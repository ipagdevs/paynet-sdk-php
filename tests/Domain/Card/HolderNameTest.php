<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Paynet\Domain\Card\HolderName;

class HolderNameTest extends TestCase
{
    public function testCanBeCreatedFromValidString(): void
    {
        $this->assertSame('JULIO CESAR', (string) HolderName::fromString('julio cesar'));
    }

    public function testCannotBeCreatedFromOneName(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        HolderName::fromString('JULIO');
    }

    public function testCannotBeCreatedFromInvalidCharacters(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        HolderName::fromString('JÚLIO CESAR');
    }
}
