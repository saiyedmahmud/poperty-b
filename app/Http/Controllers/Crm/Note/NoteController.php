<?php

namespace App\Http\Controllers\Crm\Note;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NoteController extends Controller
{
    //create a note controller method
    public function createNote(Request $request): jsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedNote = Note::destroy($ids);

                $deletedCounted = [
                    'count' => $deletedNote,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during deleting many note. Please try again later.'], 500);
            }
        } else {
            try {
                $createdNote = Note::create([
                    'noteOwnerId' => $request->input('noteOwnerId'),
                    'contactId' => $request->input('contactId') ?? null,
                    'companyId' => $request->input('companyId') ?? null,
                    'opportunityId' => $request->input('opportunityId') ?? null,
                    'quoteId' => $request->input('quoteId') ?? null,
                    'title' => $request->input('title'),
                    'description' => $request->input('description') ?? null,
                    'status'=> $request->input('status') ?? 'true',
                ]);

                $converted = arrayKeysToCamelCase($createdNote->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        }
    }

    // get all note controller method
    public function getAllNotes(Request $request): jsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $getAllNote = Note::with('noteOwner:id,firstName,lastName', 'contact', 'company', 'opportunity', 'quote')->orderBy('id', 'desc')->get();

                $getAllNote->each(function ($note) {
                    $note->noteOwner->fullName = $note->noteOwner->firstName . " " . $note->noteOwner->lastName;
                });

                $converted = arrayKeysToCamelCase($getAllNote->toArray());
                return response()->json($converted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting all note. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());

                $getAllNote = Note::where('title', 'LIKE', '%' . $request->query('key') . '%')
                    ->with('noteOwner:id,firstName,lastName', 'contact', 'company', 'opportunity', 'quote')->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();
                
                    $count = Note::where('title', 'LIKE', '%' . $request->query('key') . '%')
                    ->count();

                $getAllNote->each(function ($note) {
                    $note->noteOwner->fullName = $note->noteOwner->firstName . " " . $note->noteOwner->lastName;
                });
                $converted = arrayKeysToCamelCase($getAllNote->toArray());
                $finalResult = [
                    'getAllNote' => $converted,
                    'totalNote' => $count
                ];

                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else if ($request->query()) {
            try {
                $pagination = getPagination($request->query());

                $getAllNote = Note::with('noteOwner:id,firstName,lastName', 'contact:id,firstName,lastName', 'company:id,companyName', 'opportunity', 'quote')
                    ->when($request->query('company'), function ($query) use ($request) {
                        $query->whereIn('companyId', explode(',', $request->query('company')));
                    })
                    ->when($request->query('noteOwner'), function ($query) use ($request) {
                        $query->whereIn('noteOwnerId', explode(',', $request->query('noteOwner')));
                    })
                    ->when($request->query('contact'), function ($query) use ($request) {
                        $query->whereIn('contactId', explode(',', $request->query('contact')));
                    })
                    ->when($request->query('opportunity'), function ($query) use ($request) {
                        $query->whereIn('opportunityId', explode(',', $request->query('opportunity')));
                    })
                    ->when($request->query('quote'), function ($query) use ($request) {
                        $query->whereIn('quoteId', explode(',', $request->query('quote')));
                    })
                    ->when($request->query('status'), function ($query) use ($request) {
                        $query->where('status', explode(',', $request->query('status')));
                    })
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();
                
                    $count = Note::with('noteOwner:id,firstName,lastName', 'contact:id,firstName,lastName', 'company:id,companyName', 'opportunity', 'quote')
                    ->when($request->query('company'), function ($query) use ($request) {
                        $query->whereIn('companyId', explode(',', $request->query('company')));
                    })
                    ->when($request->query('noteOwner'), function ($query) use ($request) {
                        $query->whereIn('noteOwnerId', explode(',', $request->query('noteOwner')));
                    })
                    ->when($request->query('contact'), function ($query) use ($request) {
                        $query->whereIn('contactId', explode(',', $request->query('contact')));
                    })
                    ->when($request->query('opportunity'), function ($query) use ($request) {
                        $query->whereIn('opportunityId', explode(',', $request->query('opportunity')));
                    })
                    ->when($request->query('quote'), function ($query) use ($request) {
                        $query->whereIn('quoteId', explode(',', $request->query('quote')));
                    })
                    ->when($request->query('status'), function ($query) use ($request) {
                        $query->where('status', explode(',', $request->query('status')));
                    })
                    ->count();

                $getAllNote->each(function ($note) {
                    $note->noteOwner->fullName = $note->noteOwner->firstName . " " . $note->noteOwner->lastName;
                });

                $converted = arrayKeysToCamelCase($getAllNote->toArray());
                $finalResult = [
                    'getAllNote' => $converted,
                    'totalNote' => $count
                ];

                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else {
            return response()->json(['error' => 'Invalid Query!'], 400);
        }
    }

    // get a single note controller method
    public function getSingleNote(Request $request, $id): JsonResponse
    {
        try {
            $singleNote = Note::where('id', $id)
                ->with('noteOwner:id,firstName,lastName', 'contact', 'company', 'opportunity', 'quote')
                ->first();

            if (!$singleNote) return $this->notFound('Note not found!');

            $singleNote->noteOwner->fullName = $singleNote->noteOwner->firstName . " " . $singleNote->noteOwner->lastName;

            $converted = arrayKeysToCamelCase($singleNote->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    // update a note controller method
    public function updateNote(Request $request, $id): JsonResponse
    {
        try {
            $updatedNote = Note::where('id', $id)->first();
            $updatedNote->update($request->all());
            $updatedNote->save();

            $converted = arrayKeysToCamelCase($updatedNote->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    // update status false note controller method
    public function deleteNote(Request $request, $id): JsonResponse
    {
        try {
            $deletedNote = Note::where('id', $id)->update(['status' => $request->input('status')]);

            if($deletedNote) {
                return response()->json(['message' => 'Note Hided successfully.'], 200);
            } else {
                return response()->json(['error' => 'Failed to delete note.'], 404);
            }

        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }
}