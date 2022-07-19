<?php

declare(strict_types=1);

namespace Paynet\Domain;

use Paynet\Domain\Card\CardInterface;
use Paynet\Domain\Seller\Seller;
use Paynet\Domain\Payment\Payment;
use Paynet\Domain\Customer\Customer;

class Transaction implements \JsonSerializable
{
    const NOT_FINISHED = 0;
    const AUTHORIZED = 1;
    const CAPTURED = 2;
    const DENIED = 3;
    const VOIDED = 10;
    const REFUNDED = 11;
    const PENDING = 12;
    const ABORTED = 13;
    const SCHEDULED = 20;

    private Payment $payment;
    private CardInterface $cardInfo;
    private Seller $sellerInfo;
    private Customer $customer;
    private bool $transactionSimple;

    public function __construct(Payment $payment, CardInterface $cardInfo, Seller $sellerInfo, Customer $customer, $transactionSimple = false)
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
