<?php

declare(strict_types=1);

use Paynet\Domain\Card\Card;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    public function testCanBeCreatedFromValidValues(): void
    {
        $this->assertInstanceOf(
            Card::class,
            Card::fromValues(
                'FLAVIO AUGUSTUS',
                'FLAVIO AUGUSTUS',
                '5454 5454 5454 5454',
                '03',
                '2025',
                '123',
                Card::MASTERCARD
            )
        );
    }

    public function testCannotBeCreateWithInvalidMonth(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        Card::fromValues(
            'FLAVIO AUGUSTUS',
            'FLAVIO AUGUSTUS',
            '5454 5454 5454 5454',
            '45',
            '2025',
            '123',
            Card::MASTERCARD
        );
    }

    public function testCannotBeCreateWithInvalidYear(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        Card::fromValues(
            'FLAVIO AUGUSTUS',
            'FLAVIO AUGUSTUS',
            '5454 5454 5454 5454',
            '05',
            '25',
            '123',
            Card::MASTERCARD
        );
    }

    public function testCannotBeCreateWithInvalidCvv(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        Card::fromValues(
            'FLAVIO AUGUSTUS',
            'FLAVIO AUGUSTUS',
            '5454 5454 5454 5454',
            '05',
            '2025',
            '12342',
            Card::MASTERCARD
        );
    }
}
