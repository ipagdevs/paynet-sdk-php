<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Paynet\Domain\Seller\Seller;

class SellerTest extends TestCase
{
    public function testCanBeCreatedWithValidValues(): void
    {
        $this->assertInstanceOf(
            Seller::class,
            Seller::fromValues('000001', 'VALID SOFTDESC', 2012)
        );
    }

    public function testCanBeRepresentedAsArray(): void
    {
        $this->assertSame([
            'orderNumber' => '000001',
            'softDescriptor' => 'VALID*SOFTDESC',
            'dynamicMcc' => 2012,
        ], Seller::fromValues(
            '000001', 
            'VALID SOFTDESC', 
            2012
        )->jsonSerialize());
    }

    public function testCannotBeInitializedWithInvalidOrderNumber(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        Seller::fromValues('1231*asv', 'VALID SOFTDESC', 2012);
    }
}
