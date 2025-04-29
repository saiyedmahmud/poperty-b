<?php

namespace App\Http\Controllers\Settings\AppSetting;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\MediaFiles;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AppSettingController extends Controller
{
    //get single app setting
    public function getSingleAppSetting(): JsonResponse
    {
        try {
            $getSingleAppSetting = AppSetting::where('id', 1)->first();

            if ($getSingleAppSetting->logo) {
                $currentAppUrl = url('/');
                $getSingleAppSetting->logo = "$currentAppUrl/media/view/$getSingleAppSetting->logo";
            }

            return $this->response($getSingleAppSetting->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during getting app setting. Please try again later.'], 500);
        }
    }

    //update app setting
    public function updateAppSetting(Request $request): JsonResponse
    {
        try {

            if ($request->input('logo') !== null) {
                $media = MediaFiles::where('id', $request->input('logo'))->first();

                if ($media->fileType !== 'image/jpeg' && $media->fileType !== 'image/png' && $media->fileType !== 'image/jpg') {
                    return $this->badRequest('Logo must be an image!');
                }
            }

            $appSetting = AppSetting::where('id', 1)->first();

            $appSetting->update([
                'companyName' => $request->input('companyName') ?? $appSetting->companyName,
                'dashboardType' => $request->input('dashboardType') ?? $appSetting->dashboardType,
                'tagLine' => $request->input('tagLine') ?? $appSetting->tagLine,
                'address' => $request->input('address') ?? $appSetting->address,
                'phone' => $request->input('phone') ?? $appSetting->phone,
                'email' => $request->input('email') ?? $appSetting->email,
                'website' => $request->input('website') ?? $appSetting->website,
                'footer' => $request->input('footer') ?? $appSetting->footer,
                'logo' => $request->input('logo') ?? null,
                'bin' => $request->input('bin') ?? $appSetting->bin,
                'mushak' => $request->input('mushak') ?? $appSetting->mushak,
                'isSaleCommission' => $request->input('isSaleCommission') ?? $appSetting->isSaleCommission,
                'isPos' => $request->input('isPos') ?? $appSetting->isPos,
            ]);

            return $this->response($appSetting->toArray());
        } catch (Exception $error) {
            return $this->badRequest($error);
        }
    }
}
