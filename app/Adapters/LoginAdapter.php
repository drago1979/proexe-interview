<?php


namespace App\Adapters;


use External\Bar\Auth\LoginService;
use External\Baz\Auth\Authenticator;
use External\Baz\Auth\Responses\Success;
use External\Foo\Auth\AuthWS;
use External\Foo\Exceptions\AuthenticationFailedException;

class LoginAdapter
{
    private $login;
    private $password;

    /**
     * LoginAdapter constructor.
     * @param $login
     * @param null $password
     */
    public function __construct($login, $password = null)
    {
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function login(): bool
    {
        if (preg_match("/^BAR_.*/", $this->login)) {
            return $this->loginServiceLogin();
        };

        if (preg_match("/^BAZ_.*/", $this->login)) {
            return $this->authenticatorLogin();
        };

        if (preg_match("/^FOO_.*/", $this->login)) {
            return $this->authWsLogin();
        }

        return false;
    }

    /**
     * @return bool
     */
    private function loginServiceLogin(): bool
    {
        if ((new LoginService())->login($this->login, $this->password)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    private function authenticatorLogin(): bool
    {
        if ((new Authenticator())->auth($this->login, $this->password) instanceof Success) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    private function authWsLogin(): bool
    {
        try {
            (new AuthWS())->authenticate($this->login, $this->password);

            return true;
        } catch (AuthenticationFailedException $e) {
            return false;
        }
    }
}
