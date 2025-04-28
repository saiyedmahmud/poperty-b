<?php

namespace App\Http\Controllers\Crm\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Exception;
use App\Models\TicketCategory;


class TicketCategoryController extends Controller
{
    //create a ticketCategory controller method
    public function createTicketCategory(Request $request): jsonResponse
    {
        try {
            $ticketCategoryData = json_decode($request->getContent(), true);
            $createdTicketCategory = TicketCategory::create([
                'ticketCategoryName' => $ticketCategoryData['ticketCategoryName'],
            ]);

            $converted = arrayKeysToCamelCase($createdTicketCategory->toArray());
            return response()->json($converted, 201);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during creating a ticket category. Please try again later.'], 500);
        }
    }

    // get all the ticketCategory controller method
    public function getAllTicketCategory(Request $request): jsonResponse
    {
        try {
            $allTicketCategory = TicketCategory::orderBy('id', 'desc')->get();

            $converted = arrayKeysToCamelCase($allTicketCategory->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting all the ticket category. Please try again later.'], 500);
        }
    }

    // get a single ticketCategory controller method
    public function getSingleTicketCategory(Request $request, $id): jsonResponse
    {
        try {
            $singleTicketCategory = TicketCategory::with('ticket.customer:id,firstName,lastName')->findOrFail($id);

            $singleTicketCategory->ticket->each(function ($item) {
                $item->customer->fullName = $item->customer->firstName . " " . $item->customer->lastName;
            });

            $converted = arrayKeysToCamelCase($singleTicketCategory->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting a ticket category. Please try again later.'], 500);
        }
    }

    // update ticketCategory controller method
    public function updateTicketCategory(Request $request, $id): jsonResponse
    {
        try {
            $ticketCategoryData = json_decode($request->getContent(), true);

            $updatedTicketCategory = TicketCategory::where('id', (int)$id)->first();
            $updatedTicketCategory->update([
                'ticketCategoryName' => $ticketCategoryData['ticketCategoryName'],
            ]);

            $converted = arrayKeysToCamelCase($updatedTicketCategory->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a ticket category. Please try again later.'], 500);
        }
    }

    // delete ticketCategory controller method
    public function deleteTicketCategory(Request $request, $id): jsonResponse
    {
        try {
            $deletedTicketCategory = TicketCategory::where('id', (int)$id)->delete();

            if ($deletedTicketCategory) {
                return response()->json(['message' => 'Ticket Category Deleted Successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to delete a ticket Category!'], 404);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a Ticket Category. Please try again later.'], 500);
        }
    }
}
