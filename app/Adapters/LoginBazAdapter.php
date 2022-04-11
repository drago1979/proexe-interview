<?php


namespace App\Adapters;


use App\Interfaces\LoginInterface;
use External\Baz\Auth\Authenticator;
use External\Baz\Auth\Responses\Success;

class LoginBazAdapter implements LoginInterface
{
    /**
     * Calls BAZ API
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function login(string $login, string $password): bool
    {
        if (app(Authenticator::class)->auth($login, $password) instanceof Success) {

            return true;
        }

        return false;
    }
}
