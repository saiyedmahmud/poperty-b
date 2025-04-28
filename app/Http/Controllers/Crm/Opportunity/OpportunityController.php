<?php

namespace App\Http\Controllers\Crm\Opportunity;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OpportunityController extends Controller
{
    public function createOpportunity(Request $request): JsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);

                $deleteMany = Opportunity::destroy($ids);

                $deletedCounted = [
                    'count' => $deleteMany,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $data = json_decode($request->getContent(), true);
                foreach ($data as $item) {
                    Opportunity::insertOrIgnore($item);
                }
                return response()->json(["count" => count($data)], 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else {
            try {
            
                $data = $request->attributes->get('data');
        
                $opportunity = Opportunity::create([
                    'opportunityOwnerId' => $data['sub'] ?? null,
                    'contactId' => $request['contactId'] ?? null,
                    'companyId' => $request['companyId'] ?? null,
                    'opportunityName' => $request['opportunityName'],
                    'amount' => $request['amount'] ?? null,
                    'opportunitySourceId' => $request['opportunitySourceId'] ?? null,
                    'opportunityStageId' => $request['opportunityStageId'] ?? null,
                    'opportunityTypeId' => $request['opportunityTypeId'] ?? null,
                    'opportunityCreateDate' => new DateTime($request['opportunityCreateDate']) ?? null,
                    'opportunityCloseDate' => new DateTime($request['opportunityCloseDate']) ?? null,
                    'nextStep' => $request['nextStep'] ?? null,
                    'competitors' => $request['competitors'] ?? null,
                    'description' => $request['description'] ?? null,
                ]);
        
                // Convert array keys to camel case and return the response
                $converted = arrayKeysToCamelCase($opportunity->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        }
        
    }

    //get all opportunity
    public function getAllOpportunity(Request $request): JsonResponse
    {
        if (request()->query('query') === 'all') {
            try {
                $opportunity = Opportunity::with('opportunityOwner:id,firstName,lastName', 'company:id,companyName', 'contact:id,firstName,lastName', 'opportunityType:id,opportunityTypeName', 'opportunityStage:id,opportunityStageName', 'opportunitySource:id,opportunitySourceName')
                    ->where('status', 'true')
                    ->orderBy('id', 'desc')
                    ->get();

                $modifiedOpportunity = $opportunity->map(function ($opportunity) {
                    $owner = $opportunity->opportunityOwner;
                    $opportunity->opportunityOwner->fullName = $owner?->firstName . ' ' . $owner?->lastName;

                    if ($opportunity->contact !== null) {
                        $opportunity->contact->fullName = $opportunity->contact->firstName . " " . $opportunity->contact->lastName;
                    }
                    return $opportunity;
                });

                $converted = arrayKeysToCamelCase($modifiedOpportunity->toArray());
                return response()->json($converted, 200);
            } catch
            (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else if (request()->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $opportunity = Opportunity::with('opportunityOwner:id,firstName,lastName', 'company:id,companyName', 'contact:id,firstName,lastName', 'opportunityType:id,opportunityTypeName', 'opportunityStage:id,opportunityStageName', 'opportunitySource:id,opportunitySourceName')
                    ->where('opportunityName', 'LIKE',"%{$request['key']}%")
                    ->orWhere('amount', 'LIKE', "%{$request['key']}%")
                    ->orWhere('nextStep', 'LIKE', "%{$request['key']}%")
                    ->orWhere('competitors', 'LIKE', "%{$request['key']}%")
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();
                
                    $count = Opportunity::with('opportunityOwner:id,firstName,lastName', 'company:id,companyName', 'contact:id,firstName,lastName', 'opportunityType:id,opportunityTypeName', 'opportunityStage:id,opportunityStageName', 'opportunitySource:id,opportunitySourceName')
                    ->where('opportunityName', 'LIKE',"%{$request['key']}%")
                    ->orWhere('amount', 'LIKE', "%{$request['key']}%")
                    ->orWhere('nextStep', 'LIKE', "%{$request['key']}%")
                    ->orWhere('competitors', 'LIKE', "%{$request['key']}%")
                    ->count();
                $modifiedOpportunity = $opportunity->map(function ($opportunity) {
                    $owner = $opportunity->opportunityOwner;
                    $opportunity->opportunityOwner->fullName = $owner->firstName . ' ' . $owner->lastName;

                    $opportunity->contact->fullName = $opportunity->contact->firstName . " " . $opportunity->contact->lastName;

                    return $opportunity;
                });
                $converted = arrayKeysToCamelCase($modifiedOpportunity->toArray());
                $finalResult = ['getAllOpportunity' => $converted,
                    'totalOpportunity' => $count,];

                return response()->json($finalResult, 200);
            } catch
            (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else if (request()->query()) {
            try {
                $pagination = getPagination($request->query());
                $statusValues = explode(',', $request->query('status'));
                $opportunity = Opportunity::with('opportunityOwner:id,firstName,lastName', 'company:id,companyName', 'contact:id,firstName,lastName', 'opportunityType:id,opportunityTypeName', 'opportunityStage:id,opportunityStageName', 'opportunitySource:id,opportunitySourceName', 'crmEmail')
                    ->when($request->query('opportunityOwner'), function ($query) use ($request) {
                        $query->whereIn('opportunityOwnerId', explode(',', $request->query('opportunityOwner')));
                    })
                    ->when($request->query('opportunitySource'), function ($query) use ($request) {
                        $query->whereIn('opportunitySourceId', explode(',', $request->query('opportunitySource')));
                    })
                    ->when($request->query('opportunityStage'), function ($query) use ($request) {
                        $query->whereIn('opportunityStageId', explode(',', $request->query('opportunityStage')));
                    })
                    ->when($request->query('opportunityType'), function ($query) use ($request) {
                        $query->whereIn('opportunityTypeId', explode(',', $request->query('opportunityType')));
                    })
                    ->when($request->query('company'), function ($query) use ($request) {
                        $query->whereIn('companyId', explode(',', $request->query('company')));
                    })
                    ->when($request->query('contact'), function ($query) use ($request) {
                        $query->whereIn('contactId', explode(',', $request->query('contact')));
                    })
                    ->when(count($statusValues) > 1, function ($query) {
                    }, function ($query) use ($statusValues) {
                        $query->whereIn('status', $statusValues);
                    })
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();
                
                    $count = Opportunity::with('opportunityOwner:id,firstName,lastName', 'company:id,companyName', 'contact:id,firstName,lastName', 'opportunityType:id,opportunityTypeName', 'opportunityStage:id,opportunityStageName', 'opportunitySource:id,opportunitySourceName', 'crmEmail')
                    ->when($request->query('opportunityOwner'), function ($query) use ($request) {
                        $query->whereIn('opportunityOwnerId', explode(',', $request->query('opportunityOwner')));
                    })
                    ->when($request->query('opportunitySource'), function ($query) use ($request) {
                        $query->whereIn('opportunitySourceId', explode(',', $request->query('opportunitySource')));
                    })
                    ->when($request->query('opportunityStage'), function ($query) use ($request) {
                        $query->whereIn('opportunityStageId', explode(',', $request->query('opportunityStage')));
                    })
                    ->when($request->query('opportunityType'), function ($query) use ($request) {
                        $query->whereIn('opportunityTypeId', explode(',', $request->query('opportunityType')));
                    })
                    ->when($request->query('company'), function ($query) use ($request) {
                        $query->whereIn('companyId', explode(',', $request->query('company')));
                    })
                    ->when($request->query('contact'), function ($query) use ($request) {
                        $query->whereIn('contactId', explode(',', $request->query('contact')));
                    })
                    ->when(count($statusValues) > 1, function ($query) {
                    }, function ($query) use ($statusValues) {
                        $query->whereIn('status', $statusValues);
                    })
                    ->count();

                $modifiedOpportunity = $opportunity->map(function ($opportunity) {
                    $owner = $opportunity->opportunityOwner;
                    $opportunity->opportunityOwner->fullName = $owner->firstName . ' ' . $owner->lastName;

                    if ($opportunity->contact !== null) {
                        $opportunity->contact->fullName = $opportunity->contact->firstName . " " . $opportunity->contact->lastName;
                    }
                    return $opportunity;
                });

                $converted = arrayKeysToCamelCase($modifiedOpportunity->toArray());
                $finalResult = [
                    'getAllOpportunity' => $converted,
                    'totalOpportunity' => $count
                ];

                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else {
            return $this->badRequest('Invalid query');
        }
    }

    //getSingle opportunity
    public function getSingleOpportunity($id): JsonResponse
    {
        try {
            $opportunity = Opportunity::with(
                'opportunityOwner:id,firstName,lastName',
                'company.companyOwner:id,firstName,lastName',
                'contact.contactOwner:id,firstName,lastName',
                'opportunityType',
                'opportunityStage',
                'opportunitySource',
                'note.noteOwner:id,firstName,lastName',
                'quote.quoteOwner:id,firstName,lastName',
                'attachment.attachmentOwner:id,firstName,lastName',
                'attachment.company:id,companyName',
                'attachment.contact:id,firstName,lastName',
                'attachment.opportunity:id,opportunityName',
                'attachment.quote:id,quoteName',
                'tasks.taskType:id,taskTypeName',
                'tasks.Priority',
                'tasks.crmTaskStatus:id,taskStatusName',
                'tasks.assignee:id,firstName,lastName',
                'tasks.opportunity:id,opportunityName',
                'tasks.contact:id,firstName,lastName',
                'tasks.quote:id,quoteName',
                'emails',
                'emails.emailOwner:id,firstName,lastName,username'
            )->find($id);
    
            if (!$opportunity) {
                return response()->json(['error' => 'Opportunity not found.'], 404);
            }
    
            // Null-check and concatenate fullName for opportunityOwner
            if ($opportunity->opportunityOwner) {
                $opportunity->opportunityOwner->fullName =
                    $opportunity->opportunityOwner->firstName . " " .
                    $opportunity->opportunityOwner->lastName;
            }
    
            // Null-check and concatenate fullName for contact
            if ($opportunity->contact) {
                $opportunity->contact->fullName =
                    $opportunity->contact->firstName . " " .
                    $opportunity->contact->lastName;
    
                if ($opportunity->contact->contactOwner) {
                    $opportunity->contact->contactOwner->fullName =
                        $opportunity->contact->contactOwner->firstName . " " .
                        $opportunity->contact->contactOwner->lastName;
                }
            }
    
            // Null-check and concatenate fullName for companyOwner
            if ($opportunity->company && $opportunity->company->companyOwner) {
                $opportunity->company->companyOwner->fullName =
                    $opportunity->company->companyOwner->firstName . " " .
                    $opportunity->company->companyOwner->lastName;
            }
    
            // Loop through notes, quotes, and attachments with null checks
            $opportunity->note->each(function ($data) {
                if ($data->noteOwner) {
                    $data->noteOwner->fullName =
                        $data->noteOwner->firstName . " " .
                        $data->noteOwner->lastName;
                }
            });
    
            $opportunity->quote->each(function ($data) {
                if ($data->quoteOwner) {
                    $data->quoteOwner->fullName =
                        $data->quoteOwner->firstName . " " .
                        $data->quoteOwner->lastName;
                }
            });
    
            $opportunity->attachment->each(function ($data) {
                if ($data->attachmentOwner) {
                    $data->attachmentOwner->fullName =
                        $data->attachmentOwner->firstName . " " .
                        $data->attachmentOwner->lastName;
                }
            });
    
            // Convert to camel case
            $converted = arrayKeysToCamelCase($opportunity->toArray());
            return response()->json($converted);
    
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }
    

    //update opportunity
    public function updateOpportunity(Request $request, $id): JsonResponse
    {
        try {
            $opportunity = Opportunity::find($id);
            $opportunity->update($request->all());
            $converted = arrayKeysToCamelCase($opportunity->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a single Opportunity. Please try again later.'], 500);
        }
    }

    //delete opportunity
    public function deleteOpportunity(Request $request, $id): JsonResponse
    {
        try {
            $deletedOpportunity = Opportunity::where('id', $id)->update([
                'status' => $request->input('status'),
            ]);

            if ($deletedOpportunity) {
                return response()->json(['message' => 'Opportunity Deleted Successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to delete Opportunity!'], 404);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a single Opportunity. Please try again later.'], 500);
        }
    }
}
