<?php

declare(strict_types=1);

use Paynet\Domain\Login;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    const VALID_LOGIN_JSON = '{"status": "success","api_key": "NFdUQ2xPMjRPMG8xSmxnZ2ZSaGJtR0h1V1MyWkZjdHMwQ1VUb1hGWQ=="}';
    const INVALID_LOGIN_JSON = '{"status": "fail"}';

    public function initializeResponse(string $json)
    {
        return new Response(200, [], $json);
    }

    public function testCanBeCreatedWithValidValues(): void
    {
        $this->assertInstanceOf(
            Login::class,
            Login::fromArray(['api_key' => '123', 'status' => 'ok'])
        );
    }

    public function testCanBeCreatedFromResponseWithValidValues(): void
    {
        $this->assertInstanceOf(
            Login::class,
            Login::createFromResponse($this->initializeResponse(self::VALID_LOGIN_JSON))
        );
    }

    public function testCanBeRepresentedAsString(): void
    {
        $this->assertSame(
            "NFdUQ2xPMjRPMG8xSmxnZ2ZSaGJtR0h1V1MyWkZjdHMwQ1VUb1hGWQ==",
            (string) Login::createFromResponse($this->initializeResponse(self::VALID_LOGIN_JSON))
        );
    }

    public function testValidLogin()
    {
        $login = Login::createFromResponse($this->initializeResponse(self::VALID_LOGIN_JSON));
        $this->assertTrue($login->isValidLogin());
    }

    public function testInvalidLogin()
    {
        $login = Login::createFromResponse($this->initializeResponse(self::INVALID_LOGIN_JSON));
        $this->assertFalse($login->isValidLogin());
    }
}
