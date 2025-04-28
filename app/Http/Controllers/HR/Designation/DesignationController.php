<?php

namespace App\Http\Controllers\HR\Designation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\jsonResponse;
use Exception;
use App\Models\Designation;

class DesignationController extends Controller
{
    // create designation controller method
    public function createSingleDesignation(Request $request): jsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                // delete many Designation at once
                $data = json_decode($request->getContent(), true);
                $deletedDesignation = Designation::destroy($data);

                $deletedCounted = [
                    'count' => $deletedDesignation,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting $designations. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $designations = json_decode($request->getContent(), true);
                
                // Extract the names of the designations
                $designationNames = collect($designations)->pluck('name')->toArray();
                
                // Find existing designations with the same name
                $existingDesignations = Designation::whereIn('name', $designationNames)->pluck('name')->toArray();
                
                if (!empty($existingDesignations)) {
                    // Return error if duplicates are found
                    return response()->json([
                        'error' => 'Duplicate entries found: ' . implode(', ', $existingDesignations)
                    ], 409);
                }
        
                // Proceed to create non-duplicate designations
                $createdDesignation = collect($designations)->map(function ($designation) {
                    return Designation::create([
                        'name' => $designation['name'],
                    ]);
                });
        
                return response()->json(['count' => count($createdDesignation)], 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating designations. Please try again later.'], 500);
            }
        }
        else {
            try {
                // Check if a designation with the same name already exists
                $existingDesignation = Designation::where('name', $request->input('name'))->first();
        
                if ($existingDesignation) {
                    // Return an error if the designation already exists
                    return response()->json([
                        'error' => 'The designation with name "' . $request->input('name') . '" already exists.'
                    ], 409);
                }
        
                // Create the new designation if it doesn't exist
                $createdDesignation = Designation::create([
                    'name' => $request->input('name'),
                ]);
        
                return $this->response($createdDesignation->toArray(), 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating the designation. Please try again later.'], 500);
            }
        }
        
    }

    // get all the designation controller method
    public function getAllDesignation(Request $request): jsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $allDesignation = Designation::orderBy('id', 'desc')
                    ->where('status', "true")
                    ->with('user.role', 'user.designationHistory.designation')
                    ->get();

                collect($allDesignation)->each(function ($item) {
                    collect($item->user)->each(function ($x) {
                        unset($x->password);
                    });
                });

                return $this->response($allDesignation->toArray());
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting $designations. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'info') {
            try {
                $aggregations = [
                    '_count' => [
                        'id' => Designation::where('status', 'true')
                            ->count()
                    ],
                ];

                return response()->json($aggregations, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting $designations. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $key = trim($request->query('key'));

                $allDesignation = Designation::orWhere('name', 'LIKE', '%' . $key . '%')
                    ->orderBy('id', 'desc')
                    ->with('user.role', 'user.designationHistory.designation')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $allDesignationCount = Designation::orWhere('name', 'LIKE', '%' . $key . '%')
                    ->count();

                collect($allDesignation)->each(function ($item) {
                    collect($item->user)->each(function ($x) {
                        unset($x->password);
                    });
                });

                return $this->response([
                    'getAllDesignation' => $allDesignation->toArray(),
                    'totalDesignation' => $allDesignationCount,
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting $designations. Please try again later.'], 500);
            }
        } else if ($request->query()) {
            $pagination = getPagination($request->query());
            try {
                $allDesignation = Designation::when($request->query('status'), function ($query) use ($request) {
                    return $query->whereIn('status', explode(',', $request->query('status')));
                })
                    ->orderBy('id', 'desc')
                    ->with('user.role', 'user.designationHistory.designation')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $allDesignationCount = Designation::when($request->query('status'), function ($query) use ($request) {
                    return $query->whereIn('status', explode(',', $request->query('status')));
                })
                    ->count();

                collect($allDesignation)->each(function ($item) {
                    collect($item->user)->each(function ($x) {
                        unset($x->password);
                    });
                });

                return $this->response([
                    'getAllDesignation' => $allDesignation->toArray(),
                    'totalDesignation' => $allDesignationCount,
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting $designations. Please try again later.'], 500);
            }
        } else {
            return response()->json(['error' => 'Invalid Query'], 400);
        }
    }

    // get a single designation controller method
    public function getSingleDesignation(Request $request, $id): jsonResponse
    {
        try {
            $singleDesignation = Designation::where('id', (int)$id)
            ->with('user.role', 'user.designationHistory.designation')
            ->first();

            collect($singleDesignation->user)->each(function ($x) {
                unset($x->password);
            });


            return $this->response($singleDesignation->toArray());
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting single $designations. Please try again later.'], 500);
        }
    }

    // update a designation controller method
    public function updateSingleDesignation(Request $request, $id): jsonResponse
    {
        try {

            $existingDesignation = Designation::where('name', $request->input('name'))->first();
        
            if ($existingDesignation) {
                // Return an error if the designation already exists
                return response()->json([
                    'error' => 'The designation with name "' . $request->input('name') . '" already exists.'
                ], 409);
            }
            $updatedDesignation = Designation::where('id', $id)->update($request->all());
    

            if (!$updatedDesignation) {
                return response()->json(['error' => 'Failed To Update Designation'], 404);
            }
            return response()->json(['message' => 'Designation updated successfully'], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during update $designations. Please try again later.'], 500);
        }
    }

    // delete a designation controller method
    public function deleteSingleDesignation(Request $request, $id): jsonResponse
    {
        try {
            $deletedDesignation = Designation::where('id', $id)->update([
                'status' => $request->input('status'),
            ]);

            if ($deletedDesignation) {
                return response()->json(['message' => 'Designation Deleted Successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed To Delete Designation'], 404);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during delete $designations. Please try again later.'], 500);
        }
    }
}
