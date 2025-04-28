<?php

namespace App\Http\Controllers\Translation;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslationController extends Controller
{
    public function translate(Request $request): JsonResponse
    {
        $text = $request->input('text');
        $targetLang = $request->input('lang', 'bn');

        try {
            $translatedText = (new GoogleTranslate)->setTarget($targetLang)->translate($text);
            return response()->json(['translated' => $translatedText]);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }
}
