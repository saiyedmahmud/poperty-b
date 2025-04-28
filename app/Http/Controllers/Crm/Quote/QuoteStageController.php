<?php

namespace App\Http\Controllers\Crm\Quote;

use App\Http\Controllers\Controller;
use App\Models\QuoteStage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\Exception;

class QuoteStageController extends Controller
{
    //create quote stage
    public function createQuoteStage(Request $request): JsonResponse
    {
        //delete many
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedMany = QuoteStage::destroy($ids);
                return response()->json([
                    'count' => $deletedMany
                ], 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during deleting many Quote Stage. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $data = json_decode($request->getContent(), true);
                foreach ($data as $item) {
                    QuoteStage::insertOrIgnore($item);
                }
                return response()->json(["count" => count($data)], 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating many Quote Stage. Please try again later.'], 500);
            }
        } else {
            try {
                $createdQuoteStage = QuoteStage::create([
                    'quoteStageName' => $request->input('quoteStageName'),
                ]);

                $converted = arrayKeysToCamelCase($createdQuoteStage->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating a single Quote Stage. Please try again later.'], 500);
            }
        }
    }

    //get all quote stage
    public function getAllQuoteStage(Request $request): JsonResponse
    {
        try {
            $quoteStage = QuoteStage::with('quote')->orderBy('id', 'desc')->get();
            $converted = arrayKeysToCamelCase($quoteStage->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting all Quote Stage. Please try again later.'], 500);
        }
    }

    //get single quote stage
    public function getSingleQuoteStage(Request $request, $id): JsonResponse
    {
        try {
            $quoteStage = QuoteStage::with('quote')->find($id);
            $converted = arrayKeysToCamelCase($quoteStage->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting a single Quote Stage. Please try again later.'], 500);
        }
    }

    //update quote stage
    public function updateQuoteStage(Request $request, $id): JsonResponse
    {
        try {
            $quoteStage = QuoteStage::findOrFail($id);
            $quoteStage->update($request->all());
            $converted = arrayKeysToCamelCase($quoteStage->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a single Quote Stage. Please try again later.'], 500);
        }
    }

    //delete quote stage
    public function deleteQuoteStage(Request $request, $id): JsonResponse
    {
        try {
            $deletedQuoteStage = QuoteStage::findOrFail($id);
            $deletedQuoteStage->delete();
            return response()->json([
                'message' => 'Quote Stage Deleted Successfully',
            ], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a single Quote Stage. Please try again later.'], 500);
        }
    }
}
