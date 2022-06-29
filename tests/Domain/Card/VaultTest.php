<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Paynet\Domain\Card\Vault;

class VaultTest extends TestCase
{
    public function testCanBeCreatedFromValidValues(): void
    {
        $this->assertInstanceOf(
            Vault::class,
            Vault::fromValues('FLAVIO AUGUSTUS', '5454 5454 5454 5454', '03', '25', Vault::MASTERCARD)
        );
    }

    public function testCanBeRepresentedAsArray(): void
    {
        $this->assertSame([
            'cardNumber' => '5454545454545454',
            'cardHolder' => 'FLAVIO AUGUSTUS',
            'expirationMonth' => '03',
            'expirationYear' => '25',
            'brand' => Vault::MASTERCARD,
            'validate' => Vault::VALIDATE
        ], Vault::fromValues(
            'FLAVIO AUGUSTUS',
            '5454 5454 5454 5454',
            '03',
            '25',
            Vault::MASTERCARD
        )->jsonSerialize());
    }

    public function testCanBeRepresentedAsToken(): void
    {
        $vault = Vault::fromValues(
            'FLAVIO AUGUSTUS',
            '5454 5454 5454 5454',
            '03',
            '25',
            Vault::MASTERCARD
        );
        $vault->setToken('4efb692fc1e860542850d83378ac51bb19ea6929804339acae2c4d58d01a6171');
        $this->assertSame([
            'vaultId' => '4efb692fc1e860542850d83378ac51bb19ea6929804339acae2c4d58d01a6171'
        ], $vault->token());
    }

    public function testCannotBeCreateWithInvalidMonth(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        Vault::fromValues('FLAVIO AUGUSTUS', '5454 5454 5454 5454', '45', '25', Vault::MASTERCARD);
    }

    public function testCannotBeCreateWithInvalidYear(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        Vault::fromValues('FLAVIO AUGUSTUS', '5454 5454 5454 5454', '12', '2025', Vault::MASTERCARD);
    }
}
