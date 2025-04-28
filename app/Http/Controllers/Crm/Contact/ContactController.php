<?php

namespace App\Http\Controllers\Crm\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Customer;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    //create contact
    public function createContact(Request $request): JsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);

                $deleteMany = Contact::destroy($ids);

                $deletedCount = [
                    'count' => $deleteMany,
                ];

                return response()->json($deletedCount, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } elseif ($request->query('query') === 'createmany') {
            try {
                $data = json_decode($request->getContent(), true);

                foreach ($data as $item) {
                    Contact::insertOrIgnore($item);
                }
                return response()->json(['count' => count($data)], 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else {
            try {
                $data = $request->attributes->get('data');
                $contact = Contact::create([
                    'image' => $request->input('image') ?? null,
                    'contactOwnerId' => $data['sub'],
                    'contactSourceId' => $request->input('contactSourceId') ?? null,
                    'contactStageId' => $request->input('contactStageId') ?? null,
                    'firstName' => $request->input('firstName'),
                    'lastName' => $request->input('lastName') ?? null,
                    'dateOfBirth' => $request->input('dateOfBirth') ?? null,
                    'companyId' => $request->input('companyId') ?? null,
                    'jobTitle' => $request->input('jobTitle') ?? null,
                    'department' => $request->input('department') ?? null,
                    'industryId' => $request->input('industryId') ?? null,
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone') ?? null,
                    'twitter' => $request->input('twitter') ?? null,
                    'linkedin' => $request->input('linkedin') ?? null,
                    'presentAddress' => $request->input('presentAddress') ?? null,
                    'presentCity' => $request->input('presentCity') ?? null,
                    'presentZipCode' => $request->input('presentZipCode') ?? null,
                    'presentState' => $request->input('presentState') ?? null,
                    'presentCountry' => $request->input('presentCountry') ?? null,
                    'permanentAddress' => $request->input('permanentAddress') ?? null,
                    'permanentCity' => $request->input('permanentCity') ?? null,
                    'permanentZipCode' => $request->input('permanentZipCode') ?? null,
                    'permanentState' => $request->input('permanentState') ?? null,
                    'permanentCountry' => $request->input('permanentCountry') ?? null,
                    'description' => $request->input('description') ?? null,
                ]);

                $roleId = 3;


                // Check if email exists in Customer
                if ($contact->email) {
                    $existingCustomer = Customer::where('email', $contact->email)->first();
                    if (!$existingCustomer) {
                        // Create Customer only if email does not exist
                        Customer::create([
                            'email' => $contact->email,
                            'roleId' => $roleId,
                        ]);
                    }
                }

                $converted = arrayKeysToCamelCase($contact->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        }
    }

    //getAll contact
    public function getAllContact(Request $request): JsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $allContact = Contact::with('contactOwner:id,firstName,lastName',
                 'contactSource', 
                 'contactStage', 
                 'company:id,companyName', 
                 'industry',
                 'opportunity.opportunityOwner:id,firstName,lastName')
                  ->orderBy('id', 'desc')
                  ->where('status', 'true')
                  ->get();
                $converted = arrayKeysToCamelCase($allContact->toArray());
                return response()->json($converted, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } elseif ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $contact = Contact::with('contactOwner:id,firstName,lastName', 'contactSource', 'contactStage', 'company:id,companyName', 'industry')
                    ->where('status', 'true')
                    ->where(DB::raw("CONCAT(firstName, ' ', lastName)"), 'LIKE', "%{$request['key']}%")
                    ->orWhere('firstName', 'LIKE', "%{$request['key']}%")
                    ->orWhere('lastName', 'LIKE', "%{$request['key']}%")
                    ->orWhere('email', 'LIKE', "%{$request['key']}%")
                    ->orWhere('phone', 'LIKE', "%{$request['key']}%")
                    ->orWhere('jobTitle', 'LIKE', "%{$request['key']}%")
                    ->orWhere('department', 'LIKE', "%{$request['key']}%")
                    ->orWhere('presentAddress', 'LIKE', "%{$request['key']}%")
                    ->orWhere('presentCity', 'LIKE', "%{$request['key']}%")
                    ->orWhere('presentZipCode', 'LIKE', "%{$request['key']}%")
                    ->orWhere('presentState', 'LIKE', "%{$request['key']}%")
                    ->orWhere('presentCountry', 'LIKE', "%{$request['key']}%")
                    ->orWhere('permanentAddress', 'LIKE', "%{$request['key']}%")
                    ->orWhere('permanentCity', 'LIKE', "%{$request['key']}%")
                    ->orWhere('permanentZipCode', 'LIKE', "%{$request['key']}%")
                    ->orWhere('permanentState', 'LIKE', "%{$request['key']}%")
                    ->orWhere('permanentCountry', 'LIKE', "%{$request['key']}%")
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $modifiedContact = $contact->map(function ($contact) {
                    $owner = $contact->contactOwner;
                    $contact->contactOwner->fullName = $owner->firstName . ' ' . $owner->lastName;
                    return $contact;
                });

                $count = Contact::with('contactOwner:id,firstName,lastName', 'contactSource', 'contactStage', 'company:id,companyName', 'industry')
                    ->where('status', 'true')
                    ->where(DB::raw("CONCAT(firstName, ' ', lastName)"), 'LIKE', "%{$request['key']}%")
                    ->orWhere('firstName', 'LIKE', "%{$request['key']}%")
                    ->orWhere('lastName', 'LIKE', "%{$request['key']}%")
                    ->orWhere('email', 'LIKE', "%{$request['key']}%")
                    ->orWhere('phone', 'LIKE', "%{$request['key']}%")
                    ->orWhere('jobTitle', 'LIKE', "%{$request['key']}%")
                    ->orWhere('department', 'LIKE', "%{$request['key']}%")
                    ->orWhere('presentAddress', 'LIKE', "%{$request['key']}%")
                    ->orWhere('presentCity', 'LIKE', "%{$request['key']}%")
                    ->orWhere('presentZipCode', 'LIKE', "%{$request['key']}%")
                    ->orWhere('presentState', 'LIKE', "%{$request['key']}%")
                    ->orWhere('presentCountry', 'LIKE', "%{$request['key']}%")
                    ->orWhere('permanentAddress', 'LIKE', "%{$request['key']}%")
                    ->orWhere('permanentCity', 'LIKE', "%{$request['key']}%")
                    ->orWhere('permanentZipCode', 'LIKE', "%{$request['key']}%")
                    ->orWhere('permanentState', 'LIKE', "%{$request['key']}%")
                    ->orWhere('permanentCountry', 'LIKE', "%{$request['key']}%")
                    ->count();
                $converted = arrayKeysToCamelCase($modifiedContact->toArray());
                $finalResult = [
                    'getAllContact' => $converted,
                    'totalContact' => $count,
                ];

                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        }else if($request->query('query') === 'isEmail'){
            try {
                $contact = Contact::where('email', $request->query('email'))->first();
                if ($contact) {
                    return response()->json(['status' => "true"], 200);
                }
                return response()->json(['status' => "false"], 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }

        }elseif ($request->query()) {
            try {
                $pagination = getPagination($request->query());
                $contact = Contact::with('contactOwner:id,firstName,lastName', 'contactSource', 'contactStage', 'company:id,companyName', 'industry')
                    ->when($request->query('contactOwner'), function ($query) use ($request) {
                        $query->whereIn('contactOwnerId', explode(',', $request->query('contactOwner')));
                    })
                    ->when($request->query('contactSource'), function ($query) use ($request) {
                        $query->whereIn('contactSourceId', explode(',', $request->query('contactSource')));
                    })
                    ->when($request->query('contactStage'), function ($query) use ($request) {
                        $query->whereIn('contactStageId', explode(',', $request->query('contactStage')));
                    })
                    ->when($request->query('company'), function ($query) use ($request) {
                        $query->whereIn('companyId', explode(',', $request->query('company')));
                    })
                    ->when($request->query('industry'), function ($query) use ($request) {
                        $query->whereIn('industryId', explode(',', $request->query('industry')));
                    })
                    ->when($request->query('status'), function ($query) use ($request) {
                        $query->whereIn('status', explode(',', $request->query('status')));
                    })
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                    $count = Contact::with('contactOwner:id,firstName,lastName', 'contactSource', 'contactStage', 'company:id,companyName', 'industry')
                    ->when($request->query('contactOwner'), function ($query) use ($request) {
                        $query->whereIn('contactOwnerId', explode(',', $request->query('contactOwner')));
                    })
                    ->when($request->query('contactSource'), function ($query) use ($request) {
                        $query->whereIn('contactSourceId', explode(',', $request->query('contactSource')));
                    })
                    ->when($request->query('contactStage'), function ($query) use ($request) {
                        $query->whereIn('contactStageId', explode(',', $request->query('contactStage')));
                    })
                    ->when($request->query('company'), function ($query) use ($request) {
                        $query->whereIn('companyId', explode(',', $request->query('company')));
                    })
                    ->when($request->query('industry'), function ($query) use ($request) {
                        $query->whereIn('industryId', explode(',', $request->query('industry')));
                    })
                    ->when($request->query('status'), function ($query) use ($request) {
                        $query->whereIn('status', explode(',', $request->query('status')));
                    })
                    ->count();

                $modifiedContact = $contact->map(function ($contact) {
                    $owner = $contact->contactOwner;
                    $contact->contactOwner->fullName = $owner->firstName . ' ' . $owner->lastName;
                    return $contact;
                });




                $converted = arrayKeysToCamelCase($modifiedContact->toArray());
                $finalResult = [
                    'getAllContact' => $converted,
                    'totalContact' => $count,
                ];

                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else {
            return $this->badRequest('Invalid query');
        }
    }

    //getSingle contact
    public function getSingleContact(Request $request, $id): JsonResponse
    {
        try {
            $singleContact = Contact::with(['contactOwner:id,firstName,lastName', 
            'contactSource',
             'contactStage',
              'company:id,companyName', 
              'industry', 
              'note.noteOwner:id,firstName,lastName', 
              'quote.quoteOwner:id,firstName,lastName',
               'attachment.attachmentOwner:id,firstName,lastName', 
               'attachment.company:id,companyName', 
               'attachment.contact:id,firstName,lastName', 
               'attachment.quote:id,quoteName', 
               'attachment.opportunity:id,opportunityName', 
               'Tasks.taskType', 
               'Tasks.Priority', 
               'Tasks.crmTaskStatus', 
               'Tasks.assignee:id,firstName,lastName',
                'emails', 
                'emails.emailOwner:id,firstName,lastName,username', 
                'opportunity.opportunityOwner:id,firstName,lastName',
                ])
                ->orderBy('id', 'desc')
                ->findOrFail($id);

            $customer = Customer::with('ticket.ticketStatus', 'ticket.ticketCategory',  'ticket.priority' )
            ->where('email', $singleContact->email)->first();

            //concat first name and last name
            $singleContact->fullName = $singleContact->firstName . ' ' . $singleContact->lastName;

            $singleContact->contactOwner->fullName = $singleContact->contactOwner->firstName . ' ' . $singleContact->contactOwner->lastName;

            //oppurtunity owner full name
            $singleContact->opportunity->map(function ($opportunity) {
                $opportunity->opportunityOwner->fullName = $opportunity->opportunityOwner->firstName . ' ' . $opportunity->opportunityOwner->lastName;
                return $opportunity;
            });

            //oppurtunity owner full name

            //note owner full name
            $singleContact->note->map(function ($note) {
                $note->noteOwner->fullName = $note->noteOwner->firstName . ' ' . $note->noteOwner->lastName;
                return $note;
            });

            //quote owner full name
            $singleContact->quote->map(function ($quote) {
                $quote->quoteOwner->fullName = $quote->quoteOwner->firstName . ' ' . $quote->quoteOwner->lastName;
                return $quote;
            });

            //task assignee full name
            $singleContact->Tasks->map(function ($task) {
                $task->assignee->fullName = $task->assignee->firstName . ' ' . $task->assignee->lastName;
                return $task;
            });

            //attachment owner full name
            $singleContact->attachment->map(function ($attachment) {
                $attachment->attachmentOwner->fullName = $attachment->attachmentOwner->firstName . ' ' . $attachment->attachmentOwner->lastName;
                return $attachment;
            });

            //merge customer ticket with single contact 
            $singleContact->ticket = ($customer->ticket)->toArray();
          
            $converted = arrayKeysToCamelCase($singleContact->toArray());
            // dd($converted);
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    //update contact
    public function updateContact(Request $request, $id): JsonResponse
    {
        try {
            if ($request->input('dateOfBirth')) {
                $dateOfBirth = new DateTime($request->input('dateOfBirth'));
                $formattedDateOfBirth = $dateOfBirth->format('Y-m-d');
                $request->merge([
                    'dateOfBirth' => $formattedDateOfBirth,
                ]);
            }

            $contact = Contact::findOrFail($id);
            $contact->Update($request->all());

            $converted = arrayKeysToCamelCase($contact->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return $this->badRequest($err);
        }
    }

    //delete contact
    public function deleteContact(Request $request, $id): JsonResponse
    {
        try {
            $deletedContact = Contact::where('id', $id)->update([
                'status' => $request->input('status'),
            ]);

            if (!$deletedContact) {
                return $this->conflict('Failed to delete contact!');
            }
            return $this->success('Contact deleted successfully');
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }
}
