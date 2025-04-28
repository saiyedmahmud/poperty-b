<?php

namespace App\Http\Controllers\HR\Shift;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Str;

//
class ShiftController extends Controller
{
    //crate a shift controller method
    /**
     * @throws Exception
     */
    public function createShift(Request $request): jsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                // delete many shift at once
                $data = json_decode($request->getContent(), true);
                $deletedShift = Shift::destroy($data);

                $deletedCounted = [
                    'count' => $deletedShift,
                ];
                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during deleting many Shift. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $shiftData = $request->json()->all();
                $createdShift = collect($shiftData)->map(function ($shift) {
                    return Shift::create([
                        'name' => $shift['name'],
                        'startTime' => Carbon::parse($shift['startTime']),
                        'endTime' => Carbon::parse($shift['endTime']),
                    ]);
                });

                return $this->response($createdShift->toArray(), 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating many Shift. Please try again later.'], 500);
            }
        } else {

            //check if shift name already exist
            // $shiftName = Shift::where('name', $request->input('name'))->first();
            // if ($shiftName) {
            //     return $this->forbidden('Shift name already exist');
            // }

            $existingShift = Shift::where('name', $request->input('name'))->first();
        
                if ($existingShift) {
                    // Return an error if the designation already exists
                    return response()->json([
                        'error' => 'The shift with name "' . $request->input('name') . '" already exists.'
                    ], 409);
                }

            // $startTime = new DateTime($request->input('startTime'));
            // $endTime = new DateTime($request->input('endTime'));

            $startTime = Carbon::parse($request->input('startTime'))->format('H:i:s');
            $endTime = Carbon::parse($request->input('endTime'))->format('H:i:s');
            
            // If the end time is earlier than the start time, it means the period crosses midnight
            if ($endTime < $startTime) {
                // Add 24 hours to the end time to represent the next day
                $endTime = Carbon::parse($endTime)->addDay()->format('H:i:s');
            }
            
            $interval = Carbon::parse($startTime)->diff(Carbon::parse($endTime));
            $workHour = $interval->h + ($interval->days * 24) + ($interval->i / 60);
            
            // Output the work hour
            
            

            try {
                $createdShift = Shift::create([
                    'name' => $request->input('name'),
                    'startTime' => $startTime,
                    'endTime' => $endTime,
                    'workHour' => $workHour,
                ]);

                return $this->response($createdShift->toArray(), 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        }
    }

    // get all shift data controller method
    public function getAllShift(Request $request): jsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $allShift = Shift::orderBy('id', 'desc')
                    ->where('status', "true")
                    ->get();

                return $this->response($allShift->toArray());
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting Shift. Please try again later.'], 500);
            }
        } else if ($request->query('status') === "false") {
            $pagination = getPagination($request->query());
            try {
                $allShift = Shift::orderBy('id', 'desc')
                    ->where('status', "false")
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                return $this->response([
                    'getAllShift' => $allShift->toArray(),
                    'totalShift' => Shift::where('status', 'false')->count(),
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting Shift. Please try again later.'], 500);
            }
        } else if ($request->query()) {
            $pagination = getPagination($request->query());
            try {
                $allShift = Shift::orderBy('id', 'desc')
                    ->when($request->query('status'), function ($query) use ($request) {
                        return $query->whereIn('status', explode(',', $request->query('status')));
                    })
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                return $this->response([
                    'getAllShift' => $allShift->toArray(),
                    'totalShift' => Shift::when($request->query('status'), function ($query) use ($request) {
                        return $query->whereIn('status', explode(',', $request->query('status')));
                    })
                        ->count(),
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting Shift. Please try again later.'], 500);
            }
        } else {
            return response()->json(['error' => 'Invalid Query!'], 400);
        }
    }

    // get a single shift data controller method
    public function getSingleShift(Request $request, $id): jsonResponse
    {
        try {
            $singleShift = Shift::with(['user'])
                ->findOrFail($id);

            $usersData = $singleShift->user->map(function ($user) {
                return [
                    'id' => $user->id,
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'username' => $user->username,
                ];
            });
            $singleShift->setRelation('user', $usersData);

            return $this->response($singleShift->toArray());
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting Shift. Please try again later.'], 500);
        }
    }

    // update a single shift data controller method
    public function updateSingleShift(Request $request, $id): jsonResponse
    {
        $startTime = new DateTime($request->input('startTime'));
        $endTime = new DateTime($request->input('endTime'));

        $interval = $endTime->diff($startTime);
        $workHour = $interval->h + ($interval->days * 24);
        if ($workHour < 0) {
            $workHour = 24 + $workHour;
        }

        try {

            
            $updatedShift = Shift::findOrFail($id);
            $updatedShift->update([
                'name' => $request->input('name'),
                'startTime' => $startTime,
                'endTime' => $endTime,
                'workHour' => $workHour,
            ]);

            return $this->response($updatedShift->toArray());
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating Shift. Please try again later.', $err->getMessage()], 500);
        }
    }

    // delete a single shift data controller method
    public function deleteSingleShift(Request $request, $id): jsonResponse
    {
        try {
            $deletedShift = Shift::where('id', (int)$id)->update(['status' => $request->input('status')]);


            if ($deletedShift) {
                return response()->json(['message' => 'Shift Hided Successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to delete shift!'], 404);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting Shift. Please try again later.'], 500);
        }
    }
}
