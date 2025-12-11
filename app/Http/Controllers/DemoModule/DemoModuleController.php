<?php

namespace App\Http\Controllers\DemoModule;

use App\Http\Controllers\Controller;
use App\Models\DemoModule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DemoModuleController extends Controller
{
    public function createSingleDemoModule(Request $request): JsonResponse
    {
        try {
            if ($request->query('query') === 'deletemany') {
                $data = json_decode($request->getContent(), true);
                $deleteMany = DemoModule::destroy($data);

                return response()->json([
                    'count' => $deleteMany,
                ], 200);
            } elseif ($request->query('query') === 'createmany') {
                $data = json_decode($request->getContent(), true);

                //check if demoModule already exists
                $data = collect($data)->map(function ($item) {
                    $data = DemoModule::where('name', $item['name'])->first();
                    if ($data) {
                        return null;
                    }
                    return $item;
                })->filter(function ($item) {
                    return $item !== null;
                })->toArray();

                //if all demoModules already exists
                if (count($data) === 0) {
                    return response()->json(['error' => 'All DemoModule already exists.'], 500);
                }

                $createdDemoModule = collect($data)->map(function ($item) {
                    return DemoModule::firstOrCreate($item);
                });

                return response()->json(['count' => count($createdDemoModule)], 201);
            } else {
                $createdDemoModule = DemoModule::create([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'status' => $request->input('status', 'true'),
                ]);
                return $this->response($createdDemoModule->toArray());
            }
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during create DemoModule. Please try again later.'], 500);
        }
    }

    public function getAllDemoModule(Request $request): JsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $allDemoModule = DemoModule::orderBy('id', 'desc')
                    ->where('status', 'true')
                    ->get();

                return $this->response([
                    'getAllDemoModule' => $allDemoModule->toArray(),
                    'totalDemoModule' => DemoModule::where('status', 'true')
                        ->count(),
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting DemoModule. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $key = trim($request->query('key'));

                $getAllDemoModule = DemoModule::orWhere('name', 'LIKE', '%' . $key . '%')
                    ->orWhere('description', 'LIKE', '%' . $key . '%')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $allDemoModuleCount = DemoModule::orWhere('name', 'LIKE', '%' . $key . '%')
                    ->orWhere('description', 'LIKE', '%' . $key . '%')
                    ->count();

                return $this->response([
                    'getAllDemoModule' => $getAllDemoModule->toArray(),
                    'totalDemoModule' => $allDemoModuleCount,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during getting DemoModule. Please try again later.'], 500);
            }
        } else if ($request->query()) {
            try {
                $pagination = getPagination($request->query());
                $getAllDemoModule = DemoModule::when($request->query('status'), function ($query) use ($request) {
                    return $query->whereIn('status', explode(',', $request->query('status')));
                })
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $allDemoModuleCount = DemoModule::when($request->query('status'), function ($query) use ($request) {
                    return $query->whereIn('status', explode(',', $request->query('status')));
                })
                    ->count();

                return $this->response([
                    'getAllDemoModule' => $getAllDemoModule->toArray(),
                    'totalDemoModule' => $allDemoModuleCount,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during getting DemoModule. Please try again later.'], 500);
            }
        } else {
            return response()->json(['error' => 'Invalid query!'], 400);
        }
    }

    public function getSingleDemoModule(Request $request, $id): JsonResponse
    {
        try {
            $singleDemoModule = DemoModule::find($id);
            return $this->response($singleDemoModule->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during getting DemoModule. Please try again later.'], 500);
        }
    }

    public function updateSingleDemoModule(Request $request, $id): JsonResponse
    {
        try {
            $updatedDemoModule = DemoModule::where('id', $id)->first();

            if (!$updatedDemoModule) {
                return $this->badRequest('DemoModule not found!');
            }

            $updatedDemoModule->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'status' => $request->input('status', $updatedDemoModule->status),
            ]);

            return response()->json(['message' => 'DemoModule Updated Successfully'], 200);

        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during update DemoModule. Please try again later.'], 500);
        }
    }

    public function deleteSingleDemoModule(Request $request, $id): JsonResponse
    {
        try {
            $deletedDemoModule = DemoModule::where('id', $id)->first();

            if (!$deletedDemoModule) {
                return $this->badRequest('DemoModule not found!');
            }

            $deletedDemoModule->status = $request->input('status');
            $deletedDemoModule->save();

            return response()->json(['message' => 'DemoModule Deleted Successfully'], 200);
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during delete DemoModule. Please try again later.'], 500);
        }
    }
}
