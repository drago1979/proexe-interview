<?php

namespace App\Http\Controllers;

use App\Adapters\LoginAdapter;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent());

        $login = $content->login;
        $password = $content->password;

        // Case 1: $login or $password invalid
        if (!(new LoginAdapter($login, $password))->login()) {
            return response()->json([
                'status' => 'failure',
            ]);
        }

        // Case 2: $login or $password valid
        $token = (new TokenService())->getToken();

        return response()->json([
            'status' => 'success',
            'token' => $token
        ]);
    }
}
