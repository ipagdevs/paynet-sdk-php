<?php

declare(strict_types=1);

use Paynet\Domain\Seller\SoftDescriptor;
use PHPUnit\Framework\TestCase;

class SoftDescriptorTest extends TestCase
{
    public function testCanBeCreatedFromValidSoftDescriptor()
    {
        $this->assertInstanceOf(
            SoftDescriptor::class,
            SoftDescriptor::fromString('VALID SOFTDESCR')
        );
    }

    public function testSoftDescriptorFormatted()
    {
        $instance = SoftDescriptor::fromString('VALID SOFTDESCR');
        $this->assertEquals(
            (string) $instance,
            'VALID*SOFTDESCR'
        );
    }

    public function testCannotBeCreatedFromStringGreaterThan18Characters()
    {
        $this->expectException(\LengthException::class);
        SoftDescriptor::fromString('SOFTDESCRIPTOR TOO LONG');
    }

    public function testCannotBeCreatedFromInvalidSoftDescriptor()
    {
        $this->expectException(\UnexpectedValueException::class);
        SoftDescriptor::fromString('SOFT^_DE$CRIPT');
    }
}
