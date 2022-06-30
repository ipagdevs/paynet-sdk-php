<?php

declare(strict_types=1);

use Paynet\Domain\Login;
use PHPUnit\Framework\TestCase;
use Paynet\Application\Credentials;
use Paynet\Application\Environment;
use Paynet\Application\Http\Request;
use Paynet\Application\Services\LoginService;

class LoginServiceTest extends TestCase
{
    public function initializeApi()
    {
        $credentials = new Credentials(getenv('LOGIN'), getenv('PASSWORD'));

        $api = new Request($credentials, Environment::sandbox());
        $login = LoginService::login($api, $credentials);
        $api->setApiKey($login->getApiKey());

        return $api;
    }

    public function initializeCredentials(): Credentials
    {
        return new Credentials(getenv('LOGIN'), getenv('PASSWORD'));
    }

    public function testLoginServiceCanBeCalledWithValidValues(): void
    {
        $api = $this->initializeApi();
        $this->assertInstanceOf(
            Login::class,
            LoginService::login($api, $this->initializeCredentials())
        );
    }

    public function testHeadersCanBeRepresentedAsArray(): void
    {
        $this->assertSame([
            'Route' => '1',
        ], LoginService::headers());
    }
}
