<?php

namespace Tests\Feature;

use External\Bar\Auth\LoginService;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * Confirms that authentication fails if "login" parameter wrong;
     * Does not call APIs.
     *
     * @test
     * @return void
     */
    public function all_login_services_no_success_wrong_login()
    {
        $login = 'yyy'; // Wrong login
        $password = 'foo-bar-baz'; // Good password

        $response = $this->call(
            'POST',
            'api/login',
            $parameters = [],
            $cookies = [],
            $files = [],
            $server = ['CONTENT_TYPE' => 'text/plain'],
            '{"login":"' . $login .  '","password":"' . $password . '"}'
        );
        $response->assertExactJson(['status' => 'failure']);
    }

    /**
     * Confirms that authentication fails if "password" parameter wrong;
     * Calls BAR API.
     *
     * @test
     * @return void
     */
    public function bar_login_api_no_success_wrong_password()
    {
        $login = 'BAR_123'; // Good login
        $password = 'foo-bar-ba'; // Wrong password

        $response = $this->call(
            'POST',
            'api/login',
            $parameters = [],
            $cookies = [],
            $files = [],
            $server = ['CONTENT_TYPE' => 'text/plain'],
            '{"login":"' . $login .  '","password":"' . $password . '"}'
        );
        $response->assertExactJson(['status' => 'failure']);
    }


    /**
     * Confirms that authentication is successful with adequate params;
     * Calls BAR API.
     *
     * @test
     * @return void
     */
    public function bar_login_api_success()
    {
        $login = 'BAR_123'; // Good login
        $password = 'foo-bar-baz'; // Good password

        $response = $this->call(
            'POST',
            'api/login',
            $parameters = [],
            $cookies = [],
            $files = [],
            $server = ['CONTENT_TYPE' => 'text/plain'],
            '{"login":"' . $login .  '","password":"' . $password . '"}'
        );

        $response = json_decode($response->getContent());

        $this->assertNotNull($response->token);
        $this->assertEquals('success', $response->status);
    }

    /**
     * Confirms that response is as expected if authentication fails;
     * Auth failure is enforced.
     * Does not call APIs.
     *
     * @test
     * @return void
     */
    public function bar_login_mocked_api_no_success()
    {
        $login = 'BAR_123'; // Login must be one of the: "BAR_..", "BAZ_..", "FOO_.." .
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(LoginService::class);
        $mock->shouldReceive('login')
            ->andReturn(false);

        app()->instance(LoginService::class, $mock);

        $response = $this->call(
            'POST',
            'api/login',
            $parameters = [],
            $cookies = [],
            $files = [],
            $server = ['CONTENT_TYPE' => 'text/plain'],
            '{"login":"' . $login .  '","password":"' . $password . '"}'
        );

        $response->assertExactJson(['status' => 'failure']);
    }

    /**
     * Confirms that response is as expected if authentication is OK;
     * Auth success is enforced.
     * Does not call APIs.
     *
     * @test
     * @return void
     */
    public function bar_login_mocked_api_success()
    {
        $login = 'BAR_123'; // Login must be one of the: "BAR_..", "BAZ_..", "FOO_.." .
        $password = 'foo-bar-baz'; // Any password

        $mock = \Mockery::mock(LoginService::class);
        $mock->shouldReceive('login')
            ->andReturn(true);

        app()->instance(LoginService::class, $mock);

        $response = $this->call(
            'POST',
            'api/login',
            $parameters = [],
            $cookies = [],
            $files = [],
            $server = ['CONTENT_TYPE' => 'text/plain'],
            '{"login":"' . $login .  '","password":"' . $password . '"}'
        );

//        $response->assertExactJson(['status' => 'failure']);

        $response = json_decode($response->getContent());

        $this->assertNotNull($response->token);
        $this->assertEquals('success', $response->status);
    }

}
