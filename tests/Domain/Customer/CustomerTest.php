<?php

declare(strict_types=1);

use Paynet\Domain\Customer\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public function initializeCustomer(): Customer
    {
        return Customer::fromValues('FLAVIO AUGUSTUS', '21234879611');
    }

    public function populateCustomer(Customer $customer): Customer
    {
        $customer->setEmail('test@mail.com');
        $customer->setIp('127.0.0.1');
        $customer->setPhone('(11) 2222-3333');
        $customer->setAddressFromString('Rua do Teste,123,Centro,,Presidente Prudente,SP,19060-560');

        return $customer;
    }

    public function testCanBeInitializedWithValidValues(): void
    {
        $this->assertInstanceOf(
            Customer::class,
            Customer::fromValues('FLAVIO AUGUSTUS', '21234879611')
        );
    }

    public function testCanBeRepresentedAsArray(): void
    {
        $this->assertSame([
            'documentType' => '2',
            'documentNumberCDH' => '21234879611',
            'firstName' => 'FLAVIO',
            'lastName' => 'AUGUSTUS',
            'email' => '',
            'phoneNumber' => '',
            'ipAddress' => '',
            'country' => 'BRA',
        ], Customer::fromValues(
            'FLAVIO AUGUSTUS',
            '212.348.796-11',
        )->jsonSerialize());
    }

    public function testCanBeCreatedWithAllValues(): void
    {
        $customer = $this->initializeCustomer();
        $this->populateCustomer($customer);

        $this->assertInstanceOf(
            Customer::class,
            $customer
        );
    }

    public function testCanBeRepresentedAsArrayWithAllValues(): void
    {
        $customer = $this->initializeCustomer();
        $this->populateCustomer($customer);

        $this->assertSame([
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
        ], $customer->jsonSerialize());
    }

    public function testCannotBeInitializedWithInvalidDocument(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        Customer::fromValues('FLAVIO AUGUSTUS', '11111111111');
    }

    public function testCannotBeInitializedWithInvalidName(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        Customer::fromValues('', '21234879611');
    }

    public function testCannotSetInvalidEmail(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $customer = $this->initializeCustomer();
        $customer->setEmail('test@test');
    }

    public function testCannotSetInvalidIp(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $customer = $this->initializeCustomer();
        $customer->setIp('127.0');
    }

    public function testCannotSetInvalidPhone(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $customer = $this->initializeCustomer();
        $customer->setPhone('1234');
    }

    public function testCannotSetInvalidAddress(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $customer = $this->initializeCustomer();
        $customer->setAddressFromString('Rua do Teste,123,Centro,,Presidente Prudente,SP,123');
    }
}
