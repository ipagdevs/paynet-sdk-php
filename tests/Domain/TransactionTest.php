<?php

declare(strict_types=1);

use Paynet\Domain\Transaction;
use PHPUnit\Framework\TestCase;
use Paynet\Domain\Seller\Seller;
use Paynet\Domain\Payment\Payment;
use Paynet\Domain\CardInfo\CardInfo;
use Paynet\Domain\Customer\Customer;

class TransactionTest extends TestCase
{
    public function initializeCustomer(): Customer
    {
        return Customer::fromValues('FLAVIO AUGUSTUS', '21234879611');
    }

    public function initializeSeller(): Seller
    {
        return Seller::fromValues('000001', 'VALID SOFTDESC', 2012);
    }

    public function initializeCardInfo(): CardInfo
    {
        return CardInfo::fromValues(
            'FLAVIO AUGUSTUS', 
            '6b7238df-2346-493b-8ee8-e2f43efb8c4c', 
            '123',
            CardInfo::MASTERCARD, 
            '03', 
            '25'
        );
    }

    public function initializePayment(): Payment
    {
        return Payment::fromValues('21234879611', 1.23, 1, Payment::AUTH_AND_CAPTURE, false);
    }

    public function populateCustomer(Customer $customer): Customer
    {
        $customer->setEmail('test@mail.com');
        $customer->setIp('127.0.0.1');
        $customer->setPhone('(11) 2222-3333');
        $customer->setAddress('Rua do Teste,123,Centro,,Presidente Prudente,SP,19060-560');

        return $customer;
    }

    public function testCanBeCreatedWithAllValues(): void
    {
        $customer = $this->initializeCustomer();
        $this->populateCustomer($customer);
        $seller = $this->initializeSeller();
        $cardInfo = $this->initializeCardInfo();
        $payment = $this->initializePayment();

        $this->assertInstanceOf(
            Transaction::class,
            Transaction::fromValues(
                $payment,
                $cardInfo,
                $seller,
                $customer
            )
        );
    }

    public function testCanBeRepresentedAsArrayWithAllValues(): void
    {
        $customer = $this->initializeCustomer();
        $this->populateCustomer($customer);
        $seller = $this->initializeSeller();
        $cardInfo = $this->initializeCardInfo();
        $payment = $this->initializePayment();
        $transaction = Transaction::fromValues(
            $payment,
            $cardInfo,
            $seller,
            $customer
        );

        $this->assertSame(
            [
                'customer' => [
                    'documentType' => '2',
                    'documentNumberCDH' => '21234879611',
                    'firstName' => 'FLAVIO',
                    'lastName' => 'AUGUSTUS',
                    'email' => 'test@mail.com',
                    'phoneNumber' => '(11) 2222-3333',
                    'ipAddress' => '127.0.0.1',
                    'country' => 'BRA',
                    'address' => 'Rua do Teste 123',
                    'complement' => '',
                    'city' => 'Presidente Prudente',
                    'state' => 'SP',
                    'zipCode' => '19060-560',
                ],
                'cardInfo' => [
                    'cardholderName' => 'FLAVIO AUGUSTUS',
                    'numberToken' => '6b7238df-2346-493b-8ee8-e2f43efb8c4c',
                    'securityCode' => '123',
                    'brand' => CardInfo::MASTERCARD,
                    'expirationMonth' => '03',
                    'expirationYear' => '25',
                ],
                'sellerInfo' => [
                    'orderNumber' => '000001',
                    'softDescriptor' => 'VALID*SOFTDESC',
                    'dynamicMcc' => 2012,
                ],
                'payment' => [
                    'documentNumber' => '21234879611',
                    'transactionType' => Payment::TRANSACTION_TYPE,
                    'amount' => 123,
                    'currencyCode' => Payment::CURRENCY_BRL,
                    'productType' => Payment::A_VISTA,
                    'installments' => 1,
                    'captureType' => Payment::AUTH_AND_CAPTURE,
                    'recurrent' => false,
                ],
                'transactionSimple' => false
            ],
            $transaction->jsonSerialize()
        );
    }

    public function testCanBeCreatedWithSimpleValues(): void
    {
        $customer = $this->initializeCustomer();
        $seller = $this->initializeSeller();
        $cardInfo = $this->initializeCardInfo();
        $payment = $this->initializePayment();

        $this->assertInstanceOf(
            Transaction::class,
            Transaction::fromValues(
                $payment,
                $cardInfo,
                $seller,
                $customer
            )
        );
    }

    public function testCanBeRepresentedAsArrayWithSimpleValues(): void
    {
        $customer = $this->initializeCustomer();
        $seller = $this->initializeSeller();
        $cardInfo = $this->initializeCardInfo();
        $payment = $this->initializePayment();
        $transaction = Transaction::fromValues(
            $payment,
            $cardInfo,
            $seller,
            $customer
        );

        $this->assertSame(
            [
                'customer' => [
                    'documentType' => '2',
                    'documentNumberCDH' => '21234879611',
                    'firstName' => 'FLAVIO',
                    'lastName' => 'AUGUSTUS',
                    'email' => '',
                    'phoneNumber' => '',
                    'ipAddress' => '',
                    'country' => 'BRA',
                ],
                'cardInfo' => [
                    'cardholderName' => 'FLAVIO AUGUSTUS',
                    'numberToken' => '6b7238df-2346-493b-8ee8-e2f43efb8c4c',
                    'securityCode' => '123',
                    'brand' => CardInfo::MASTERCARD,
                    'expirationMonth' => '03',
                    'expirationYear' => '25',
                ],
                'sellerInfo' => [
                    'orderNumber' => '000001',
                    'softDescriptor' => 'VALID*SOFTDESC',
                    'dynamicMcc' => 2012,
                ],
                'payment' => [
                    'documentNumber' => '21234879611',
                    'transactionType' => Payment::TRANSACTION_TYPE,
                    'amount' => 123,
                    'currencyCode' => Payment::CURRENCY_BRL,
                    'productType' => Payment::A_VISTA,
                    'installments' => 1,
                    'captureType' => Payment::AUTH_AND_CAPTURE,
                    'recurrent' => false,
                ],
                'transactionSimple' => false
            ],
            $transaction->jsonSerialize()
        );
    }
}
