<?php

namespace App\Http\Controllers\Crm\Company;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    //create company
    public function createCompany(Request $request): JsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $data = json_decode($request->getContent(), true);
                $deleteMany = Company::destroy($data);
                return response()->json(['count' => $deleteMany], 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } elseif ($request->query('query') === 'createmany') {
            try {
                $data = json_decode($request->getContent(), true);

                foreach ($data as $item) {
                    Company::insertOrIgnore($item);
                }

                return response()->json(['count' => count($data)], 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else {
            try {
                $data = $request->attributes->get('data');
                $createdCompany = Company::create([
                    'companyOwnerId' => $data['sub'] ?? null,
                    'companyName' => $request->input('companyName'),
                    'image' => $request->input('image') ?? null,
                    'industryId' => $request->input('industryId') ?? null,
                    'companyTypeId' => $request->input('companyTypeId') ?? null,
                    'companySize' => $request->input('companySize') ?? null,
                    'annualRevenue' => $request->input('annualRevenue') ?? null,
                    'website' => $request->input('website') ?? null,
                    'phone' => $request->input('phone') ?? null,
                    'email' => $request->input('email') ?? null,
                    'linkedin' => $request->input('linkedin') ?? null,
                    'facebook' => $request->input('facebook') ?? null,
                    'twitter' => $request->input('twitter') ?? null,
                    'instagram' => $request->input('instagram') ?? null,
                    'billingStreet' => $request->input('billingStreet') ?? null,
                    'billingCity' => $request->input('billingCity') ?? null,
                    'billingState' => $request->input('billingState') ?? null,
                    'billingZipCode' => $request->input('billingZipCode') ?? null,
                    'billingCountry' => $request->input('billingCountry') ?? null,
                    'shippingStreet' => $request->input('shippingStreet') ?? null,
                    'shippingCity' => $request->input('shippingCity') ?? null,
                    'shippingState' => $request->input('shippingState') ?? null,
                    'shippingZipCode' => $request->input('shippingZipCode') ?? null,
                    'shippingCountry' => $request->input('shippingCountry') ?? null,
                ]);

                $converted = arrayKeysToCamelCase($createdCompany->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        }
    }

    //get all companies
    public function getAllCompanies(Request $request): JsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $companies = Company::with('companyOwner:id,firstName,lastName', 'industry', 'companyType')->orderBy('id', 'desc')->where('status', 'true')->get();

                $modifiedCompanies = $companies->map(function ($company) {
                    $owner = $company->companyOwner;
                    $company->companyOwner->fullName = $owner->firstName . ' ' . $owner->lastName;
                    return $company;
                });

                $converted = arrayKeysToCamelCase($modifiedCompanies->toArray());
                return response()->json($converted, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } elseif ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $companies = Company::with('companyOwner:id,firstName,lastName', 'industry', 'companyType')
                ->where('status', 'true')
                ->where('companyName', 'LIKE', "%{$request['key']}%")
                ->orWhere('companySize', 'LIKE', "%{$request['key']}%")
                ->orWhere('annualRevenue', 'LIKE', "%{$request['key']}%")
                ->orWhere('website', 'LIKE', "%{$request['key']}%")
                ->orWhere('phone', 'LIKE', "%{$request['key']}%")
                ->orWhere('email', 'LIKE', "%{$request['key']}%")
                ->orWhere('linkedin', 'LIKE', "%{$request['key']}%")
                ->orWhere('facebook', 'LIKE', "%{$request['key']}%")
                ->orWhere('twitter', 'LIKE', "%{$request['key']}%")
                ->orWhere('instagram', 'LIKE', "%{$request['key']}%")
                ->orWhere('billingStreet', 'LIKE', "%{$request['key']}%")
                ->orWhere('billingCity', 'LIKE', "%{$request['key']}%")
                ->orWhere('billingState', 'LIKE', "%{$request['key']}%")
                ->orWhere('billingZipCode', 'LIKE', "%{$request['key']}%")
                ->orWhere('billingCountry', 'LIKE', "%{$request['key']}%")
                ->orWhere('shippingStreet', 'LIKE', "%{$request['key']}%")
                ->orWhere('shippingCity', 'LIKE', "%{$request['key']}%")
                ->orWhere('shippingState', 'LIKE', "%{$request['key']}%")
                ->orWhere('shippingZipCode', 'LIKE', "%{$request['key']}%")
                ->orWhere('shippingCountry', 'LIKE', "%{$request['key']}%")
                ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                    $count = Company::with('companyOwner:id,firstName,lastName', 'industry', 'companyType')
                    ->where('status', 'true')
                    ->where('companyName', 'LIKE', "%{$request['key']}%")
                    ->orWhere('companySize', 'LIKE', "%{$request['key']}%")
                    ->orWhere('annualRevenue', 'LIKE', "%{$request['key']}%")
                    ->orWhere('website', 'LIKE', "%{$request['key']}%")
                    ->orWhere('phone', 'LIKE', "%{$request['key']}%")
                    ->orWhere('email', 'LIKE', "%{$request['key']}%")
                    ->orWhere('linkedin', 'LIKE', "%{$request['key']}%")
                    ->orWhere('facebook', 'LIKE', "%{$request['key']}%")
                    ->orWhere('twitter', 'LIKE', "%{$request['key']}%")
                    ->orWhere('instagram', 'LIKE', "%{$request['key']}%")
                    ->orWhere('billingStreet', 'LIKE', "%{$request['key']}%")
                    ->orWhere('billingCity', 'LIKE', "%{$request['key']}%")
                    ->orWhere('billingState', 'LIKE', "%{$request['key']}%")
                    ->orWhere('billingZipCode', 'LIKE', "%{$request['key']}%")
                    ->orWhere('billingCountry', 'LIKE', "%{$request['key']}%")
                    ->orWhere('shippingStreet', 'LIKE', "%{$request['key']}%")
                    ->orWhere('shippingCity', 'LIKE', "%{$request['key']}%")
                    ->orWhere('shippingState', 'LIKE', "%{$request['key']}%")
                    ->orWhere('shippingZipCode', 'LIKE', "%{$request['key']}%")
                    ->orWhere('shippingCountry', 'LIKE', "%{$request['key']}%")
                    ->count();
                
                $modifiedCompanies = $companies->map(function ($company) {
                    $owner = $company->companyOwner;
                    $company->companyOwner->fullName = $owner->firstName . ' ' . $owner->lastName;
                    return $company;
                });

                $converted = arrayKeysToCamelCase($modifiedCompanies->toArray());
                $finalResult = [
                    'getAllCompany' => $converted,
                    'totalCompany' => $count
                ];
                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } elseif ($request->query()) {
            try {
                $pagination = getPagination($request->query());
                $statusValues = explode(',', $request->query('status'));
                $companies = Company::with('companyOwner:id,firstName,lastName', 'industry', 'companyType')
                    ->when($request->query('companyOwner'), function ($query) use ($request) {
                        $query->whereIn('companyOwnerId', explode(',', $request->query('companyOwner')));
                    })
                    ->when($request->query('industry'), function ($query) use ($request) {
                        $query->whereIn('industryId', explode(',', $request->query('industry')));
                    })
                    ->when($request->query('companyType'), function ($query) use ($request) {
                        $query->whereIn('companyTypeId', explode(',', $request->query('companyType')));
                    })
                    ->when(
                        count($statusValues) > 1,
                        function ($query) {},
                        function ($query) use ($statusValues) {
                            $query->whereIn('status', $statusValues);
                        },
                    )
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                    $count = Company::with('companyOwner:id,firstName,lastName', 'industry', 'companyType')
                    ->when($request->query('companyOwner'), function ($query) use ($request) {
                        $query->whereIn('companyOwnerId', explode(',', $request->query('companyOwner')));
                    })
                    ->when($request->query('industry'), function ($query) use ($request) {
                        $query->whereIn('industryId', explode(',', $request->query('industry')));
                    })
                    ->when($request->query('companyType'), function ($query) use ($request) {
                        $query->whereIn('companyTypeId', explode(',', $request->query('companyType')));
                    })
                    ->when(
                        count($statusValues) > 1,
                        function ($query) {},
                        function ($query) use ($statusValues) {
                            $query->whereIn('status', $statusValues);
                        },
                    )
                    ->count();
                $modifiedCompanies = $companies->map(function ($company) {
                    $owner = $company->companyOwner;
                    $company->companyOwner->fullName = $owner->firstName . ' ' . $owner->lastName;
                    return $company;
                });
                $converted = arrayKeysToCamelCase($modifiedCompanies->toArray());
                $finalResult = [
                    'getAllCompany' => $converted,
                    'totalCompany' => $count
                ];
                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else {
            return $this->notFound('invalid query');
        }
    }

    public function getSingleCompany(Request $request, $id): JsonResponse
    {
        try {
            $singleCompany = Company::with('companyOwner:id,firstName,lastName', 'industry', 'companyType', 'contact.contactOwner:id,firstName,lastName', 'opportunity.opportunityOwner:id,firstName,lastName', 'opportunity.opportunityStage', 'opportunity.opportunityType', 'opportunity.opportunitySource', 'note.noteOwner:id,firstName,lastName', 'quote.quoteOwner:id,firstName,lastName', 'attachment.attachmentOwner:id,firstName,lastName', 'attachment.company:id,companyName', 'attachment.contact:id,firstName,lastName', 'attachment.quote:id,quoteName', 'attachment.opportunity:id,opportunityName', 'tasks.taskType', 'tasks.Priority', 'tasks.crmTaskStatus', 'emails', 'tasks.assignee:id,firstName,lastName', 'emails.emailOwner:id,firstName,lastName,username')->findOrFail($id);

            $owner = $singleCompany->companyOwner;
            $singleCompany->companyOwner->fullName = $owner->firstName . ' ' . $owner->lastName;

            $singleCompany->contact->map(function ($contact) {
                $owner = $contact->contactOwner;
                $contact->contactOwner->fullName = $owner->firstName . ' ' . $owner->lastName;
                return $contact;
            });

            $singleCompany->opportunity->map(function ($opportunity) {
                $owner = $opportunity->opportunityOwner;
                $opportunity->opportunityOwner->fullName = $owner->firstName . ' ' . $owner->lastName;
                return $opportunity;
            });

            $singleCompany->note->map(function ($note) {
                $owner = $note->noteOwner;
                $note->noteOwner->fullName = $owner->firstName . ' ' . $owner->lastName;
                return $note;
            });

            $singleCompany->quote->map(function ($quote) {
                $owner = $quote->quoteOwner;
                $quote->quoteOwner->fullName = $owner->firstName . ' ' . $owner->lastName;
                return $quote;
            });

            $singleCompany->attachment->map(function ($attachment) {
                $owner = $attachment->attachmentOwner;
                $attachment->attachmentOwner->fullName = $owner->firstName . ' ' . $owner->lastName;
                return $attachment;
            });

            $converted = arrayKeysToCamelCase($singleCompany->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    // Update company
    public function updateCompany(Request $request, $id): JsonResponse
    {
        try {
            // Find the company by ID or throw a 404 error if It is not found
            $company = Company::findOrFail($id);

            // Update the company with the request data
            $company->update($request->all());

            // Convert the company's attributes to camelCase if needed
            $converted = arrayKeysToCamelCase($company->toArray());

            // Return the updated company data in camelCases
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a single company. Please try again later.'], 500);
        }
    }

    public function deleteCompany(Request $request, $id): JsonResponse
    {
        try {
            $deletedCompany = Company::where('id', $id)->update([
                'status' => $request->input('status'),
            ]);

            if (!$deletedCompany) {
                return response()->json(['error' => 'Failed to delete the company'], 409);
            }

            return response()->json(['message' => 'company deleted successfully'], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a single company. Please try again later.'], 500);
        }
    }
}
