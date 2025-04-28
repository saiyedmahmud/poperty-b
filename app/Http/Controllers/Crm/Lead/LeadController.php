<?php

namespace App\Http\Controllers\Crm\Lead;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Users;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    //create lead controller method
    public function createLead(Request $request): JsonResponse
    {
        if($request->query('query') === 'deletemany'){
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedLead = Lead::destroy($ids);

                $deletedCounted = [
                    'count' => $deletedLead,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else if($request->query('query') === 'createmany'){
            try {
                $data = json_decode($request->getContent(), true);

                if (!$data || !is_array($data)) {
                    return response()->json([
                        'error' => 'Invalid JSON format or empty data.'
                    ], 400);
                }
                
                $leadsToInsert = [];
                $errors = [];
                foreach ($data as $index => $row) {
                    if (!isset($row['leadOwner'])) {
                        continue; 
                    }
                
                    $user = DB::table('users')->where('username', $row['leadOwner'])->first();
                    if ($user) {
                        $leadSource = DB::table('leadSource')->where('name', $row['leadSource'])->first();
                        $row['leadSourceId'] = $leadSource ? $leadSource->id : null;
                
                        // Check if email already exists
                        if (!empty($row['email']) && DB::table('lead')->where('email', $row['email'])->exists()) {
                            $errors[] = [
                            'error' => "row " .$index+1 . " " . $row['email']."  already exists",
                            ];
                            continue; 
                        }
                
                        $leadsToInsert[] = [
                            'name' => $row['name'] ?? null,
                            'email' => $row['email'] ?? null,
                            'phone' => $row['phone'] ?? null,
                            'leadOwnerId' => $user->id,
                            'leadStatus' => $row['leadStatus'] ?? 'new',
                            'leadSourceId' => $row['leadSourceId'] ?? null,
                            'status' => $row['status'] ?? 'true',
                            'leadValue' => $row['leadValue'] ?? null,    
                        ];
                    }
                }
                
                if (!empty($leadsToInsert)) {
                    DB::table('lead')->insert($leadsToInsert);
                }
                
                return response()->json([
                    'count' => count($leadsToInsert),
                    'errors' => $errors,
                ],201);
                
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else {
            try {
                $leadData = json_decode($request->getContent(), true);

                $createdLead = Lead::create([
                    'name' => $leadData['name'],
                    'email' => $leadData['email'] ?? null,
                    'phone' => $leadData['phone'] ?? null,
                    'leadOwnerId' => $leadData['leadOwnerId'],
                    'leadSourceId' => $leadData['leadSourceId'] ?? null,
                    'leadStatus' => $leadData['leadStatus'] ?? 'new',
                    'leadValue' => $leadData['leadValue'] ?? null,
                ]);

                $converted = arrayKeysToCamelCase($createdLead->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        }
    }

    //get all lead controller method

    public function getAllLead(Request $request): JsonResponse
    {
            if($request->query('query') === 'all'){
                try {
                    $allLead = Lead::with('leadOwner:id,firstName,lastName', 'leadSource')
                    ->orderBy('id', 'desc')
                    ->get();


                    $converted = arrayKeysToCamelCase($allLead->toArray());
                    return response()->json($converted, 200);
                } catch (Exception $err) {
                    return response()->json(['error' => 'An error occurred during getting all leads'], 500);
                }
        }else if($request->query('query') === 'search'){
            try {
                $pagination = getPagination($request->query());
                $lead = Lead::with('leadOwner:id,firstName,lastName', 'leadSource:id,name')
                ->where('name', 'LIKE', "%{$request['key']}%")
                ->orWhere('email', 'LIKE', "%{$request['key']}%")
                ->orWhere('phone', 'LIKE', "%{$request['key']}%")
                ->orWhere('leadValue', 'LIKE', "%{$request['key']}%")
                ->orderBy('id', 'desc')
                ->skip($pagination['skip'])
                ->take($pagination['limit'])
                ->get();

                $count = Lead::with('leadOwner:id,firstName,lastName', 'leadSource:id,name')
                ->where('name', 'LIKE', "%{$request['key']}%")
                ->orWhere('email', 'LIKE', "%{$request['key']}%")
                ->orWhere('phone', 'LIKE', "%{$request['key']}%")
                ->orWhere('leadValue', 'LIKE', "%{$request['key']}%")
                ->count();
            
                $modified = $lead->map(function ($lead) {
                    $owner = $lead->leadOwner;
                    $lead->leadOwner = $owner->firstName . ' ' . $owner->lastName;
                    return $lead;
                });

                $converted = arrayKeysToCamelCase($modified->toArray());
                $finalResult = [
                    'getAllLead' => $converted,
                    'totalLead' => $count
                ];
                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during searching for leads'], 500);
            }
        } else if($request->query()){
            try {
                $pagination = getPagination($request->query());
                $lead = Lead::with('leadOwner:id,firstName,lastName', 'leadSource:id,name')
                ->when($request->query('leadOwnerId'), function ($query) use ($request) {
                    $query->whereIn('leadOwnerId', explode(',', $request->query('leadOwnerId')));
                })
                ->when($request->query('leadSourceId'), function ($query) use ($request) {
                    $query->whereIn('leadSourceId', explode(',', $request->query('leadSourceId')));
                })
                ->when($request->query('status'), function ($query) use ($request) {
                    $query->whereIn('status', explode(',', $request->query('status')));
                })
                ->orderBy('id', 'desc')
                ->skip($pagination['skip'])
                ->take($pagination['limit'])
                ->get();

                $count = Lead::with('leadOwner:id,firstName,lastName', 'leadSource:id,name')
                ->when($request->query('leadOwnerId'), function ($query) use ($request) {
                    $query->whereIn('leadOwnerId', explode(',', $request->query('leadOwnerId')));
                })
                ->when($request->query('leadSourceId'), function ($query) use ($request) {
                    $query->whereIn('leadSourceId', explode(',', $request->query('leadSourceId')));
                })
                ->when($request->query('status'), function ($query) use ($request) {
                    $query->whereIn('status', explode(',', $request->query('status')));
                })
                ->count();
                
                $modified = $lead->map(function ($lead) {
                    $owner = $lead->leadOwner;
                    $lead->leadOwner = $owner->firstName . ' ' . $owner->lastName;
                    return $lead;
                });

                $converted = arrayKeysToCamelCase($modified->toArray());
                $finalResult = [
                    'getAllLead' => $converted,
                    'totalLead' => $count
                ];
                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting all leads'], 500);
            }
        }else{
            return $this->badRequest('Invalid query parameter');
        }

    }

    //get single lead controller method
    public function getSingleLead($id): JsonResponse
    {
        try {
            $singleLead = Lead::with('leadOwner' , 'leadSource')
            ->findOrFail($id);
           
            $singleLead->leadOwner->fullName = $singleLead->leadOwner->firstName . ' ' . $singleLead->leadOwner->lastName;  


            $converted = arrayKeysToCamelCase($singleLead->toArray());
            
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting single lead'], 500);
        }

    }
    
    //update lead controller method
    public function updateLead(Request $request, $id): JsonResponse
    {
        try {
            $leadData = json_decode($request->getContent(), true);

           $lead = Lead::findOrFail($id);
           $lead->Update($request->all());

            $converted = arrayKeysToCamelCase($lead->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    //delete lead controller method

    public function deleteLead(Request $request, $id): JsonResponse
    {
        try {
            $deletedLead = Lead::where('id', $id)->first();

            if(!$deletedLead){
                return response()->json(['error' => 'Lead not found'], 404);
            }

            if($request->query('query') === 'changeContactStatus'){
                
                $deletedLead->isConverted = $request->query('isConverted');
                $deletedLead->save();

                $data =  $request->attributes->get('data');
                if($request->query('isConverted') === 'true'){
                 
                    $contact = Contact::create([
                    'image' => $request->input('image') ?? null,
                    'contactOwnerId' => $data['sub'],
                    'contactSourceId' => $request->input('contactSourceId')?? null,
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
                    if(!$contact){
                        return response()->json(['error' => 'An error occurred during converting lead to contact'], 500);
                    }
                    return response()->json(['message' => 'Lead converted to contact successfully'], 200);
                }else{
                    return response()->json(['message' => 'Lead status changed successfully'], 200);
                } 
            }

            $deletedLead = Lead::where('id', $id)->update([
                'status' => $request->input('status')
            ]);
            if($deletedLead){
                return response()->json(['message' => 'Lead deleted successfully'], 200);
            }else{
                return response()->json(['error' => 'An error occurred during deleting lead'], 500);
            }
            
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting lead'], 500);
        }

    }
}