<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Services\EmailService;
use Exception;
use App\Models\Cc;
use App\Models\Bcc;
use App\Models\Email;
use App\Models\Attachment;
use App\Models\EmailConfig;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function sendEmail(Request $request): JsonResponse
    {
        try {
            //get the email config
            $data = $request->attributes->get('data');
            $emailConfig = EmailConfig::first();
            $file_path = $request->file_paths;
            //find the attachment storage path

            $attachmentPath = [];
            if ($file_path) {
                foreach ($file_path as $path) {
                    $attachmentPath[] = storage_path('app/uploads/' . $path);
                }
            }


            $cc = null;
            if ($request->cc) {
                if (is_array($request->cc)) {
                    $cc = array_map('trim', $request->cc);
                    $cc = array_map('trim', explode(',', $cc[0]));
                } elseif (str_contains($request->cc, ',')) {
                    $cc = array_map('trim', explode(',', $request->cc));
                } else {
                    $cc = trim($request->cc);
                }
            }
            $bcc = null;

            if ($request->bcc) {
                if (is_array($request->bcc)) {
                    $bcc = array_map('trim', $request->bcc);
                    $bcc = array_map('trim', explode(',', $bcc[0]));
                } elseif (str_contains($request->bcc, ',')) {
                    $bcc = array_map('trim', explode(',', $request->bcc));
                } else {
                    $bcc = trim($request->bcc);
                }
            }
            //create email
            $createEmail = Email::create([
                'emailOwnerId' => $data['sub'],
                'senderEmail' => $emailConfig->emailUser,
                'receiverEmail' => $request->receiverEmail,
                "contactId" => $request->input('contactId') ?? null,
                "companyId" => $request->input('companyId') ?? null,
                "opportunityId" => $request->input('opportunityId') ?? null,
                "quoteId" => $request->input('quoteId') ?? null,
                'subject' => $request->subject ?? 'No Subject',
                'body' => $request->body ?? 'No Body',
                'emailStatus' => 'sent',
                'emailType' => $request->emailType ?? 'global',
            ]);

            if ($file_path) {
                foreach ($file_path as $path) {
                    Attachment::create([
                        'emailId' => $createEmail->id,
                        'name' => $path,
                        'attachmentPath' => $path,
                    ]);
                }
            }
            // Handle CC
            if ($cc !== null) {
                if (is_array($cc)) {
                    foreach ($cc as $ccEmail) {
                        if ($ccEmail !== "") {
                            Cc::create([
                                'emailId' => $createEmail->id,
                                'ccEmail' => $ccEmail,
                            ]);
                        }
                    }
                } else {
                    Cc::create([
                        'emailId' => $createEmail->id,
                        'ccEmail' => $cc,
                    ]);
                }
            }

            // Handle BCC
            if ($bcc !== null) {
                if (is_array($bcc)) {
                    foreach ($bcc as $bccEmail) {
                        if ($bccEmail !== "") {
                            Bcc::create([
                                'emailId' => $createEmail->id,
                                'bccEmail' => $bccEmail,
                            ]);
                        }
                    }
                } else {
                    Bcc::create([
                        'emailId' => $createEmail->id,
                        'bccEmail' => $bcc,
                    ]);
                }
            }
            function updateEmailStatus($status, Email $email): void
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
                    'attachment' => $attachmentPath ?? null,

                ];

                $email = $this->emailService->sendEmail($emailConfig, $request->receiverEmail, $mailData);


                if ($email) {
                    updateEmailStatus('sent', $createEmail);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Email is sent successfully.'
                    ], 200);
                } else {
                    updateEmailStatus('failed', $createEmail);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Email is not sent!'
                    ], 500);
                }

            } else if ($cc && !$bcc) {
                //send the email
                $mailData = [
                    'title' => $request->subject,
                    'body' => $request->body,
                    'attachment' => $attachmentPath ?? null,

                ];
                $email = $this->emailService->sendEmail($emailConfig, $request->receiverEmail, $mailData, $cc);

                if ($email) {
                    updateEmailStatus('sent', $createEmail);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Email is sent successfully.'
                    ], 200);
                } else {
                    updateEmailStatus('failed', $createEmail);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Email is not sent!'
                    ], 500);
                }
            } else if (!$cc && $bcc) {
                //send the email
                $mailData = [
                    'title' => $request->subject,
                    'body' => $request->body,
                    'attachment' => $attachmentPath ?? null,
                ];
                $email = $this->emailService->sendEmail($emailConfig, $request->receiverEmail, $mailData, $bcc);

                if ($email) {
                    updateEmailStatus('sent', $createEmail);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Email is sent successfully.'
                    ], 200);
                } else {
                    updateEmailStatus('failed', $createEmail);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Email is not sent!'
                    ], 500);
                }
            } else {
                //send the email
                $mailData = [
                    'title' => $request->subject,
                    'body' => $request->body,
                    'attachment' => $attachmentPath ?? null,

                ];
                $email = $this->emailService->sendEmail($emailConfig, $request->receiverEmail, $mailData, $cc, $bcc);

                if ($email) {
                    updateEmailStatus('sent', $createEmail);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Email is sent successfully.'
                    ], 200);
                } else {
                    updateEmailStatus('failed', $createEmail);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Email is not sent!'
                    ], 500);
                }
            }
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    //get all emails
    public function getEmails(Request $request): JsonResponse
    {
        try {
            if ($request->query('query') === 'all') {
                $emails = Email::with('cc', 'bcc', 'attachment', 'company', 'contact', 'opportunity', 'quote')
                    ->where('status', 'true')
                    ->orderBy('id', 'desc')
                    ->get();
                return response()->json(arrayKeysToCamelCase($emails->toArray()));
            } elseif ($request->query('query') === 'search') {
                $pagination = getPagination($request->query());
                $emails = Email::with('cc', 'bcc', 'attachment', 'company', 'contact', 'opportunity', 'quote')
                    ->where('status', 'true')
                    ->where('receiverEmail', 'like', '%' . $request->query('key') . '%')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->orderBy('id', 'desc')
                    ->get();

                $totalEmail = count($emails);

                return response()->json([
                    'getAllEmail' => arrayKeysToCamelCase($emails->toArray()),
                    'totalEmail' => $totalEmail
                ]);
            } elseif ($request->query()) {
                $pagination = getPagination($request->query());
                $emails = Email::with('cc', 'bcc', 'attachment', 'company:id,companyName', 'contact:id,firstName,lastName', 'opportunity:id,opportunityName', 'quote:id,quoteName', 'emailOwner:id,firstName,lastName,username')
                    ->when($request->query('emailType'), function ($query) use ($request) {
                        return $query->whereIn('emailType', explode(',', $request->query('emailType')));
                    })
                    ->when($request->query('emailStatus'), function ($query) use ($request) {
                        return $query->whereIn('emailStatus', explode(',', $request->query('emailStatus')));
                    })
                    ->when($request->query('status'), function ($query) use ($request) {
                        return $query->whereIn('status', explode(',', $request->query('status')));
                    })
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->orderBy('id', 'desc')
                    ->get();

                $totalEmail = count($emails);

                return response()->json([
                    'getAllEmail' => arrayKeysToCamelCase($emails->toArray()),
                    'totalEmail' => $totalEmail
                ]);
            } else {
                return $this->badRequest('Invalid query!');
            }
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    //getSingleEmail
    public function getSingleEmail(Request $request): JsonResponse
    {
        try {
            $email = Email::with('cc', 'bcc', 'attachment')->find($request->id);
            return response()->json($email);
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    //deleteEmail
    public function deleteEmail(Request $request): JsonResponse
    {
        try {
            $email = Email::find($request->id);

            if (!$email) {
                return $this->notFound('Email not found.');
            }

            $email->delete();
            return $this->badRequest('Email deleted successfully.');
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }
}
