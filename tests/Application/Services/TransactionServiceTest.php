<?php

declare(strict_types=1);

use Paynet\Domain\Response;
use Paynet\Domain\Card\Card;
use Paynet\Domain\Card\Token;
use Paynet\Domain\Transaction;
use PHPUnit\Framework\TestCase;
use Paynet\Domain\Seller\Seller;
use Paynet\Domain\Payment\Payment;
use Paynet\Application\Credentials;
use Paynet\Application\Environment;
use Paynet\Application\Http\Request;
use Paynet\Domain\Customer\Customer;
use Paynet\Application\Services\LoginService;
use Paynet\Application\Services\TransactionService;

class TransactionServiceTest extends TestCase
{
    public function initializeApi()
    {
        $credentials = new Credentials(getenv('LOGIN'), getenv('PASSWORD'));

        $api = new Request($credentials, Environment::sandbox());
        $login = LoginService::login($api, $credentials);
        $api->setApiKey($login->getApiKey());

        return $api;
    }

    public function initializeCustomer(): Customer
    {
        return Customer::fromValues('FLAVIO AUGUSTUS', '21234879611');
    }

    public function initializeSeller(): Seller
    {
        return Seller::fromValues(date('YmdHis'), 'VALID SOFTDESC', 2012);
    }

    public function initializeCard(): Card
    {
        return Card::fromValues(
            'FLAVIO AUGUSTUS',
            'FLAVIO AUGUSTUS',
            '5454545454545454',
            '03',
            '2025',
            '123',
            Card::MASTERCARD
        );
    }

    public function initializeToken(): Token
    {
        $token = new Token($this->initializeCard());
        $token->setToken('6b7238df-2346-493b-8ee8-e2f43efb8c4c');

        return $token;
    }

    public function initializePayment(): Payment
    {
        return Payment::fromValues('76600763000135', 1.23, 1, Payment::AUTH_AND_CAPTURE, false);
    }

    public function populateCustomer(Customer $customer): Customer
    {
        $customer->setEmail('test@mail.com');
        $customer->setIp('127.0.0.1');
        $customer->setPhone('(11) 2222-3333');
        $customer->setAddressFromString('Rua do Teste,123,Centro,,Presidente Prudente,SP,19060-560');

        return $customer;
    }

    public function initializeTransaction(): Transaction
    {
        $customer = $this->initializeCustomer();
        $this->populateCustomer($customer);
        $seller = $this->initializeSeller();
        $cardInfo = $this->initializeToken();
        $payment = $this->initializePayment();

        return new Transaction(
            $payment,
            $cardInfo,
            $seller,
            $customer
        );
    }

    public function testLoginServiceCanBeCalledWithValidValues(): void
    {
        $this->assertInstanceOf(
            Response::class,
            TransactionService::authorize($this->initializeApi(), $this->initializeTransaction())
        );
    }

    public function testHeadersCanBeRepresentedAsArray(): void
    {
        $api = $this->initializeApi();
        $this->assertSame([
            'Route' => '1',
            'Version' => 'v2',
            'Authorization' => $api->apiKey()
        ], TransactionService::headers($api->apiKey()));
    }
}
