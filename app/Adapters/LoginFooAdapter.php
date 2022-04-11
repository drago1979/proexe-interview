<?php


namespace App\Adapters;


use App\Interfaces\LoginInterface;
use External\Foo\Auth\AuthWS;
use External\Foo\Exceptions\AuthenticationFailedException;

class LoginFooAdapter implements LoginInterface
{
    /**
     * Calls FOO API
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function login(string $login, string $password): bool
    {
        try {
            app(AuthWS::class)->authenticate($login, $password);

            return true;
        } catch (AuthenticationFailedException $e) {
            return false;
        }
    }
}
