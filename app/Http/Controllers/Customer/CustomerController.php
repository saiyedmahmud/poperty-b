<?php

namespace App\Http\Controllers\Customer;

use DateTime;
use Exception;
use App\Models\Role;
use Firebase\JWT\JWT;
use App\Models\Customer;
use App\Models\AppSetting;
use App\Models\EmailConfig;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\MailStructure\MailStructure;
use App\Models\Contact;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;


class CustomerController extends Controller
{
    protected MailStructure $MailStructure;

    public function __construct(MailStructure $MailStructure)
    {
        $this->MailStructure = $MailStructure;
    }

    public function customerLogin(Request $request): jsonResponse
    {
        try {
            $loggedCustomer = json_decode($request->getContent(), true);
            if (!preg_match('/^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/', $loggedCustomer['email'])) {
                return response()->json(['error' => 'Invalid Email!'], 400);
            }

            $customer = Customer::where('email', $loggedCustomer['email'])->first();
            // check authentication using email and password;
            if (!($customer && Hash::check($loggedCustomer['password'], $customer->password))) {
                return response()->json(['error' => 'username or password is incorrect'], 404);
            }

            $permissions = Role::with('RolePermission.permission')
                ->where('id', $customer['role']['id'])
                ->first();

            $token = array(
                "sub" => $customer->id,
                "roleId" => $customer->roleId,
                "role" => $permissions->name,
                "exp" => time() + 86400
            );

            $jwt = JWT::encode($token, env('JWT_SECRET'), 'HS256');

            unset($customer->password);
            Customer::where('email', $loggedCustomer['email'])->update([
                'isLogin' => 'true',
            ]);

            $customer->token = $jwt;
            $customer->profileImage = $customer->profileImage ? url('/') . '/customer-profileImage/' . $customer->profileImage : null;

            return response()->json($customer->toArray());

        } catch (Exception $err) {
            return response()->json(['error' => $err->getMessage()], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $user = Customer::findOrFail($request->id);
            $user->isLogin = 'false';
            $user->save();
            $cookie = Cookie::forget('refreshToken');

            return $this->success('Logout successfully')->withCookie($cookie);
        } catch (Exception $error) {
            return $this->badRequest($error->getMessage());
        }
    }

    public function resetPassword(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->attributes->get("data");
            if ($data['sub'] !== (int)$id) {
                return response()->json(['error' => 'You are not authorized to access this data.'], 401);
            }

            $customer = Customer::findOrFail($id);
            $checkingOldPassword = Hash::check($request->input('oldPassword'), $customer->password);

            if ($request->input('oldPassword') === $request->input('password')) {
                return response()->json(['error' => 'Old password and new password should not be same!'], 400);
            }

            if ($checkingOldPassword === false) {
                return response()->json(['error' => 'Old password does not match!'], 400);
            }

            $newHashedPassword = Hash::make($request->input('password'));

            $updatedPassword = Customer::where('id', $id)->update([
                'password' => $newHashedPassword,
            ]);

            if (!$updatedPassword) {
                return $this->badRequest('Password Not Updated!');
            }
            return $this->success('password reset successfully');
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    public function requestForgetPassword(Request $request): JsonResponse
    {
        try {
            $customerEmail = $request->input('email');

            //check the email is not fake email using regex
            if (!preg_match('/^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/', $customerEmail)) {
                return response()->json(['error' => 'Invalid Email!'], 400);
            }

            //validate email
            $validEmail = $request->validate([
                'email' => 'required|email',
            ]);

            if (!$validEmail) {
                return $this->badRequest('Invalid Email!');
            }

            $customer = Customer::where('email', $customerEmail)->first();

            if (!$customer) {
                return $this->badRequest('Invalid Email!');
            }

            //company
            $companyName = AppSetting::first();
            $emailConfig = EmailConfig::first();

            //convert the email before @
            $email = explode('@', $customerEmail);

            $token = PasswordResetToken::Create(
                [
                    'userId' => $customer->id,
                    'token' => Str::random(60),
                    'experiresAt' => now()->addHours(2),
                ]
            );

            $forgetPassLink = env('APP_URL') . '/forget-password/' . $token->token;

            $mailData = [
                'title' => 'request forget password',
                'name' => $email[0],
                'resetLink' => $forgetPassLink,
                'expiryHours' => '2',
                'companyName' => $companyName->companyName,
            ];

            $emailSent = $this->MailStructure->requestForgetPassword($customerEmail, $mailData);

            if ($emailSent === false) {
                return response()->json(['error' => 'Email Not Sent!'], 500);
            }

            return response()->json(['message' => 'Please check your mail']);
        } catch (Exception $err) {
            return response()->json(['error' => $err->getMessage()], 500);
        }
    }

    // forgot Password controller method
    public function forgotPassword(Request $request): jsonResponse
    {
        try {

            $token = $request->input('token');
            $confirmPassword = $request->input('confirmPassword');
            $password = $request->input('password');

            if ($confirmPassword !== $password) {
                return response()->json(['error' => 'Password does not match!'], 400);
            }

            $token = PasswordResetToken::where('token', $token)->first();

            if (!$token) {
                return response()->json(['error' => 'Invalid Token!'], 404);
            }

            if ($token->experiresAt < now()) {
                return response()->json(['error' => 'Token Expired!'], 404);
            }

            $customer = Customer::where('id', $token->userId)->first();

            if (!$customer) {
                return response()->json(['error' => 'Customer Not Found!'], 404);
            }

            $newHashedPassword = Hash::make($password);

            $updatedPassword = Customer::where('id', $token->userId)->update([
                'password' => $newHashedPassword,
            ]);

            if (!$updatedPassword) {
                return response()->json(['error' => 'password not updated!'], 404);
            }

            $token->delete();

            return response()->json(['message' => 'password reset successfully'], 200);
        } catch (Exception $err) {
            return response()->json(['error' => $err->getMessage()], 500);
        }
    }

    private function makePassword($length): string
    {
        $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $password = "";

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $password;
    }
    
    public function registerCustomer(Request $request): jsonResponse
    {
        try {
            $password = $request->input('password');
            $confirmPassword = $request->input('confirmPassword');
            $email = $request->input('email');

            if (!preg_match('/^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/', $email)) {
                return response()->json(['error' => 'Invalid Email!'], 400);
            }

            if ($confirmPassword !== $password) {
                return response()->json(['error' => 'Password does not match!'], 400);
            }

            if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
                return response()->json(['error' => 'Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.'], 400);
            }

            //check if email already exists
            
            
            $hashedPass = Hash::make($password);
            $roleId = 3;
            // $customer = Customer::where('email', $email)->first();

            // if ($customer) {
            //     // Update the existing customer
            //     $customer->update([
            //         'username' => htmlspecialchars($request->input('username')),
            //         'password' => $hashedPass,
            //         'roleId' => $roleId,
            //     ]);
            // } else {
            //     // Create a new customer
            //     $customer = Customer::create([
            //         'username' => htmlspecialchars($request->input('username')),
            //         'email' => $email,
            //         'password' => $hashedPass,
            //         'roleId' => $roleId,
            //     ]);
            // }
            $customer = Customer::updateOrCreate(
                ['email' => $email], // Condition to check for existing customer
                [
                    'username' => htmlspecialchars($request->input('username')),
                    'password' => $hashedPass,
                    'roleId' => $roleId,
                ]
            );
           
            $companyName = AppSetting::first();
            $mailData = [
                'title' => "New Account",
                "name" => htmlspecialchars($request->input('username')),
                "email" => $email,
                "password" => $password,
            ];

            try {
                $this->MailStructure->NewAccount($request->email, $mailData);
            } catch (Exception $err) {
                return response()->json(['error' => 'Email Not Sent!' . $err->getMessage(),], 404);
            }

            unset($customer->password);
            return $this->response($customer->toArray());
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    public function createSingleCustomer(Request $request): jsonResponse
    {
        DB::beginTransaction();
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedCustomer = Customer::destroy($ids);
                DB::commit();
                return response()->json($deletedCustomer, 200);
            } catch (Exception $err) {
                DB::rollBack();
                return response()->json(['error' => $err->getMessage()], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $customerData = json_decode($request->getContent(), true);

                //check if product already exists
                $customerData = collect($customerData)->map(function ($item) {
                    $customer = Customer::where('email', $item['email'])->first();
                    if ($customer) {
                        return null;
                    }
                    return $item;
                })->filter(function ($item) {
                    return $item !== null;
                })->toArray();

                //if all products already exists
                if (count($customerData) === 0) {
                    return response()->json(['error' => 'All Customer Email already exists.'], 500);
                }
                $createdCustomer = collect($customerData)->map(function ($item) {
                    $randomPassword = $this->makePassword(10);
                    $hashedPass = Hash::make($randomPassword);

                    return Customer::firstOrCreate([
                        'username' => $item['username'],
                        'email' => $item['email'] ?? null,
                        'phone' => $item['phone'],
                        'address' => $item['address'],
                        'password' => $hashedPass,
                    ]);
                });

                $createdCustomer->map(function ($item) {
                    unset($item->password);
                });
                DB::commit();
                return response()->json($createdCustomer, 201);
            } catch (Exception $err) {
                DB::rollBack();
                return response()->json(['error' => $err->getMessage()], 500);
            }
        } else {
            try {

                $randomPassword = $this->makePassword(10);
                $hashedPass = Hash::make($randomPassword);
                $customerData = json_decode($request->getContent(), true);

                if (isset($customerData['email'])) {
                    $customer = Customer::where('email', $customerData['email'])->first();
                    if ($customer) {
                        return response()->json(['error' => 'Customer email already exists.'], 500);
                    }
                    $companyName = AppSetting::first();
                    $emailConfig = EmailConfig::first();
                    //convert the email before @
                    $email = explode('@', $request->email);
                    $createdCustomer = Customer::create([
                        'username' => $request->input('username') ?? $email[0],
                        'email' => $request->input('email'),
                        'phone' => $request->input('phone') ?? null,
                        'address' => $request->input('address') ?? null,
                        'password' => $hashedPass,
                        'contactId' => $request->input('contactId') ?? null,
                    ]);

                    $mailData = [
                        'title' => "New Account",
                        "body" => $request->body,
                        "name" => $request->username,
                        "email" => $request->email,
                        "password" => $randomPassword,
                        "companyName" => $companyName->companyName,
                    ];

                    try {
                        $this->MailStructure->NewAccount($request->email, $mailData);
                    } catch (Exception $err) {
                        DB::rollBack();
                        return response()->json(['error' => 'Email Not Sent!' . $err->getMessage(),], 404);
                    }
                    unset($createdCustomer->password);
                    DB::commit();
                    return response()->json(['message' => 'Please check your mail', 'data' => $createdCustomer], 201);
                }

                $createdCustomer = Customer::create([
                    'username' => $request->input('username'),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                    'password' => $hashedPass,
                    'contactId' => $request->input('contactId') ?? null,
                ]);
                unset($createdCustomer->password);
                DB::commit();
                return response()->json([$createdCustomer], 201);
            } catch (Exception $err) {
                DB::rollBack();
                return response()->json(['error' => $err->getMessage()], 500);
            }
        }
    }

    // get all the customer controller method;
    public function getAllCustomer(Request $request): jsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $allCustomer = Customer::orderBy('id', 'desc')->get();

                // secure data removing password form customer data;
                collect($allCustomer)->map(function ($item) {
                    unset($item->password);
                    return $item->fullName = $item->firstName . $item->lastName;
                });


                $converted = arrayKeysToCamelCase($allCustomer->toArray());
                return response()->json($converted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting all customer. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'search') {
            try {
                $key = trim($request->query('key'));
                $pagination = getPagination($request->query());
                $getAllCustomer = Customer::where(function ($query) use ($key) {
                    $query->where('firstName', 'ilike', '%' . $key . '%')
                        ->orWhere('lastName', 'ilike', '%' . $key . '%')
                        ->orWhere('email', 'ilike', '%' . $key . '%')
                        ->orWhere('phone', 'ilike', '%' . $key . '%');
                })->orderBy('id', 'desc')->skip($pagination['skip'])->take($pagination['limit'])->get();

                // secure data removing password form customer data;
                collect($getAllCustomer)->map(function ($item) {
                    unset($item->password);
                    return $item->fullName = $item->firstName . $item->lastName;
                });
                $converted = arrayKeysToCamelCase($getAllCustomer->toArray());
                $finalResult = [
                    'getAllCustomer' => $converted,
                    'totalCustomerCount' => [
                        '_count' => [
                            'id' => count($converted),
                        ],
                    ],
                ];

                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting all customer. Please try again later.'], 500);
            }
        }
         else if ($request->query()) {
            $statusQuery = $request->query('status');
            $roleIdQuery = $request->query('role');

            $statusArray = explode(',', $statusQuery);
            $booleanStatus = array_map(function ($status) {
                return filter_var($status, FILTER_VALIDATE_BOOLEAN);
            }, $statusArray);

            $roleIdArray = array_map('intval', explode(',', $roleIdQuery));

            if (count($booleanStatus) === 2) {
                try {
                    $pagination = getPagination($request->query());
                    $getAllCustomer = Customer::whereIn('roleId', $roleIdArray)
                        ->where(function ($query) use ($booleanStatus) {
                            $query->orWhere('status', $booleanStatus[0]);
                            $query->orWhere('status', $booleanStatus[1]);
                        })->orderBy('id', 'desc')->skip($pagination['skip'])->take($pagination['limit'])->get();

                    // secure data removing password form customer data;
                    collect($getAllCustomer)->map(function ($item) {
                        unset($item->password);
                        return $item->fullName = $item->firstName . $item->lastName;
                    });
                    $converted = arrayKeysToCamelCase($getAllCustomer->toArray());
                    $finalResult = [
                        'getAllCustomer' => $converted,
                        'totalCustomerCount' => [
                            '_count' => [
                                'id' => count($converted),
                            ],
                        ],
                    ];

                    return response()->json($finalResult, 200);
                } catch (Exception $err) {
                    return response()->json(['error' => 'An error occurred during getting all customer. Please try again later.'], 500);
                }
            } else if (count($booleanStatus) === 1) {
                try {
                    $pagination = getPagination($request->query());
                    $filteredCustomer = Customer::whereIn('roleId', $roleIdArray)
                        ->where(function ($query) use ($booleanStatus) {
                            $query->where('status', $booleanStatus[0]);
                        })->orderBy('id', 'desc')->skip($pagination['skip'])->take($pagination['limit'])->get();

                    // secure data removing password form customer data;
                    collect($filteredCustomer)->map(function ($item) {
                        unset($item->password);
                        return $item->fullName = $item->firstName . $item->lastName;
                    });
                    $converted = arrayKeysToCamelCase($filteredCustomer->toArray());
                    // total customer count
                    $finalResult = [
                        'filteredCustomer' => $converted,
                        'totalCustomerCount' => count($filteredCustomer),
                    ];

                    return response()->json($finalResult, 200);
                } catch (Exception $err) {
                    return response()->json(['error' => 'An error occurred during getting all customer. Please try again later.'], 500);
                }
            }
        }
        return response()->json(['error' => 'Invalid query!'], 400);
    }

    // get a single customer data controller method;
    public function getSingleCustomer(Request $request, $id): jsonResponse
    {
        try {
            $singleCustomer = Customer::where('id', $id)->with('role', 'ticket', 'ticket.ticketStatus', 'ticket.ticketPriority', 'ticket.ticketCategory', 'ticket.ticketComment')->first();

            // to secure data removing password form customer data;
            unset($singleCustomer->password);
            $singleCustomer->fullName = $singleCustomer->firstName . " " . $singleCustomer->lastName;

            $converted = arrayKeysToCamelCase($singleCustomer->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting a single customer. Please try again later.'], 500);
        }
    }

    // update a single customer data controller method;
    public function updateSingleCustomer(Request $request, $id): jsonResponse
    {
        try {
            // $customerData = json_decode($request->getContent(), true);


            // if (isset($customerData['password'])) {
            //     unset($customerData['password']);
            //     return response()->json(['error' => 'password cannot be updated!'], 400);
            // } else if (isset($customerData['resetPassword'])) {
            //     $customerData['password'] = Hash::make($customerData['resetPassword']);
            // }

            // unset($customerData['resetPassword']);
            // $updatedCustomer = Customer::where('id', $id)->update($customerData);

            // return response()->json($updatedCustomer, 200);


            $file_paths = $request->file_paths;
            if (isset($request['password'])) {
                unset($request['password']);
                return response()->json(['message' => 'password cannot be updated!'], 400);
            }

            unset($request['resetPassword']);

            $customer = Customer::where('id', $id)->first();
            $updatedCustomer = Customer::where('id', $id)->update([
                'profileImage' => $file_paths[0] ?? $customer->profileImage,
                'username' => $request->username ?? $customer->username,
                'email' => $request->email ?? $customer->email,
                'firstName' => $request->firstName ?? $customer->firstName,
                'lastName' => $request->lastName ?? $customer->lastName,
                'phone' => $request->phone ?? $customer->phone,
                'address' => $request->address ?? $customer->address,
            ]);


            if (!$updatedCustomer) {
                return $this->badRequest('Customer Not Updated!');
            }

            return $this->success('Customer Updated SuccessFully');
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a single customer. Please try again later.'], 500);
        }
    }

    public function getProfile(Request $request): jsonResponse
    {
        try {
            $data = $request->attributes->get("data");
            if ($data['role'] === 'customer') {
                $customer = Customer::where('id', $data['sub'])->first();

                if (!$customer) {
                    return response()->json(['error' => 'Customer Not Found!'], 404);
                }

                unset($customer->password);
                if ($customer->googleId) {
                    if (str_contains($customer->profileImage, 'googleusercontent')) {
                        $customer->profileImage = $customer->profileImage;
                    } else {
                        $customer->profileImage = $customer->profileImage ? url('/') . '/customer-profileImage/' . $customer->profileImage : null;
                    }
                } else {
                    $customer->profileImage = $customer->profileImage ? url('/') . '/customer-profileImage/' . $customer->profileImage : null;
                }
                return $this->response($customer->toArray());
            }
            return $this->unauthorized('You are not authorized to access this data.');
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    public function profileUpdate(Request $request): jsonResponse
    {
        try {
            $data = $request->attributes->get("data");
            $customerData = json_decode($request->getContent(), true);

            if ($data['role'] === 'customer') {
                $customer = Customer::where('id', $data['sub'])->first();
                $updatedCustomer = Customer::where('id', $data['sub'])->update([
                    'username' => $customerData['username'] ?? $customer->username,
                    'phone' => $customerData['phone'] ?? $customer->phone,
                    'address' => $customerData['address'] ?? $customer->address,
                ]);

                if (!$updatedCustomer) {
                    return response()->json(['error' => 'Customer Not Updated!'], 404);
                }

                return response()->json(['message' => 'Customer Updated SuccessFully'], 200);
            }
            return $this->unauthorized('You are not authorized to access this data.');
        } catch (Exception $err) {
            return response()->json(['error' => $err->getMessage()], 500);
        }
    }

    // delete a single customer data controller method
    public function deleteSingleCustomer(Request $request, $id): jsonResponse
    {
        try {
            $status = json_decode($request->getContent(), true);
            Customer::where('id', $id)->update([
                'status' => $status['status'],
            ]);

            return response()->json(["message" => "Customer Deleted Successfully"], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a single customer. Please try again later.'], 500);
        }
    }

    public function getCustomerByEmail(Request $request): jsonResponse
    {
            try {
                $customerData = Customer::where('email', $request->query('email'))->get();
               
                if ($customerData->isEmpty()) {
                    return response()->json(['status' => "false"], 200);
                }

                if($customerData->isNotEmpty() && $customerData->first()->password) {
                    return response()->json(['status' => "true"], 200);
                }
                return response()->json(['status' => "false"], 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }

        
    }
}
