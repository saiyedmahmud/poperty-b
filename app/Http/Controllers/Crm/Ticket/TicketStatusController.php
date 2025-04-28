<?php

namespace App\Http\Controllers\Crm\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Exception;
use App\Models\TicketStatus;


class TicketStatusController extends Controller
{
    //create a ticketStatus controller method
    public function createTicketStatus(Request $request): jsonResponse
    {
        try {
            $ticketStatusData = json_decode($request->getContent(), true);
            $createdTicketStatus = TicketStatus::create([
                'ticketStatusName' => $ticketStatusData['ticketStatusName'],
            ]);

            $converted = arrayKeysToCamelCase($createdTicketStatus->toArray());
            return response()->json($converted, 201);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during creating Ticket Status. Please try again later.'], 500);
        }
    }

    // get all the ticketStatus controller method
    public function getAllTicketStatus(Request $request): jsonResponse
    {
        try {
            $allTicketStatus = TicketStatus::orderBy('id', 'desc')->get();

            $converted = arrayKeysToCamelCase($allTicketStatus->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting Ticket Status. Please try again later.'], 500);
        }
    }

    // get a single ticketStatus controller method
    public function getSingleTicketStatus(Request $request, $id): jsonResponse
    {
        try {
            $singleTicketStatus = TicketStatus::with('ticket.customer:id,firstName,lastName')->findOrFail($id);

            $singleTicketStatus->ticket->each(function ($x) {
                $x->customer->fullName = $x->customer->firstName . " " . $x->customer->lastName;
            });

            $converted = arrayKeysToCamelCase($singleTicketStatus->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting Ticket Status. Please try again later.'], 500);
        }
    }

    // update ticketStatus controller method
    public function updateTicketStatus(Request $request, $id): jsonResponse
    {
        try {
            $ticketStatusData = json_decode($request->getContent(), true);

            $updatedTicketStatus = TicketStatus::where('id', (int)$id)->first();
            $updatedTicketStatus->update([
                'ticketStatusName' => $ticketStatusData['ticketStatusName'],
            ]);

            $converted = arrayKeysToCamelCase($updatedTicketStatus->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating Ticket Status. Please try again later.'], 500);
        }
    }

    // delete ticketStatus controller method
    public function deleteTicketStatus(Request $request, $id): jsonResponse
    {
        try {
            $deletedTicketStatus = TicketStatus::where('id', (int)$id)->delete();

            if ($deletedTicketStatus) {
                return response()->json(['message' => 'Ticket Status Deleted Successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to delete Ticket Status!'], 404);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting Ticket Status. Please try again later.'], 500);
        }
    }
}
