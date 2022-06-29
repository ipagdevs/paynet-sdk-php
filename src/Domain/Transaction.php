<?php

declare(strict_types=1);

namespace Paynet\Domain;

use Paynet\Domain\Card\Card;
use Paynet\Domain\Seller\Seller;
use Paynet\Domain\Payment\Payment;
use Paynet\Domain\Customer\Customer;

class Transaction implements \JsonSerializable
{
    private Payment $payment;
    private Card $cardInfo;
    private Seller $sellerInfo;
    private Customer $customer;
    private bool $transactionSimple;

    public function __construct(Payment $payment, Card $cardInfo, Seller $sellerInfo, Customer $customer, $transactionSimple = false)
    {
        $this->payment = $payment;
        $this->cardInfo = $cardInfo;
        $this->sellerInfo = $sellerInfo;
        $this->customer = $customer;
        $this->transactionSimple = $transactionSimple;
    }

    public function transactionSimple(): void
    {
        $this->transactionSimple = true;
    }

    public function jsonSerialize(): array
    {
        return [
            'customer' => $this->customer->jsonSerialize(),
            'cardInfo' => $this->cardInfo->token(),
            'sellerInfo' => $this->sellerInfo->jsonSerialize(),
            'payment' => $this->payment->jsonSerialize(),
            'transactionSimple' => $this->transactionSimple,
        ];
    }
}
