<?php

namespace App\Http\Controllers;

use App\Adapters\LoginAdapter;
use App\Interfaces\LoginInterface;
use App\Services\SelectLoginAdapterService;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @param LoginInterface $loginInterface
     * @return JsonResponse
     */
    public function login(Request $request, LoginInterface $loginInterface): JsonResponse
    {
        $content = json_decode($request->getContent());

        $login = $content->login;
        $password = $content->password;

        // Case 1: $login or $password invalid
        if (! $loginInterface->login($login, $password)) {
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
