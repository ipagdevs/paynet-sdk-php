<?php

declare(strict_types=1);

namespace Paynet\Domain;

use Paynet\Domain\Seller\Seller;
use Paynet\Domain\Payment\Payment;
use Paynet\Domain\CardInfo\CardInfo;
use Paynet\Domain\Customer\Customer;

class Transaction implements \JsonSerializable
{
    private Payment $payment;
    private CardInfo $cardInfo;
    private Seller $sellerInfo;
    private Customer $customer;
    private bool $transactionSimple;

    public function __construct(Payment $payment, CardInfo $cardInfo, Seller $sellerInfo, Customer $customer, $transactionSimple = false)
    {
        $this->payment = $payment;
        $this->cardInfo = $cardInfo;
        $this->sellerInfo = $sellerInfo;
        $this->customer = $customer;
        $this->transactionSimple = $transactionSimple;
    }

    public static function fromValues(Payment $payment, CardInfo $cardInfo, Seller $sellerInfo, Customer $customer, $transactionSimple = false): self
    {
        return new self(
            $payment,
            $cardInfo,
            $sellerInfo,
            $customer,
            $transactionSimple
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'customer' => $this->customer->jsonSerialize(),
            'cardInfo' => $this->cardInfo->jsonSerialize(),
            'sellerInfo' => $this->sellerInfo->jsonSerialize(),
            'payment' => $this->payment->jsonSerialize(),
            'transactionSimple' => $this->transactionSimple,
        ];
    }
}
