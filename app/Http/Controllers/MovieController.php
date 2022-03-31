<?php

namespace App\Http\Controllers;

use App\Services\GetTitlesService;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getTitles(): JsonResponse
    {
        $titles = (new GetTitlesService())->getTitles();

        if (array_key_exists('error', $titles)) {
            return response()->json(['status' => 'failure']);
        }

        return response()->json($titles);
    }
}
