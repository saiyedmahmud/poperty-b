<?php

namespace App\Http\Controllers\Crm\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Images;
use App\Models\TicketComment;
use App\Models\Users;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
    // create a ticketComment controller method
    public function createTicketComment(Request $request): JsonResponse
    {
        $data = $request->attributes->get('data');
        if (strtolower($data['role']) === 'customer') {
            try {
                $getTheCustomer = Customer::where('id', $data['sub'])->first();

                $createdTicketComment = TicketComment::create([
                    'ticketId' => $request->input('ticketId'),
                    'repliedBy' => $getTheCustomer->username,
                    'userType' => $data['role'],
                    'description' => $request->input('description'),
                ]);

                // insert uploaded imageName;
                $file_paths = $request->file_paths;
                if ($createdTicketComment) {
                    foreach ($file_paths as $image) {
                        Images::create([
                            'ticketId' => $createdTicketComment->ticketId,
                            'ticketCommentId' => $createdTicketComment->id,
                            'imageName' => $image,
                        ]);
                    }
                }

                $converted = arrayKeysToCamelCase($createdTicketComment->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else if (strtolower($data['role']) === 'super-admin') {
            try {
                $getTheUser = Users::where('id', $data['sub'])->first();

                $createdTicketComment = TicketComment::create([
                    'ticketId' => $request->input('ticketId'),
                    'repliedBy' => $getTheUser->username,
                    'userType' => $data['role'],
                    'description' => $request->input('description'),
                ]);

                // insert uploaded imageName;
                $file_paths = $request->file_paths;
                if ($createdTicketComment) {
                    foreach ($file_paths as $image) {
                        Images::create([
                            'ticketCommentId' => $createdTicketComment->id,
                            'imageName' => $image,
                        ]);
                    }
                }

                $converted = arrayKeysToCamelCase($createdTicketComment->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        }
        return $this->badRequest('Only Super Admin and Ticket owner(Customer) can create ticket comment');
    }

    // get all the ticketComment controller method
    public function getAllTicketComment(Request $request): JsonResponse
    {
        try {
            $allTicketComment = TicketComment::with('ticket')
                ->orderBy('id', 'desc')
                ->get();

            $converted = arrayKeysToCamelCase($allTicketComment->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    // get all ticketComment by ticketId
    public function getAllTicketCommentByTicketId(Request $request, $ticketId): JsonResponse
    {
        try {
            $ticketCommentByTicketId = TicketComment::
            where('ticketId', $ticketId)
                ->with('images:id,ticketCommentId,imageName')
                ->orderBy('id', 'desc')
                ->orderBy('id', 'desc')
                ->get();

            //show images
            foreach ($ticketCommentByTicketId as $ticketComment) {
                $currentUrl = url('/');
                $ticketComment->images->map(function ($image) use ($currentUrl) {
                    $image->imageUrl = $currentUrl . '/show-ticket-comment-image/' . $image->imageName;
                    return $image;
                });
            }
            $converted = arrayKeysToCamelCase($ticketCommentByTicketId->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }
}
