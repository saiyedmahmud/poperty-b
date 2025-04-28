<?php

namespace App\Services;

use DateTime;
use Exception;
use Carbon\Carbon;
use App\Models\Users;
use Firebase\JWT\JWT;
use App\Models\Education;
use App\Models\Permission;
use App\Traits\ErrorTrait;
use App\Models\SalaryHistory;
use App\Models\RolePermission;
use Illuminate\Http\JsonResponse;
use App\Models\DesignationHistory;
use Illuminate\Support\Facades\{DB, Hash, Cookie, Http};

class UserService
{
    use ErrorTrait;

    public function UserAuthenticate($request): JsonResponse
    {

        try {

            $user = Users::where('username', $request->input('username'))->with('role:id,name')->first();

            if (!$user) {
                return $this->unauthorized("Username or password is incorrect");
            }

            $pass = Hash::check($request->input('password'), $user->password);

            if (!$pass) {
                return $this->unauthorized("Username or password is incorrect");
            }

            $token = $this->generateToken($user);

            $refreshToken = $this->generateRefreshToken($user);

            $cookie = $this->createRefreshTokenCookie($refreshToken);

            $userWithoutPassword = $this->prepareUserData($user, $token);

            $user->refreshToken = $refreshToken;
            $user->isLogin = 'true';
            $user->save();

            return $this->response($userWithoutPassword)->withCookie($cookie);
        } catch (Exception $error) {
            return $this->badRequest($error->getMessage());
        }
    }

    private function generateToken($user): string
    {
        $token = [
            "sub" => $user->id,
            "roleId" => $user['role']['id'],
            "role" => $user['role']['name'],
            //            2 min token
            //            "exp" => time() + (60 * 2)
            "exp" => time() + (60 * 60 * 6),
        ];

        return JWT::encode($token, env('JWT_SECRET'), 'HS256');
    }

    private function generateRefreshToken($user): string
    {
        $refreshToken = [
            "sub" => $user->id,
            "role" => $user['role']['name'],
            "exp" => time() + 86400 * 30
        ];

        return JWT::encode($refreshToken, env('REFRESH_SECRET'), 'HS384');
    }

    private function createRefreshTokenCookie($refreshToken): \Symfony\Component\HttpFoundation\Cookie
    {
        return Cookie::make('refreshToken', $refreshToken, 60 * 24 * 30)
            ->withPath('/')
            ->withHttpOnly()
            ->withSameSite('None')
            ->withSecure();
    }

    private function prepareUserData($user, $token): array
    {
        $userWithoutPassword = $user->toArray();
        $userWithoutPassword['role'] = $user['role']['name'];
        $userWithoutPassword['token'] = $token;
        unset($userWithoutPassword['password']);

        return $userWithoutPassword;
    }

    //register

    public function createUser(array $userData): JsonResponse
    {
        DB::beginTransaction();
        try {
            $hash = Hash::make($userData['password']);

            if ($userData['roleId'] === 1) {
                return $this->forbidden("You can not create super admin");
            }

            $createUser = Users::create([
                'firstName' => isset($userData['firstName']) ? $userData['firstName'] : null,
                'lastName' => isset($userData['lastName']) ? $userData['lastName'] : null,
                'username' => $userData['username'],
                'password' => $hash,
                'roleId' => $userData['roleId'],
                'email' => isset($userData['email']) ? $userData['email'] : null,
                'street' => isset($userData['street']) ? $userData['street'] : null,
                'city' => isset($userData['city']) ? $userData['city'] : null,
                'state' => isset($userData['state']) ? $userData['state'] : null,
                'zipCode' => isset($userData['zipCode']) ? $userData['zipCode'] : null,
                'country' => isset($userData['country']) ? $userData['country'] : null,
                'employeeId' => isset($userData['employeeId']) ? $userData['employeeId'] : null,
                'phone' => isset($userData['phone']) ? $userData['phone'] : null,
                'bloodGroup' => isset($userData['bloodGroup']) ? $userData['bloodGroup'] : null,
                'image' => isset($userData['image']) ? $userData['image'] : null,
            ]);
           
            unset($createUser['password']);
            $this->updateRolePermission($userData);
            DB::commit();
            return $this->response($createUser->toArray());
        } catch (Exception $error) {
            DB::rollback();
            return $this->badRequest($error);
        }
    }


    private function updateRolePermission($userData): JsonResponse
    {
        DB::beginTransaction();
        try {
            $permissionId = Permission::where('name', 'readSingle-rolePermission')->first()->id;
            //update role permission
            RolePermission::create([
                'roleId' => $userData['roleId'],
                'permissionId' => $permissionId,
            ]);

            DB::commit();
            return $this->success('Role Permission updated successfully');
        } catch (Exception $error) {
            DB::rollback();
            return $this->badRequest($error);
        }
    }
}
