<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Paynet\Domain\Customer\IdentificationDocId;

class IdentificationDocIdTest extends TestCase
{
    public function testCanBeCreatedFromValidCpf()
    {
        $this->assertInstanceOf(
            IdentificationDocId::class,
            IdentificationDocId::fromString('799.993.388-01')
        );
    }

    public function testCanBeCreatedFromValidCnpj()
    {
        $this->assertInstanceOf(
            IdentificationDocId::class,
            IdentificationDocId::fromString('28.546.216/0001-76')
        );
    }

    public function testCannotBeCreatedFromStringLesserThan11Characters()
    {
        $this->expectException(\UnexpectedValueException::class);
        IdentificationDocId::fromString('123456');
    }

    public function testCannotBeCreatedFromStringGreaterThan13Characters()
    {
        $this->expectException(\UnexpectedValueException::class);
        IdentificationDocId::fromString('123456123456123456');
    }

    public function testCannotBeCreatedFromInvalidCpf()
    {
        $this->expectException(\UnexpectedValueException::class);
        IdentificationDocId::fromString('111.222.333-44');
    }

    public function testCannotBeCreatedFromInvalidCnpj()
    {
        $this->expectException(\UnexpectedValueException::class);
        IdentificationDocId::fromString('11.222.333/0001-44');
    }
}
