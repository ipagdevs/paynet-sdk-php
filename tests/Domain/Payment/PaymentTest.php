<?php

declare(strict_types=1);

use Paynet\Domain\Payment\Payment;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    public function testCanBeCreatedWithValidValues(): void
    {
        $this->assertInstanceOf(
            Payment::class,
            Payment::fromValues('21234879611', 1.23, 1, Payment::AUTH_AND_CAPTURE, false)
        );
    }

    public function testCanBeRepresentedAsArray(): void
    {
        $this->assertSame([
            'documentNumber' => '21234879611',
            'transactionType' => Payment::TRANSACTION_TYPE,
            'amount' => 123,
            'currencyCode' => Payment::CURRENCY_BRL,
            'productType' => Payment::A_VISTA,
            'installments' => 1,
            'captureType' => Payment::AUTH_AND_CAPTURE,
            'recurrent' => false,
        ], Payment::fromValues(
            '21234879611',
            1.23,
            1,
            Payment::AUTH_AND_CAPTURE,
            false
        )->jsonSerialize());
    }

    public function testCannotBeInitializedWithInvalidDocument(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        Payment::fromValues('11111111111', 1.23, 1, Payment::AUTH_AND_CAPTURE, false);
    }

    public function testCannotBeInitializedWithInvalidAmount(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        Payment::fromValues('21234879611', 0, 1, Payment::AUTH_AND_CAPTURE, false);
    }

    public function testCannotBeInitializedWithInvalidInstallments(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        Payment::fromValues('21234879611', 1.23, 20, Payment::AUTH_AND_CAPTURE, false);
    }

    public function testCannotBeInitializedWithInvalidCaptureType(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        Payment::fromValues('21234879611', 1.23, 1, 10, false);
    }
}
