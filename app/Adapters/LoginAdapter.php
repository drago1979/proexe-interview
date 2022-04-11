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
    public function __construct($login, $password)
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
     * Calls BAR API
     * @return bool
     */
    protected function loginServiceLogin(): bool
    {
        if (app(LoginService::class)->login($this->login, $this->password)) {
            return true;
        }

        return false;
    }

    /**
     * Calls BAZ API
     * @return bool
     */
    protected function authenticatorLogin(): bool
    {
        if (app(Authenticator::class)->auth($this->login, $this->password) instanceof Success) {

            return true;
        }

        return false;
    }

    /**
     * Calls FOO API
     * @return bool
     */
    protected function authWsLogin(): bool
    {
        try {
            app(AuthWS::class)->authenticate($this->login, $this->password);

            return true;
        } catch (AuthenticationFailedException $e) {
            return false;
        }
    }
}
