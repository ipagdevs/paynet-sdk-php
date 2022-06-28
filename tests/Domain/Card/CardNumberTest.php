<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Paynet\Domain\Card\CardNumber;

class CardNumberTest extends TestCase
{
    public function testCanCreatedCardNumberFromValidString(): void
    {
        $this->assertInstanceOf(
            CardNumber::class,
            CardNumber::fromString('4111 1111 1111 1111')
        );
    }

    public function testCanBeRepresentedAsString(): void
    {
        $this->assertSame(
            '4111111111111111',
            (string) CardNumber::fromString('4111 1111 1111 1111')
        );
    }

    public function testCannotBeCreatedFromInvalidNumber(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        CardNumber::fromString('1234 1234 1234 1234');
    }
}
