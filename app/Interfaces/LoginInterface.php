<?php


namespace App\Interfaces;


interface LoginInterface
{
    public function login(string $login, string $password):bool;
}
