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

            $sub = $this->isSubscribed();
            if ($sub === false) {
                return $this->unauthorized("Your subscription has expired");
            }

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

    //TODO: need to update subscription validation
    private function isSubscribed(): bool
    {
        // $subscription = Subscription::first();
        // $today = Carbon::now();
        // // dd("sub",$subscription->updated_at->format('Y-m-d'), $today->format('Y-m-d'));
        // if ($subscription->updated_at->format('Y-m-d') >= $today->format('Y-m-d')) {
        //     return true;
        // } else {

        //     $subdomain = env('SUBDOMAIN');

        //     $url = env('POINT_URL');
        //     $url = $url . "subdomain=$subdomain";

        //     $response = Http::get($url);
        //     $response = json_decode($response->body(), true);

        //     if ($response['success'] === 'false') {
        //         return false;
        //     } else {
        //         $expDate = $response['expireDate'];
        //     }
        //     if ($expDate >= $today->format('Y-m-d')) {
        //         $subscription->expDate = $expDate;
        //         $subscription->save();
        //         return true;
        //     } else {
        //         return false;
        //     }
        // }
        return true;
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
            $joinDate = isset($userData['joinDate']) ? new DateTime($userData['joinDate']) : null;
            $leaveDate = isset($userData['leaveDate']) ? new DateTime($userData['leaveDate']) : null;
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
                'joinDate' => $joinDate,
                'leaveDate' => $leaveDate,
                'employeeId' => isset($userData['employeeId']) ? $userData['employeeId'] : null,
                'phone' => isset($userData['phone']) ? $userData['phone'] : null,
                'bloodGroup' => isset($userData['bloodGroup']) ? $userData['bloodGroup'] : null,
                'image' => isset($userData['image']) ? $userData['image'] : null,
                'designationId' => isset($userData['designationId']) ? $userData['designationId'] : null,
                'employmentStatusId' => isset($userData['employmentStatusId']) ? $userData['employmentStatusId'] : null,
                'departmentId' => isset($userData['departmentId']) ? $userData['departmentId'] : null,
                'shiftId' => isset($userData['shiftId']) ? $userData['shiftId'] : null,
            ]);

           if(isset($userData['designationId'])){
            $this->createDesignationHistory($createUser->id, $userData);
           }
           if(isset($userData['salaryStartDate'])){
            $this->createSalaryHistory($createUser->id, $userData);
           }
           if(isset($userData['education'])){
            $this->createEducation($createUser->id, $userData);
           }
           
            unset($createUser['password']);
            $this->updateRolePermission($userData);
            DB::commit();
            return $this->response($createUser->toArray());
        } catch (Exception $error) {
            DB::rollback();
            return $this->badRequest($error);
        }
    }

    private function createDesignationHistory($userId, $userData): JsonResponse
    {
        try {
            $designationStartDate = Carbon::parse($userData['designationStartDate']);
            $designationEndDate = isset($userData['designationEndDate']) ? Carbon::parse($userData['designationEndDate']) : null;

            DesignationHistory::create([
                'userId' => $userId,
                'designationId' => $userData['designationId'] ?? null,
                'startDate' => $designationStartDate->format('Y-m-d H:i:s') ?? null,
                'endDate' => optional($designationEndDate)->format('Y-m-d H:i:s') ?? null,
                'comment' => $userData['comment'] ?? null,
            ]);
            DB::commit();
            return $this->success('Designation created successfully');
        } catch (Exception $error) {
            DB::rollback();
            return $this->badRequest($error);
        }
    }

    private function createSalaryHistory($userId, $userData): JsonResponse
    {
        try {
            $salaryStartDate = Carbon::parse($userData['salaryStartDate']);
            $salaryEndDate = isset($userData['salaryEndDate']) ? Carbon::parse($userData['salaryEndDate']) : null;
            SalaryHistory::create([
                'userId' => $userId,
                'salary' => $userData['salary'],
                'startDate' => $salaryStartDate->format('Y-m-d H:i:s'),
                'endDate' => optional($salaryEndDate)->format('Y-m-d H:i:s'),
                'comment' => $userData['salaryComment'] ?? null,
            ]);
            DB::commit();
            return $this->success('SalaryHistory created successfully');
        } catch (Exception $error) {
            DB::rollback();
            return $this->badRequest($error);
        }
    }

    private function createEducation($userId, $userData): JsonResponse
    {
        try {
            $educationData = collect($userData['education'])->map(function ($education) use ($userId) {
                $startDate = new DateTime($education['studyStartDate']);
                $endDate = isset($education['studyEndDate']) ? new DateTime($education['studyEndDate']) : null;

                return [
                    'userId' => $userId,
                    'degree' => $education['degree'],
                    'institution' => $education['institution'],
                    'fieldOfStudy' => $education['fieldOfStudy'],
                    'result' => $education['result'],
                    'studyStartDate' => $startDate->format('Y-m-d H:i:s'),
                    'studyEndDate' => optional($endDate)->format('Y-m-d H:i:s'),
                ];
            });

            Education::insert($educationData->toArray());
            DB::commit();
            return $this->success('Education created successfully');
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
