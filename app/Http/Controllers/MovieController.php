<?php

namespace App\Http\Controllers;


use App\Services\GetTitlesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getTitles(Request $request): JsonResponse
    {
        $titles = (new GetTitlesService())->getTitles();

        return response()->json($titles);
    }
}
