<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Paynet\Domain\Card\Token;

class TokenTest extends TestCase
{
    public function testCanBeCreatedFromValidValues(): void
    {
        $this->assertInstanceOf(
            Token::class,
            Token::fromValues('FLAVIO AUGUSTUS', 'FLAVIO AUGUSTUS', '5454 5454 5454 5454', '03', '25')
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
        ], Token::fromValues(
            'FLAVIO AUGUSTUS',
            'FLAVIO AUGUSTUS',
            '5454 5454 5454 5454',
            '03',
            '25'
        )->jsonSerialize());
    }

    public function testCanBeRepresentedAsToken(): void
    {
        $token = Token::fromValues(
            'FLAVIO AUGUSTUS',
            'FLAVIO AUGUSTUS',
            '5454 5454 5454 5454',
            '03',
            '25'
        );
        $token->setToken('6b7238df-2346-493b-8ee8-e2f43efb8c4c');
        $token->setSecurityCode('123');
        $token->setBrand(Token::MASTERCARD);

        $this->assertSame([
            'numberToken' => '6b7238df-2346-493b-8ee8-e2f43efb8c4c',
            'cardholderName' => 'FLAVIO AUGUSTUS',
            'securityCode' => '123',
            'brand' => Token::MASTERCARD,
            'expirationMonth' => '03',
            'expirationYear' => '25'
        ], $token->token());
    }

    public function testCannotBeCreateWithInvalidMonth(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        Token::fromValues('FLAVIO AUGUSTUS', 'FLAVIO AUGUSTUS', '5454 5454 5454 5454', '45', '25');
    }

    public function testCannotBeCreateWithInvalidYear(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        Token::fromValues('FLAVIO AUGUSTUS', 'FLAVIO AUGUSTUS', '5454 5454 5454 5454', '12', '2025');
    }
}
