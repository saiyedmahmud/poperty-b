<?php

namespace App\Http\Controllers\HR\EmploymentStatus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use App\Models\EmploymentStatus;

//
class EmploymentStatusController extends Controller
{
    //create employmentStatus controller method
    public function createSingleEmployment(Request $request): jsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $data = json_decode($request->getContent(), true);
                $deletedEmploymentStatus = EmploymentStatus::destroy($data);

                $deletedCounted = [
                    'count' => $deletedEmploymentStatus,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during deleting employmentStatus. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $employmentHistoryData = json_decode($request->getContent(), true);

                $createdEmploymentHistory = collect($employmentHistoryData)->map(function ($item) {
                    return EmploymentStatus::create([
                        'name' => $item['name'],
                        'colourValue' => $item['colourValue'],
                        'description' => $item['description'],
                        'isHolidayPaid' => isset($item['isHolidayPaid']) ? 'true' : 'false',
                        'isWeekendPaid' => isset($item['isWeekendPaid']) ? 'true' : 'false',
                    ]);
                });

                return $this->response($createdEmploymentHistory->toArray(), 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during create employmentStatus. Please try again later.'], 500);
            }
        } else {
            try {
                $employmentHistoryData = json_decode($request->getContent(), true);
        
                // Check if an employment status with the same name already exists
                $existingEmploymentStatusByName = EmploymentStatus::where('name', $employmentHistoryData['name'])->first();
        
                // Check if an employment status with the same colourValue already exists
                // $existingEmploymentStatusByColour = EmploymentStatus::where('colourValue', $employmentHistoryData['colourValue'])->first();
        
                // If either the name or colourValue already exists, return an appropriate error
                if ($existingEmploymentStatusByName) {
                    return response()->json([
                        'error' => 'The employment status with name "' . $employmentHistoryData['name'] . '" already exists.'
                    ], 409);
                }
        
                // if ($existingEmploymentStatusByColour) {
                //     return response()->json([
                //         'error' => 'The employment status with colourValue "' . $employmentHistoryData['colourValue'] . '" already exists.'
                //     ], 409);
                // }
        
                // Create the new employment status if no duplicates are found
                $createdEmploymentHistory = EmploymentStatus::create([
                    'name' => $employmentHistoryData['name'],
                    'colourValue' => $employmentHistoryData['colourValue'],
                    'description' => $employmentHistoryData['description'] ?? null,
                    'isHolidayPaid' => isset($employmentHistoryData['isHolidayPaid']) ? 'true' : 'false',
                    'isWeekendPaid' => isset($employmentHistoryData['isWeekendPaid']) ? 'true' : 'false',
                ]);
        
                return $this->response($createdEmploymentHistory->toArray(), 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating employment status. Please try again later.'], 500);
            }
        }
        
        
    }

    // get all the employmentStatus controller method
    public function getAllEmployment(Request $request): jsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $allEmploymentStatus = EmploymentStatus::orderBy('id', 'desc')
                    ->where('status', "true")
                    ->get();

                return $this->response($allEmploymentStatus->toArray());
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting employmentStatus. Please try again later.'], 500);
            }
        } else if ($request->query('status') === 'false') {
            try {
                $pagination = getPagination($request->query());
                $allEmploymentStatus = EmploymentStatus::orderBy('id', 'desc')
                    ->where('status', 'false')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                return $this->response([
                    'getAllEmploymentStatus' => $allEmploymentStatus->toArray(),
                    'totalEmploymentStatus' => EmploymentStatus::where('status', 'false')->count(),
                ]);

            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting employmentStatus. Please try again later.'], 500);
            }
        } else if ($request->query()) {
            try {
                $pagination = getPagination($request->query());
                $allEmploymentStatus = EmploymentStatus::orderBy('id', 'desc')
                    ->where('status', 'true')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                return $this->response([
                    'getAllEmploymentStatus' => $allEmploymentStatus->toArray(),
                    'totalEmploymentStatus' => EmploymentStatus::where('status', 'true')->count(),
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting employmentStatus. Please try again later.'], 500);
            }
        } else {
            return response()->json(['error' => 'Invalid Query'], 400);
        }
    }

    // get a single employmentStatus controller method
    public function getSingleEmployment(Request $request, $id): jsonResponse
    {
        try {
            $singleEmploymentStatus = EmploymentStatus::with('user')->findOrFail($id);

            // get specific users data filed
            $usersData = $singleEmploymentStatus->user->map(function ($user) {
                return [
                    'id' => $user->id,
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'username' => $user->username,
                ];
            });
            $singleEmploymentStatus->setRelation('user', $usersData);

            return $this->response($singleEmploymentStatus->toArray());
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting employmentStatus. Please try again later.'], 500);
        }
    }

    // delete a employmentStatus controller method
    public function deletedEmployment(Request $request, $id): jsonResponse
    {
        try {
            $deletedEmploymentStatus = EmploymentStatus::where('id', $id)->update([
                'status' => $request->input('status'),
            ]);

            if ($deletedEmploymentStatus) {
                return response()->json(['message' => 'EmploymentStatus Change Successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to Delete EmploymentStatus'], 404);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting employmentStatus. Please try again later.'], 500);
        }
    }
}
