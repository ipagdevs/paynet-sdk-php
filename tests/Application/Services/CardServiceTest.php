<?php

declare(strict_types=1);

use Paynet\Domain\Card\Card;
use Paynet\Domain\Card\Token;
use Paynet\Domain\Card\Vault;
use PHPUnit\Framework\TestCase;
use Paynet\Application\Credentials;
use Paynet\Application\Environment;
use Paynet\Application\Http\Request;
use Paynet\Application\Services\CardService;
use Paynet\Application\Services\LoginService;

class CardServiceTest extends TestCase
{
    public function initializeApi()
    {
        $credentials = new Credentials(getenv('LOGIN'), getenv('PASSWORD'));
        $environment = Environment::sandbox();

        $api = new Request($credentials, $environment);
        $login = LoginService::login($api, $credentials);
        $api->setApiKey($login->getApiKey());

        return $api;
    }

    public function initializeCard()
    {
        return Card::fromValues(
            'FLAVIO AUGUSTUS',
            'FLAVIO AUGUSTUS',
            '5454 5454 5454 5454',
            '03',
            '25',
            '123',
            Card::MASTERCARD
        );
    }

    public function initializeToken(Request $api): Token
    {
        $card = $this->initializeCard();
        $token = new Token($card);
        $cardResponse = CardService::tokenize($api, $token);
        $token->setToken($cardResponse->token());
        return $token;
    }

    public function initializeVault(Request $api): Vault
    {
        $card = $this->initializeCard();
        $vault = new Vault($card);
        $cardResponse = CardService::vault($api, $vault);
        $vault->setToken($cardResponse->token());
        return $vault;
    }

    public function testTokenizeServiceCanBeCalledWithValidValues(): void
    {
        $api = $this->initializeApi();
        $this->assertInstanceOf(
            CardResponse::class,
            CardService::tokenize($api, $this->initializeToken($api))
        );
    }

    public function testVaultServiceCanBeCalledWithValidValues(): void
    {
        $api = $this->initializeApi();
        $this->assertInstanceOf(
            CardResponse::class,
            CardService::vault($api, $this->initializeVault($api))
        );
    }

    public function testHeadersCanBeRepresentedAsArray(): void
    {
        $api = $this->initializeApi();
        $this->assertSame([
            'Route' => '1',
            'Version' => 'v2',
            'Authorization' => $api->apiKey()
        ], CardService::headers($api->apiKey()));
    }
}
