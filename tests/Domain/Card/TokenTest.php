<?php

declare(strict_types=1);

use Paynet\Domain\Card\Card;
use Paynet\Domain\Card\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    public function initializeCard(): Card
    {
        return Card::fromValues(
            'FLAVIO AUGUSTUS',
            'FLAVIO AUGUSTUS',
            '5454545454545454',
            '03',
            '25',
            '123',
            Card::MASTERCARD
        );
    }

    public function testCanBeCreatedFromValidValues(): void
    {
        $this->assertInstanceOf(
            Token::class,
            new Token($this->initializeCard())
        );
    }

    public function testCanBeRepresentedAsArray(): void
    {
        $this->assertSame([
            'cardHolder' => 'FLAVIO AUGUSTUS',
            'customerName' => 'FLAVIO AUGUSTUS',
            'cardNumber' => '5454545454545454',
            'expirationMonth' => '03',
            'expirationYear' => '25'
        ], (new Token($this->initializeCard()))->jsonSerialize());
    }

    public function testCanBeRepresentedAsToken(): void
    {
        $token = new Token($this->initializeCard());
        $token->setToken('6b7238df-2346-493b-8ee8-e2f43efb8c4c');

        $this->assertSame([
            'numberToken' => '6b7238df-2346-493b-8ee8-e2f43efb8c4c',
            'cardholderName' => 'FLAVIO AUGUSTUS',
            'securityCode' => '123',
            'brand' => Card::MASTERCARD,
            'expirationMonth' => '03',
            'expirationYear' => '25'
        ], $token->token());
    }
}
