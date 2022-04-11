<?php

namespace App\Adapters;

use App\Interfaces\LoginInterface;

class SelectLoginAdapter implements LoginInterface
{

    public function login(string $login, string $password): bool
    {
        if (preg_match("/^BAR_.*/", $login)) {
            return (new LoginBarAdapter())->login($login, $password);
        };

        if (preg_match("/^BAZ_.*/", $login)) {
            return (new LoginBazAdapter())->login($login, $password);
        };

        if (preg_match("/^FOO_.*/", $login)) {
            return (new LoginFooAdapter())->login($login, $password);
        }

        return false;
    }
}

