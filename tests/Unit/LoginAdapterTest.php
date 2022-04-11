<?php

namespace Tests\Unit;

use App\Adapters\SelectLoginAdapter;
use External\Bar\Auth\LoginService;
use External\Baz\Auth\Authenticator;
use External\Baz\Auth\Responses\Failure;
use External\Baz\Auth\Responses\Success;
use External\Foo\Auth\AuthWS;
use External\Foo\Exceptions\AuthenticationFailedException;
use Tests\TestCase;

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

        $loginAdapter = new SelectLoginAdapter();

        $this->assertFalse($loginAdapter->login($login, $password));
    }

    /**
     * Mocked BAR login API is called when $login = "BAR_...".
     * @test
     * @return void
     */
    public function bar_login_mocked_api_is_called()
    {
        $login = 'BAR_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(SelectLoginAdapter::class);
        $mock->shouldReceive('login')->once();

        $mock->login($login, $password);
    }

    /**
     * Mocked BAZ login API is called when $login = "BAZ_...".
     * @test
     * @return void
     */
    public function baz_login_mocked_api_is_called()
    {
        $login = 'BAZ_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(SelectLoginAdapter::class);

        $mock->shouldReceive('login')->once();

        $mock->login($login, $password);
    }

    /**
     * Mocked FOO login API is called when $login = "FOO_...".
     * @test
     * @return void
     */
    public function foo_login_mocked_api_is_called()
    {
        $login = 'FOO_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(SelectLoginAdapter::class);

        $mock->shouldReceive('login')->once();

        $mock->login($login, $password);
    }


    /**
     * Confirm that adapter adequately converts BAR APIs "not authenticated"
     * return.
     * Mocked API is called.
     *
     * @test
     * @return void
     */
    public function bar_login_mocked_api_login_adapter_converts_adequately_unauthenticated()
    {
        $login = 'BAR_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(LoginService::class);
        $mock->shouldReceive('login')
            ->andReturn(false);

        app()->instance(LoginService::class, $mock);

        $return = (new SelectLoginAdapter())->login($login, $password);

        $this->assertFalse($return);
    }

    /**
     * Confirm that adapter adequately converts BAR APIs "is authenticated"
     * return.
     * Mocked API is called.
     *
     * @test
     * @return void
     */
    public function bar_login_mocked_api_login_adapter_converts_adequately_authenticated()
    {
        $login = 'BAR_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(LoginService::class);
        $mock->shouldReceive('login')
            ->andReturn(true);

        app()->instance(LoginService::class, $mock);

        $return = (new SelectLoginAdapter())->login($login, $password);

        $this->assertTrue($return);
    }

    /**
     * Confirm that adapter adequately converts BAZ APIs "not authenticated"
     * return.
     * Mocked API is called.
     *
     * @test
     * @return void
     */
    public function baz_login_mocked_api_login_adapter_converts_adequately_unauthenticated()
    {
        $login = 'BAZ_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(Authenticator::class);
        $mock->shouldReceive('auth')
            ->andReturn(new Failure());

        app()->instance(Authenticator::class, $mock);

        $return = (new SelectLoginAdapter())->login($login, $password);

        $this->assertFalse($return);
    }

    /**
     * Confirm that adapter adequately converts BAZ APIs "is authenticated"
     * return.
     * Mocked API is called.
     *
     * @test
     * @return void
     */
    public function baz_login_mocked_api_login_adapter_converts_adequately_authenticated()
    {
        $login = 'BAZ_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(Authenticator::class);
        $mock->shouldReceive('auth')
            ->andReturn(new Success());

        app()->instance(Authenticator::class, $mock);


        $return = (new SelectLoginAdapter())->login($login, $password);

        $this->assertTrue($return);
    }

    /**
     * Confirm that adapter adequately converts FOO APIs "not authenticated"
     * return.
     * Mocked API is called.
     *
     * @test
     * @return void
     */
    public function foo_login_mocked_api_login_adapter_converts_adequately_unauthenticated()
    {
        $login = 'FOO_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(AuthWS::class);
        $mock->shouldReceive('authenticate')
            ->andThrow(new AuthenticationFailedException());

        app()->instance(AuthWS::class, $mock);

        $return = (new SelectLoginAdapter())->login($login, $password);

        $this->assertFalse($return);
    }

    /**
     * Confirm that adapter adequately converts FOO APIs "is authenticated"
     * return.
     * Mocked API is called.
     *
     * @test
     * @return void
     */
    public function foo_login_mocked_api_login_adapter_converts_adequately_authenticated()
    {
        $login = 'FOO_1'; // Any login
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(AuthWS::class);
        $mock->shouldReceive('authenticate')
            ->andReturn(null);

        app()->instance(AuthWS::class, $mock);

        $return = (new SelectLoginAdapter())->login($login, $password);

        $this->assertTrue($return);
    }
}
