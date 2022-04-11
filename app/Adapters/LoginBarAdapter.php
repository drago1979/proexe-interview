<?php


namespace App\Adapters;


use App\Interfaces\LoginInterface;
use External\Bar\Auth\LoginService;

class LoginBarAdapter implements LoginInterface
{

    /**
     * Calls BAR API
     * @param $login
     * @param $password
     * @return bool
     */
    public function login(string $login, string $password): bool
    {
        if (app(LoginService::class)->login($login, $password)) {
            return true;
        }

        return false;
    }
}
