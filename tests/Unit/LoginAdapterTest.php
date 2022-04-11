<?php

namespace Tests\Unit;

use App\Adapters\LoginAdapter;
use External\Bar\Auth\LoginService;
use External\Baz\Auth\Authenticator;
use External\Baz\Auth\Responses\Failure;
use External\Baz\Auth\Responses\Success;
use External\Foo\Auth\AuthWS;
use External\Foo\Exceptions\AuthenticationFailedException;
use phpDocumentor\Reflection\Types\Void_;
use Tests\TestCase;
use Mockery\MockInterface;

class LoginAdapterTest extends TestCase
{
    /**
     * No authentication API is called.
     * LoginAdapter returns "false".
     * @test
     * @return void
     */
    public function inadequate_login_parameters_wrong_login()
    {
        $login = 'yyy'; // Wrong login
        $password = 'foo-bar-baz'; // Good password

        $loginAdapter = new LoginAdapter($login, $password);

        $this->assertFalse($loginAdapter->login());
    }

    /**
     * BAR login API is called when $login = "BAR_...".
     * @test
     * @return void
     */
    public function bar_login_service_is_called()
    {
        $login = 'BAR_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(LoginAdapter::class, [$login, $password])
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $mock->shouldReceive('loginServiceLogin')->once();

        $mock->login();
    }

    /**
     * BAZ login API is called when $login = "BAZ_...".
     * @test
     * @return void
     */
    public function baz_login_service_is_called()
    {
        $login = 'BAZ_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(LoginAdapter::class, [$login, $password])
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $mock->shouldReceive('authenticatorLogin')->once();

        $mock->login();
    }

    /**
     * FOO login API is called when $login = "FOO_...".
     * @test
     * @return void
     */
    public function foo_login_service_is_called()
    {
        $login = 'FOO_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(LoginAdapter::class, [$login, $password])
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $mock->shouldReceive('authWsLogin')->once();

        $mock->login();
    }


    /**
     * Confirm that adapter adequately converts BAR APIs "not authenticated"
     * return.
     *
     * @test
     * @return void
     */
    public function bar_login_service_returns_unauthenticated()
    {
        $login = 'BAR_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(LoginService::class);
        $mock->makePartial()
            ->shouldReceive('login')
            ->andReturn(false);

        app()->instance(LoginService::class, $mock);

        $return = (new LoginAdapter($login, $password))->login();

        $this->assertFalse($return);
    }

    /**
     * Confirm that adapter adequately converts BAR APIs "is authenticated"
     * return.
     *
     * @test
     * @return void
     */
    public function bar_login_service_returns_authenticated()
    {
        $login = 'BAR_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(LoginService::class);
        $mock->makePartial()
            ->shouldReceive('login')
            ->andReturn(true);

        app()->instance(LoginService::class, $mock);

        $return = (new LoginAdapter($login, $password))->login();

        $this->assertTrue($return);
    }

    /**
     * Confirm that adapter adequately converts BAZ APIs "not authenticated"
     * return.
     *
     * @test
     * @return void
     */
    public function baz_login_service_returns_unauthenticated()
    {
        $login = 'BAZ_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(Authenticator::class);
        $mock->makePartial()
            ->shouldReceive('auth')
            ->andReturn(new Failure());

        app()->instance(Authenticator::class, $mock);

        $return = (new LoginAdapter($login, $password))->login();

        $this->assertFalse($return);
    }

    /**
     * Confirm that adapter adequately converts BAZ APIs "is authenticated"
     * return.
     *
     * @test
     * @return void
     */
    public function baz_login_service_returns_authenticated()
    {
        $login = 'BAZ_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(Authenticator::class);
        $mock->makePartial()
            ->shouldReceive('auth')
            ->andReturn(new Success());

        app()->instance(Authenticator::class, $mock);


        $return = (new LoginAdapter($login, $password))->login();

        $this->assertTrue($return);
    }

    /**
     * Confirm that adapter adequately converts FOO APIs "not authenticated"
     * return.
     *
     * @test
     * @return void
     */
    public function foo_login_service_returns_unauthenticated()
    {
        $login = 'FOO_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(AuthWS::class);
        $mock->makePartial()
            ->shouldReceive('authenticate')
            ->andThrow(new AuthenticationFailedException());

        app()->instance(AuthWS::class, $mock);

        $return = (new LoginAdapter($login, $password))->login();

        $this->assertFalse($return);
    }

    /**
     * Confirm that adapter adequately converts FOO APIs "is authenticated"
     * return.
     *
     * @test
     * @return void
     */
    public function foo_login_service_returns_authenticated()
    {
        $login = 'FOO_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(AuthWS::class);
        $mock->makePartial()
            ->shouldReceive('authenticate')
            ->andReturn(null);

        app()->instance(AuthWS::class, $mock);

        $return = (new LoginAdapter($login, $password))->login();

        $this->assertTrue($return);
    }
}
