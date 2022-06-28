<?php

declare(strict_types=1);

use Paynet\Domain\Card\Card;
use PHPUnit\Framework\TestCase;
use Paynet\Domain\CardInfo\CardInfo;

class CardInfoTest extends TestCase
{
    public function testCanBeCreatedFromValidValues(): void
    {
        $this->assertInstanceOf(
            CardInfo::class,
            CardInfo::fromValues(
                'FLAVIO AUGUSTUS', 
                '6b7238df-2346-493b-8ee8-e2f43efb8c4c', 
                '123',
                CardInfo::MASTERCARD, 
                '03', 
                '25'
            )
        );
    }

    public function testCanBeRepresentedAsArray(): void
    {
        $this->assertSame([
            'cardholderName' => 'FLAVIO AUGUSTUS',
            'numberToken' => '6b7238df-2346-493b-8ee8-e2f43efb8c4c',
            'securityCode' => '123',
            'brand' => CardInfo::MASTERCARD,
            'expirationMonth' => '03',
            'expirationYear' => '25'
        ], CardInfo::fromValues(
            'FLAVIO AUGUSTUS',
            '6b7238df-2346-493b-8ee8-e2f43efb8c4c',
            '123',
            CardInfo::MASTERCARD,
            '03',
            '25'
        )->jsonSerialize());
    }

    public function testCannotBeCreateWithInvalidMonth(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        CardInfo::fromValues(
            'FLAVIO AUGUSTUS', 
            '6b7238df-2346-493b-8ee8-e2f43efb8c4c', 
            '123',
            CardInfo::MASTERCARD, 
            '45', 
            '25'
        );
    }

    public function testCannotBeCreateWithInvalidYear(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        CardInfo::fromValues(
            'FLAVIO AUGUSTUS', 
            '6b7238df-2346-493b-8ee8-e2f43efb8c4c', 
            '123',
            CardInfo::MASTERCARD, 
            '12', 
            '2025'
        );
    }

    public function testCannotBeCreateWithShortToken(): void
    {
        $this->expectException(\LengthException::class);

        CardInfo::fromValues(
            'FLAVIO AUGUSTUS', 
            '6b7238df-2346-493b-8ee8-e2fc', 
            '123',
            CardInfo::MASTERCARD, 
            '12', 
            '25'
        );
    }

    public function testCannotBeCreateWithLongToken(): void
    {
        $this->expectException(\LengthException::class);

        CardInfo::fromValues(
            'FLAVIO AUGUSTUS', 
            '6b7238df-2346-493b-8ee8-e2f43efb8c4c123', 
            '123',
            CardInfo::MASTERCARD, 
            '12', 
            '25'
        );
    }
}
