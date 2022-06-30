<?php

declare(strict_types=1);

use Paynet\Domain\Operation;
use PHPUnit\Framework\TestCase;

class OperationTest extends TestCase
{
    public function testCanBeCreatedCaptureOperationWithValidValues(): void
    {
        $this->assertInstanceOf(
            Operation::class,
            new Operation(Operation::CAPTURE, '28.546.216/0001-76', 1.23, 'asd123-123456asdfgh-dsa321')
        );
    }

    public function testCanBeCreatedCancelOperationWithValidValues(): void
    {
        $this->assertInstanceOf(
            Operation::class,
            new Operation(Operation::CANCEL, '28.546.216/0001-76', 1.23, 'asd123-123456asdfgh-dsa321')
        );
    }

    public function testCanBeRepresentedAsArray(): void
    {
        $this->assertSame([
            "documentNumber" => "28546216000176",
            "paymentId" => "asd123-123456asdfgh-dsa321",
            "amount" => 123
        ], (new Operation(
            Operation::CANCEL,
            '28.546.216/0001-76',
            1.23,
            'asd123-123456asdfgh-dsa321'
        ))->jsonSerialize());
    }

    public function testCannotBeCreatedWithInvalidDocument()
    {
        $this->expectException(\UnexpectedValueException::class);
        new Operation(Operation::CANCEL, '11.111.111/1111-11', 1.23, 'asd123-123456asdfgh-dsa321');
    }

    public function testCannotBeCreatedWithInvalidOperation()
    {
        $this->expectException(\UnexpectedValueException::class);
        new Operation('authorize', '28.546.216/0001-76', 1.23, 'asd123-123456asdfgh-dsa321');
    }

    public function testCannotBeCreatedWithInvalidAmount()
    {
        $this->expectException(\UnexpectedValueException::class);
        new Operation(Operation::CANCEL, '28.546.216/0001-76', -1.23, 'asd123-123456asdfgh-dsa321');
    }

    public function testCannotBeCreatedWithInvalidPaymentId()
    {
        $this->expectException(\UnexpectedValueException::class);
        new Operation(Operation::CANCEL, '28.546.216/0001-76', 1.23, '');
    }
}
