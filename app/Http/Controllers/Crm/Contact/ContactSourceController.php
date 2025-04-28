<?php

namespace App\Http\Controllers\Crm\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Str;
use App\Models\ContactSource;


class ContactSourceController extends Controller
{
    //create contactSource controller method
    public function createContactSource(Request $request): jsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedContactSource = ContactSource::destroy($ids);

                $deletedCounted = [
                    'count' => $deletedContactSource,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $contactSourceData = json_decode($request->getContent(), true);

                $createdContactSource = collect($contactSourceData)->map(function ($item) {
                    return ContactSource::create([
                        'contactSourceName' => $item['contactSourceName'],
                    ]);
                });

                $converted = arrayKeysToCamelCase($createdContactSource->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else {
            try {
                $contactSourceData = json_decode($request->getContent(), true);

                $createdContactSource = ContactSource::create([
                    'contactSourceName' => $contactSourceData['contactSourceName'],
                ]);

                $converted = arrayKeysToCamelCase($createdContactSource->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        }
    }

    // get all the contact Source data controller method
    public function getAllContactSource(Request $request): jsonResponse
    {
        try {
            $allContactSource = ContactSource::with('contact')->orderBy('id', 'desc')->get();

            $allContactSource->each(function ($item) {
                $item->contact->each(function ($x) {
                    $x->fullName = $x->firstName . " " . $x->lastName;
                });
            });

            $converted = arrayKeysToCamelCase($allContactSource->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting all the contact source. Please try again later.'], 500);
        }
    }

    // get single contact source data controller method
    public function getSingleContactSource(Request $request, $id): jsonResponse
    {
        try {
            $singleContactSource = ContactSource::with('contact')->orderBy('id', 'desc')->findOrFail($id);

            $singleContactSource->contact->each(function ($x) {
                $x->fullName = $x->firstName . " " . $x->lastName;
            });

            $converted = arrayKeysToCamelCase($singleContactSource->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting a single contact source. Please try again later.'], 500);
        }
    }

    // update a single contact source controller method
    public function updateContactSource(Request $request, $id): jsonResponse
    {
        try {
            $contactSourceData = json_decode($request->getContent(), true);
            $updatedContactSource = ContactSource::where('id', $id)->first();
            $updatedContactSource->update([
                'contactSourceName' => $contactSourceData['contactSourceName'],
            ]);

            $converted = arrayKeysToCamelCase($updatedContactSource->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a single contact source. Please try again later.'], 500);
        }
    }

    // delete a single contactSource controller method
    public function deleteContactSource(Request $request, $id): jsonResponse
    {
        try {
            $deletedContactSource = ContactSource::where('id', $id)->delete();

            if ($deletedContactSource) {
                return response()->json(['message' => "ContactSource has been deleted"], 200);
            } else {
                return response()->json(['error' => 'Failed to delete a Contact Source!'], 409);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a single contact source. Please try again later.'], 500);
        }
    }
}
