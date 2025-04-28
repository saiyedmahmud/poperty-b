<?php

namespace App\Http\Controllers\Crm\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Str;
use App\Models\ContactStage;


class ContactStageController extends Controller
{
    //create ContactStage controller method
    public function createContactStage(Request $request): jsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedContactStage = ContactStage::destroy($ids);

                $deletedCounted = [
                    'count' => $deletedContactStage,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during deleting many contact stage. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $contactStageData = json_decode($request->getContent(), true);

                $createdContactStage = collect($contactStageData)->map(function ($item) {
                    return ContactStage::create([
                        'contactStageName' => $item['contactStageName'],
                    ]);
                });

                $converted = arrayKeysToCamelCase($createdContactStage->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating many contact stage. Please try again later.'], 500);
            }
        } else {
            try {
                $contactStageData = json_decode($request->getContent(), true);

                $createdContactStage = ContactStage::create([
                    'contactStageName' => $contactStageData['contactStageName'],
                ]);

                $converted = arrayKeysToCamelCase($createdContactStage->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating a single contact stage. Please try again later.'], 500);
            }
        }
    }

    // get all the contact Stage data controller method
    public function getAllContactStage(Request $request): jsonResponse
    {
        try {
            $allContactStage = ContactStage::with('contact')->orderBy('id', 'desc')->get();

            $allContactStage->each(function ($item) {
                $item->contact->each(function ($x) {
                    $x->fullName = $x->firstName . " " . $x->lastName;
                });
            });

            $converted = arrayKeysToCamelCase($allContactStage->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting all contact stage. Please try again later.'], 500);
        }
    }

    // get single contact Stage data controller method
    public function getSingleContactStage(Request $request, $id): jsonResponse
    {
        try {
            $singleContactStage = ContactStage::with('contact')->orderBy('id', 'desc')->findOrFail($id);

            $singleContactStage->contact->each(function ($x) {
                $x->fullName = $x->firstName . " " . $x->lastName;
            });

            $converted = arrayKeysToCamelCase($singleContactStage->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting a single contact stage. Please try again later.'], 500);
        }
    }

    // update a single contact Stage controller method
    public function updateContactStage(Request $request, $id): jsonResponse
    {
        try {
            $contactStageData = json_decode($request->getContent(), true);

            $updatedContactStage = ContactStage::where('id', $id)->first();
            $updatedContactStage->update([
                'contactStageName' => $contactStageData['contactStageName'],
            ]);

            $converted = arrayKeysToCamelCase($updatedContactStage->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a single contact stage. Please try again later.'], 500);
        }
    }

    // delete a single contactSource controller method
    public function deleteContactStage(Request $request, $id): jsonResponse
    {
        try {
            $deletedContactStage = ContactStage::where('id', $id)->delete();

            if ($deletedContactStage) {
                return response()->json(['message' => "Contact Stage has been deleted"], 200);
            } else {
                return response()->json(['error' => 'Failed to delete a Contact Stage!'], 409);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a single contact stage. Please try again later.'], 500);
        }
    }
}
