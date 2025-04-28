<?php

namespace App\Http\Controllers\HR\Award;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use App\Models\Award;
//
class AwardController extends Controller
{
    // create single and multiple award and delete many award controller method
    public function createSingleAward(Request $request): jsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                // delete many Award at once
                $data = json_decode($request->getContent(), true);
                $deletedAward = Award::destroy($data);

                $deletedCounted = [
                    'count' => $deletedAward,
                ];
                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during deleting award. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $awardData = $request->json()->all();
                $createdAward = collect($awardData)->map(function ($award) {
                    return Award::create([
                        'name' => $award['name'],
                        'description' => $award['description'] ?? null,
                    ]);
                });
                return $this->response($createdAward->toArray(),201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting creating award. Please try again later.'], 500);
            }
        } else {
            try {
                $createdAward = Award::create([
                    'name' => $request->input('name'),
                    'description' => $request->input('description') ?? null,
                ]);

                return $this->response($createdAward->toArray(),201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating award. Please try again later.'], 500);
            }
        }
    }

    // get all the award data controller method
    public function getAllAward(Request $request): jsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $allAward = Award::where('status', 'true')
                    ->orderBy('id', 'desc')
                    ->get();

                return $this->response($allAward->toArray());
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting award. Please try again later.'], 500);
            }
        } else if ($request->query('status') === 'false') {
            $pagination = getPagination($request->query());
            try {
                $allAward = Award::where('status', "false")
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $converted = $this->arrayKeysToCamelCase($allAward->toArray());
                $aggregation = [
                    'getAllAward' => $converted,
                    'totalAward' => Award::where('status', 'false')
                        ->count(),
                ];

                return response()->json($aggregation, 200);
            } catch (Exception $err) {

                return response()->json(['error' => 'An error occurred during getting award. Please try again later.'], 500);
            }
        }else if($request->query('query') === 'search'){
            $pagination = getPagination($request->query());
            try {
                $allAward = Award::where('status', "true")
                    ->where('name', 'like', '%' . $request->query('key') . '%')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                return $this->response([
                    'getAllAward' => arrayKeysToCamelCase($allAward->toArray()),
                    'totalAward' => Award::where('status', 'true')
                        ->where('name', 'like', '%' . $request->query('name') . '%')
                        ->count(),
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting award. Please try again later.'], 500);
            }
        }
         else {
            $pagination = getPagination($request->query());
            try {
                $allAward = Award::when($request->query('status'), function ($query) use ($request) {
                    $query->whereIn('status', explode(',', $request->query('status')));
                })
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                return $this->response([
                    'getAllAward' => $allAward->toArray(),
                    'totalAward' => Award::where('status', 'true')
                        ->count(),
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting award. Please try again later.'], 500);
            }
        }
    }


    // get single award data controller method
    public function getSingleAward(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->attributes->get('data');

            $awardData = Award::with(['awardHistory.user:id,firstName,lastName,username'])
                ->find($id);

            // make an array of unique usersId,
            $userIdArray = [];
            foreach ($awardData->awardHistory as $item) {
                $userId = $item->userId;
                if (!in_array($userId, $userIdArray)) {
                    $userIdArray[] = $userId;
                }
            }
            

            $filteredAwardHistory = $awardData->awardHistory->filter(function ($item) use ($data) {
                return $item['userId'] === $data['sub'];
            });

            $awardData->setRelation('awardHistory', $filteredAwardHistory);
            return $this->response($awardData->toArray());
        } catch (Exception $err) {
            echo $err;
            return response()->json(['error' => 'An error occurred during getting single award. Please try again later.'], 500);
        }
    }

    //update a single award controller method
    public function updateSingleAward(Request $request, $id): jsonResponse
    {
        try {
            $updatedAward = Award::where('id', $id)->update($request->all());

            if (!$updatedAward) {
                return response()->json(['error' => 'Failed to update Award!'], 404);
            }
            return response()->json(['message' => 'Award updated successfully'], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during update award. Please try again later.'], 500);
        }
    }

    // delete a single award controller method
    public function deleteSingleAward(Request $request, $id): jsonResponse
    {
        try {
            $deletedAward = Award::where('id', $id)->update([
                'status' => $request->input('status'),
            ]);

            if ($deletedAward) {
                return response()->json(['message' => 'Award Deleted Successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed To Delete Award'], 404);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during delete award. Please try again later.'], 500);
        }
    }
}
