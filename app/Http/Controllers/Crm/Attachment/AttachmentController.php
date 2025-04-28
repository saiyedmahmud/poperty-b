<?php

namespace App\Http\Controllers\Crm\Attachment;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use App\Models\Attachment;

class AttachmentController extends Controller
{
    //create a attachment controller method
    public function createAttachment(Request $request): JsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedAttachment = Attachment::destroy($ids);

                $deletedCounted = [
                    'count' => $deletedAttachment,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else {
            try {
                $data = $request->attributes->get('data');


                $createdAttachment = Attachment::create([
                    'attachmentOwnerId' => $data['sub'] ?? null,
                    'contactId' => $request->input('contactId') ?? null,
                    'companyId' => $request->input('companyId') ?? null,
                    'opportunityId' => $request->input('opportunityId') ?? null,
                    'quoteId' => $request->input('quoteId') ?? null,
                    'attachmentPath' => $request->input('attachmentPath'),
                    'attachmentName' => $request->input('attachmentName') ?? null,
                ]);

                $converted = arrayKeysToCamelCase($createdAttachment->toArray());
                return response()->json($converted, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        }
    }

    // get all the attachment controller method
    public function getAllAttachments(Request $request): jsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $getAllAttachment = Attachment::with('attachmentOwner:id,firstName,lastName', 'company', 'contact', 'opportunity', 'quote')->orderBy('id', 'desc')->get();

                $getAllAttachment->each(function ($item) {
                    $item->attachmentOwner->fullName = $item->attachmentOwner->firstName . " " . $item->attachmentOwner->lastName;
                });

                $converted = arrayKeysToCamelCase($getAllAttachment->toArray());
                return response()->json($converted, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else if ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $key = $request->query('key');

                $getAllAttachment = Attachment::when($key, fn($query) => $query->where('attachmentName', 'LIKE', "%$key%"))->with('attachmentOwner:id,firstName,lastName', 'company', 'contact', 'opportunity', 'quote')->orderBy('id', 'desc')->skip($pagination['skip'])->take($pagination['limit'])->get();

                $getAllAttachment->each(function ($item) {
                    $item->attachmentOwner->fullName = $item->attachmentOwner->firstName . " " . $item->attachmentOwner->lastName;
                });

                $converted = arrayKeysToCamelCase($getAllAttachment->toArray());
                $finalResult = [
                    'getAllAttachment' => $converted,
                    'totalAttachmentCount' => [
                        '_count' => [
                            'id' => count($getAllAttachment),
                        ],
                    ],
                ];
                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else if ($request->query()) {
            try {
                $pagination = getPagination($request->query());
            
                $getAllAttachment = Attachment::with('attachmentOwner:id,firstName,lastName', 'company', 'contact', 'opportunity', 'quote')
                    ->when($request->query('company'), function ($query) use ($request) {
                        $query->whereIn('companyId', explode(',', $request->query('company')));
                    })
                    ->when($request->query('companyOwner'), function ($query) use ($request) {
                        $query->whereIn('attachmentOwnerId', explode(',', $request->query('attachmentOwner')));
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
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();
            
                $converted = arrayKeysToCamelCase($getAllAttachment->toArray());
                $finalResult = [
                    'getAllAttachment' => $converted,
                    'totalAttachmentCount' => $getAllAttachment->count(),
                ];
            
                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }            
        }
        return response()->json(['error' => 'invalid query'], 400);
    }

    // get a single attachment controller method
    public function getSingleAttachment($id): JsonResponse
    {
        try {
            $getSingleAttachment = Attachment::where('id', $id)->with('attachmentOwner:id,firstName,lastName', 'company', 'contact', 'opportunity', 'quote')->first();

            $getSingleAttachment->attachmentOwner->fullName = $getSingleAttachment->attachmentOwner->firstName . " " . $getSingleAttachment->attachmentOwner->lastName;

            $converted = arrayKeysToCamelCase($getSingleAttachment->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    // delete attachment controller method
    public function deleteAttachment(Request $request, $id): JsonResponse
    {
        try {
            $getSingleAttachment = Attachment::where('id', $id)->delete();

            if ($getSingleAttachment) {
                return $this->success('Attachment Deleted Successfully!');
            } else {
                return $this->conflict('Delete Failed!');
            }
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }
}
