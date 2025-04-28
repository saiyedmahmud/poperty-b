<?php

namespace App\Http\Controllers\Settings\QuickLink;

use App\Http\Controllers\Controller;
use App\Models\QuickLinks;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuickLinkController extends Controller
{
    //get all quick links
    public function getAllQuickLinks(Request $request): JsonResponse
    {
        try {
            if ($request->query('query') === "all") {
                $quickLinks = QuickLinks::orderBy('id', 'desc')->get();
                return $this->response($quickLinks);
            } else {
                return $this->notFound('No quick links found');
            }
        } catch (Exception $e) {
            return $this->badRequest($e);
        }
    }

    //get all position
    public function getAllPosition(Request $request): JsonResponse
    {
        try {
            $quickLinks = QuickLinks::orderBy('position', 'asc')->get();

           // position is null filter out
            $quickLinks = $quickLinks->filter(function ($quickLink) {
                return $quickLink->position !== null;
            })->values();

            return $this->response($quickLinks);
        } catch (Exception $e) {
            return $this->badRequest($e);
        }
    }

    //update quick link
    public function updateQuickLink(Request $request): JsonResponse
    {
        try {
            $ids = json_decode($request->getContent(), true);

            QuickLinks::where('id', '!=', $ids)->update(['position' => null]);
            foreach ($ids as $index => $id) {
                $quickLink = QuickLinks::find($id);
                if (!$quickLink) return $this->notFound('Quick link not found');
                $quickLink->position = $index + 1;
                $quickLink->save();
            }
            return $this->success('Quick link updated successfully');
        } catch (Exception $e) {
            return $this->badRequest($e);
        }
    }
}
