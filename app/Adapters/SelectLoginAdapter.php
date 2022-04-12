<?php

namespace App\Adapters;

use App\Interfaces\LoginInterface;

class SelectLoginAdapter implements LoginInterface
{

    public function login(string $login, string $password): bool
    {
        if (preg_match("/^BAR_.*/", $login)) {
            return app(LoginBarAdapter::class)->login($login, $password);
        };

        if (preg_match("/^BAZ_.*/", $login)) {
            return app(LoginBazAdapter::class)->login($login, $password);
        };

        if (preg_match("/^FOO_.*/", $login)) {
            return app(LoginFooAdapter::class)->login($login, $password);
        }

        return false;
    }
}

