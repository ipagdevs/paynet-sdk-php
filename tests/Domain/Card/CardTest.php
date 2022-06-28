<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Paynet\Domain\Card\Card;

class CardTest extends TestCase
{
    public function testCanBeCreatedFromValidValues(): void
    {
        $this->assertInstanceOf(
            Card::class,
            Card::fromValues('FLAVIO AUGUSTUS', 'FLAVIO AUGUSTUS', '5454 5454 5454 5454', '03', '25')
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
        ], Card::fromValues(
            'FLAVIO AUGUSTUS',
            'FLAVIO AUGUSTUS',
            '5454 5454 5454 5454',
            '03',
            '25'
        )->jsonSerialize());
    }

    public function testCannotBeCreateWithInvalidMonth(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        Card::fromValues('FLAVIO AUGUSTUS', 'FLAVIO AUGUSTUS', '5454 5454 5454 5454', '45', '25');
    }

    public function testCannotBeCreateWithInvalidYear(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        Card::fromValues('FLAVIO AUGUSTUS', 'FLAVIO AUGUSTUS', '5454 5454 5454 5454', '12', '2025');
    }
}
