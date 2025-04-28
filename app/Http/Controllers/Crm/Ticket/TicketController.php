<?php

namespace App\Http\Controllers\Crm\Ticket;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use App\Models\Ticket;
use App\Models\Images;


class TicketController extends Controller
{
    //create a ticket controller method;
    public function createTicket(Request $request): jsonResponse
    {
        try {
            // generate random number
            $ticketId = mt_rand(100000, 999999);

            // get all the ticketId from db;
            $allTicketId = Ticket::where('ticketId', $ticketId)->get();

            if (count($allTicketId) > 0) {
                $ticketId = mt_rand(100000, 999999);
            }


            $createdTicket = Ticket::create([
                'ticketId' => (int)$ticketId ?? null,
                'customerId' => $request->input('customerId') ?? null,
                'email' => $request->input('email') ?? null,
                'subject' => $request->input('subject'),
                'description' => $request->input('description') ?? null,
                'ticketResolveTime' => $request->input('ticketResolveTime') ?? null,
                'ticketCategoryId' => $request->input('ticketCategoryId') ?? null,
                'priorityId' => $request->input('priorityId') ?? null,
                'ticketStatusId' => 1,
            ]);

            // insert uploaded imageName;
            $file_paths = $request->file_paths;
            if ($createdTicket) {
                foreach ($file_paths as $image) {
                    Images::create([
                        'ticketId' => $createdTicket->ticketId,
                        'imageName' => $image,
                    ]);
                }
            }

            $converted = arrayKeysToCamelCase($createdTicket->toArray());
            return response()->json($converted, 201);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during creating a Ticket. Please try again later.'], 500);
        }
    }

    // get all the ticket controller method;
    public function getAllTicket(Request $request): jsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $pagination = getPagination($request->query());

                $getAllTicket = Ticket::with('customer:id,firstName,lastName, username', 'ticketCategory', 'priority', 'ticketStatus', 'images:id,ticketId,imageName')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                // $getAllTicket->each(function ($ticket) {
                //     $ticket->customer->fullName = $ticket->customer->firstName . " " . $ticket->customer->lastName;
                // });

                $converted = arrayKeysToCamelCase($getAllTicket->toArray());

