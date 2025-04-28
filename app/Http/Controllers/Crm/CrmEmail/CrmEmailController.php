<?php

namespace App\Http\Controllers\Crm\CrmEmail;

use App\Http\Controllers\Controller;
use App\Mail\Sendmail;
use App\Models\Bcc;
use App\Models\Cc;
use App\Models\CrmEmail;
use App\Models\EmailConfig;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CrmEmailController extends Controller
{
    //create email
    public function createCrmEmail(Request $request): JsonResponse
    {

        if ($request->query('query') === "deletemany") {
            try {
                $data = json_decode($request->getContent(), true);
                $deleteMany = CrmEmail::destroy($data);

                return response()->json(["count" => $deleteMany], 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during deleting many crm email. Please try again later.'], 500);
            }
        } else {
            try {
                //get the email config
                $emailConfig = EmailConfig::first();

                //set the config
                config([
                    'mail.mailers.smtp.host' => $emailConfig->emailHost,
                    'mail.mailers.smtp.port' => $emailConfig->emailPort,
                    'mail.mailers.smtp.encryption' => $emailConfig->emailEncryption,
                    'mail.mailers.smtp.username' => $emailConfig->emailUser,
                    'mail.mailers.smtp.password' => $emailConfig->emailPass,
                    'mail.mailers.smtp.local_domain' => env('MAIL_EHLO_DOMAIN'),
                    'mail.from.address' => $emailConfig->emailUser,
                    'mail.from.name' => $emailConfig->emailConfigName,
                ]);

                $cc = $request->cc;
                $bcc = $request->bcc;

                //create crmEmail
                $createEmail = CrmEmail::create([
                    "emailOwnerId" => $request->input('emailOwnerId'),
                    "contactId" => $request->input('contactId'),
                    "companyId" => $request->input('companyId'),
                    "opportunityId" => $request->input('opportunityId'),
                    "quoteId" => $request->input('quoteId'),
                    "senderEmail" => $emailConfig->emailUser,
                    "receiverEmail" => $request->input('receiverEmail'),
                    "subject" => $request->input('subject'),
                    "body" => $request->input('body'),
                    "emailStatus" => 'sent',
                ]);

                //create cc
                if ($cc) {
                    foreach ($cc as $ccEmail) {
                        Cc::create([
                            'crmEmailId' => $createEmail->id,
                            'ccEmail' => $ccEmail,
                        ]);
                    }
                }

                //create bcc
                if ($bcc) {
                    foreach ($bcc as $bccEmail) {
                        Bcc::create([
                            'crmEmailId' => $createEmail->id,
                            'bccEmail' => $bccEmail,
                        ]);
                    }
                }

                function updateEmailStatus($status, CrmEmail $email): void
                {
                    $email->update([
                        'emailStatus' => $status,
                    ]);
                }


                if (!$cc && !$bcc) {
                    //send the email
                    $mailData = [
                        'title' => $request->subject,
                        'body' => $request->body,
                        'name' => "",
                        "email" => "",
                        "companyName" => "",
                        "password" => "",
                    ];
                    $email = Mail::to($request->receiverEmail)->send(new Sendmail($mailData));

                    if ($email) {
                        updateEmailStatus('sent', $createEmail);
                    } else {
                        updateEmailStatus('failed', $createEmail);
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Email is sent successfully.'
                    ], 200);
                } else if ($cc && !$bcc) {
                    //send the email
                    $mailData = [
                        'title' => $request->subject,
                        'body' => $request->body,
                        "name" => "",
                        "companyName" => "",
                        "email" => "",
                        "password" => "",
                    ];
                    $email = Mail::to($request->receiverEmail)->cc($cc)->send(new Sendmail($mailData));

                    if ($email) {
                        updateEmailStatus('sent', $createEmail);
                    } else {
                        updateEmailStatus('failed', $createEmail);
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Email is sent successfully.'
                    ], 200);
                } else if (!$cc && $bcc) {
                    //send the email
                    $mailData = [
                        'title' => $request->subject,
                        'body' => $request->body,
                        'name' => "",
                        "email" => "",
                        "companyName" => "",
                        "password" => "",
                    ];
                    $email = Mail::to($request->receiverEmail)->bcc($bcc)->send(new Sendmail($mailData));

                    if ($email) {
                        updateEmailStatus('sent', $createEmail);
                    } else {
                        updateEmailStatus('failed', $createEmail);
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Email is sent successfully.'
                    ], 200);
                } else {
                    //send the email
                    $mailData = [
                        'title' => $request->subject,
                        "name" => "",
                        'body' => $request->body,
                        "companyName" => "",
                        "email" => "",
                        "password" => "",
                    ];
                    $email = Mail::to($request->receiverEmail)->cc($cc)->bcc($bcc)->send(new Sendmail($mailData));

                    if ($email) {
                        updateEmailStatus('sent', $createEmail);
                    } else {
                        updateEmailStatus('failed', $createEmail);
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Email is sent successfully.'
                    ], 200);
                }
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating a crm email. Please try again later.'], 500);
            }
        }
    }

    //get all crmEmails
    public function getAllCrmEmails(Request $request): JsonResponse
    {
        if ($request->query('query') === "all") {
            try {
                $crmEmails = CrmEmail::with('emailOwner:id,firstName,lastName', 'contact', 'company', 'opportunity', 'quote', 'bcc', 'cc')
                    ->where('status', 'true')
                    ->orderBy('id', 'desc')
                    ->get();

                $converted = arrayKeysToCamelCase($crmEmails->toArray());
                return response()->json($converted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting all crm email. Please try again later.'], 500);
            }
        } else if ($request->query()) {
            try {
                $pagination = getPagination($request->query());
                $statusValues = explode(',', $request->query('status'));
                $aggregations = CrmEmail::query()
                    ->selectRaw('COUNT(id) as totalCount')
                    ->where('status', "true")
                    ->first();

                $crmEmails = CrmEmail::with('emailOwner:id,firstName,lastName', 'contact', 'company', 'opportunity', 'quote', 'bcc', 'cc')
                    ->when($request->query('crmEmailOwner'), function ($query) use ($request) {
                        $query->whereIn('crmEmailOwnerId', explode(',', $request->query('crmEmailOwner')));
                    })
                    ->when($request->query('company'), function ($query) use ($request) {
                        $query->whereIn('companyId', explode(',', $request->query('company')));
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
                    ->when(count($statusValues) > 1, function ($query) {
                    }, function ($query) use ($statusValues) {
                        $query->whereIn('status', $statusValues);
                    })
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $converted = arrayKeysToCamelCase($crmEmails->toArray());
                $response = [
                    "getAllCrmEmail" => $converted,
                    "totalCrmEmailCount" => [
                        "_count" => [
                            "id" => $aggregations->totalCount ?? 0,
                        ]
                    ]
                ];

                return response()->json($response, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting all crm email. Please try again later.'], 500);
            }
        } else {
            return response()->json(['error' => 'Invalid Query!'], 400);
        }
    }

    //getSingleCrmEmail
    public function getSingleCrmEmail($id): JsonResponse
    {
        try {
            $crmEmail = CrmEmail::with('emailOwner:id,firstName,lastName', 'contact', 'company', 'opportunity', 'quote', 'bcc', 'cc')->find($id);

            $converted = arrayKeysToCamelCase($crmEmail->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting a single crm email. Please try again later.'], 500);
        }
    }

    //deleteCrmEmail
    public function deleteCrmEmail($id): JsonResponse
    {
        try {
            $crmEmail = CrmEmail::find($id);
            $crmEmail->delete();

            return response()->json([
                'message' => 'Crm Email deleted successfully.'
            ], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a single crm email. Please try again later.'], 500);
        }
    }
}
