<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use Paynet\Domain\CardResponse;
use PHPUnit\Framework\TestCase;

class CardResponseTest extends TestCase
{
    const VALID_TOKEN_JSON = '{"numberToken": "6b7238df-2346-493b-8ee8-e2f43efb8c4c"}';
    const VALID_VAULT_JSON = '{"data": {"vaultId": "4efb692fc1e860542850d83378ac51bb19ea6929804339acae2c4d58d01a6171"}}';

    public function initializeResponse(string $json)
    {
        return new Response(200, [], $json);
    }

    public function testCanBeCreatedFromResponseWithValidValues(): void
    {
        $this->assertInstanceOf(
            CardResponse::class,
            CardResponse::createFromResponse($this->initializeResponse(self::VALID_TOKEN_JSON))
        );
    }

    public function testVaultCanBeRepresentedAsArray(): void
    {
        $this->assertSame([
            'token' => '4efb692fc1e860542850d83378ac51bb19ea6929804339acae2c4d58d01a6171',
            'type' => 'vault'
        ], CardResponse::createFromResponse($this->initializeResponse(self::VALID_VAULT_JSON))->jsonSerialize());
    }

    public function testTokenCanBeRepresentedAsArray(): void
    {
        $this->assertSame([
            'token' => '6b7238df-2346-493b-8ee8-e2f43efb8c4c',
            'type' => 'token'
        ], CardResponse::createFromResponse($this->initializeResponse(self::VALID_TOKEN_JSON))->jsonSerialize());
    }
}