                return response()->json($converted, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else if ($request->query('query') === 'search') {
            // search by ticket Id
            try {
                if ($request->query('key') === 'undefined') {
                    return response()->json(['error' => 'please provide a ticket Id'], 400);
                }

                $getAllTicket = Ticket::where('ticketId', (int)$request->query('key'))->with('customer:id,firstName,lastName', 'ticketCategory', 'priority', 'ticketStatus', 'images:id,ticketId,imageName')
                ->orderBy('id', 'desc')->get();

                // $getAllTicket->each(function ($ticket) {
                //     return $ticket->customer->fullName = $ticket->customer->firstName . " " . $ticket->customer->lastName;
                // });
                $converted = arrayKeysToCamelCase($getAllTicket->toArray());
                $finalResult = [
                    'getAllTicket' => $converted,
                    'totalTicket' => count($converted),
                ];

                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else if ($request->query()) {
            // search by ticketStatusId;
            if ($request->query('ticketStatus') === 'undefined') {
                return response()->json(['error' => 'please provide a ticket status'], 400);
            }
            try {
                $pagination = getPagination($request->query());
                if ($request->query('ticketStatus')) {
                    $ticketStatusArray = explode(",", $request->query('ticketStatus'));
                    $getAllTicket = Ticket::whereIn('ticketStatusId', $ticketStatusArray)
                        ->with('customer:id,firstName,lastName', 'ticketCategory', 'priority', 'ticketStatus', 'images:id,ticketId,imageName')
                        ->orderBy('id', 'desc')
                        ->skip($pagination['skip'])
                        ->take($pagination['limit'])
                        ->get();
                    $count = Ticket::whereIn('ticketStatusId', $ticketStatusArray)->count();

                } else {
                    $getAllTicket = Ticket::with('customer:id,firstName,lastName', 'ticketCategory', 'priority', 'ticketStatus', 'images:id,ticketId,imageName')
                        ->orderBy('id', 'desc')
                        ->skip($pagination['skip'])
                        ->take($pagination['limit'])
                        ->get();
                    $count = Ticket::count();
                }

                // $getAllTicket->each(function ($ticket) {
                //     $ticket->customer->fullName = $ticket->customer->firstName . " " . $ticket->customer->lastName;
                // });

                $converted = arrayKeysToCamelCase($getAllTicket->toArray());
                $finalResult = [
                    'getAllTicket' => $converted,
                    'totalTicket' => $count,
                ];
                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        }
        return response()->json(['error' => 'Invalid query!'], 400);
    }

    // get a single ticket controller method
    public function getSingleTicket(Request $request, $ticketId): jsonResponse
    {
        try {
            //TODO: add images relationship to the ticket model
            // 'ticketComment.images',

            $singleTicket = Ticket::where('ticketId', (int)$ticketId)
                ->with('customer:id,firstName,lastName', 'ticketCategory', 'priority', 'ticketStatus', 'images:id,ticketId,imageName')
                ->first();

            $singleTicket->customer->fullName = $singleTicket->customer->firstName . " " . $singleTicket->customer->lastName;

            //SHOW images
            $singleTicket->images->each(function ($image) {
                $currentUrl = url('/');
                $image->imageUrl = $currentUrl .'/show-ticket-image/'. $image->imageName;
            });

            $converted = arrayKeysToCamelCase($singleTicket->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    // get ticket by customerId controller method
    public function getAllTicketByCustomerId(Request $request): jsonResponse
    {
        // customer validation with json token;
        $data = $request->attributes->get("data");
        $customerId = $data['sub'];
        if (($data['sub'] !== null && $data['role'] !== 'customer') && $data['role'] !== 'super-admin') {
            return $this->forbidden("You are not allowed to access this resource");
        }

        if ($request->query('query') === 'search') {
            // search by ticketId and customerId
            //TODO: add images relationship to the ticket model
            try {
                $pagination = getPagination($request->query());
                $getAllTicket = Ticket::where('customerId', (int)$customerId)
                    ->where('ticketId', (int)$request->query('key'))
                    ->with('customer:id,firstName,lastName', 'ticketCategory', 'priority', 'ticketStatus', 'images:id,ticketId,imageName')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->where('status', $request->query('status'))
                    ->first();

                $totalTicket = Ticket::where('customerId', (int)$customerId)
                    ->where('ticketId', (int)$request->query('key'))
                    ->with('customer:id,firstName,lastName', 'ticketCategory', 'priority', 'ticketStatus', 'images:id,ticketId,imageName')
                    ->orderBy('id', 'desc')
                    ->where('status', $request->query('status'))
                    ->count();

                $getAllTicket->customer->fullName = $getAllTicket->customer->firstName . " " . $getAllTicket->customer->lastName;

                $converted = arrayKeysToCamelCase($getAllTicket->toArray());

                $finalResult = [
                    'getAllTicket' => [$converted],
                    'totalTicket' => $totalTicket,
                ];

                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else if ($request->query('ticketStatus')) {
            // search by ticketStatusId and customerId;
            try {
                $ticketStatusArray = explode(",", $request->query('ticketStatus'));
                $pagination = getPagination($request->query());

                $getAllTicket = Ticket::where('customerId', $customerId)->whereIn('ticketStatusId', $ticketStatusArray)->with('customer:id,firstName,lastName', 'images', 'ticketCategory', 'priority', 'ticketStatus', 'images:id,ticketId,imageName')
                ->orderBy('id', 'desc')
                ->skip($pagination['skip'])
                ->take($pagination['limit'])
                ->get();

                $getAllTicket->each(function ($ticket) {
                    $ticket->customer->fullName = $ticket->customer->firstName . " " . $ticket->customer->lastName;
                });
                $converted = arrayKeysToCamelCase($getAllTicket->toArray());
                $finalResult = [
                    'getAllTicket' => $converted,
                    'totalTicketCount' => [
                        '_count' => [
                            'id' => count($converted),
                        ],
                    ],
                ];

                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting all Ticket. Please try again later.'], 500);
            }
        } else if ($request->query()) {
            $pagination = getPagination($request->query());

            $getAllTicket = Ticket::where('customerId', $customerId)
                ->with('customer:id,firstName,lastName', 'ticketCategory', 'priority', 'ticketStatus', 'images:id,ticketId,imageName')
                ->orderBy('id', 'desc')
                ->where('status', $request->query('status'))
                ->skip($pagination['skip'])
                ->take($pagination['limit'])
                ->get();

            $getAllTicket->each(function ($ticket) {
                return $ticket->customer->fullName = $ticket->customer->firstName . " " . $ticket->customer->lastName;
            });

            $converted = arrayKeysToCamelCase($getAllTicket->toArray());
            $finalResult = [
                'getAllTicket' => $converted,
                'totalTicket' => count($converted),
            ];

            return response()->json($finalResult, 200);
        } else {
            return response()->json(['error' => 'Invalid query!'], 400);
        }
    }

    // update ticket controller method
    public function updateTicket(Request $request, $ticketId): jsonResponse
    {
        try {
            // get the ticket
            $ticket = Ticket::where('ticketId', $ticketId)->first();

            if (!$ticket) return $this->notFound("Ticket not found");

            $newDate = new DateTime();
            $ticketDate = new DateTime($ticket->created_at);

            $timeDiff = $newDate->diff($ticketDate);
            $timeDiffInMinutes = $timeDiff->days * 24 * 60 + $timeDiff->h * 60 + $timeDiff->i;

            $updatedTicket = Ticket::where('ticketId', $ticketId)->first();
            $updatedTicket->update([
                'ticketStatusId' => $request->input('ticketStatusId'),
                'ticketResolveTime' => (string)$timeDiffInMinutes,
            ]);

            $converted = arrayKeysToCamelCase($updatedTicket->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {

            return $this->badRequest($err->getMessage());
        }
    }

    // delete a ticket controller method
    public function deleteTicket(Request $request, $id): jsonResponse
    {
        try {
            $deletedTicket = Ticket::where('id', $id)->delete();

            if ($deletedTicket) {
                return response()->json(['message' => 'Ticket Deleted Successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to delete Ticket!'], 404);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting Ticket. Please try again later.'], 500);
        }
    }
}
