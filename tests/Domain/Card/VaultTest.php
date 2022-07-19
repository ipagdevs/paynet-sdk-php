<?php

declare(strict_types=1);

use Paynet\Domain\Card\Card;
use Paynet\Domain\Card\Vault;
use PHPUnit\Framework\TestCase;

class VaultTest extends TestCase
{
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

    public function testCanBeCreatedFromValidValues(): void
    {
        $this->assertInstanceOf(
            Vault::class,
            new Vault($this->initializeCard())
        );
    }

    public function testCanBeRepresentedAsArray(): void
    {
        $this->assertSame([
            'cardNumber' => '5454545454545454',
            'cardHolder' => 'FLAVIO AUGUSTUS',
            'expirationMonth' => '03',
            'expirationYear' => '2025',
            'brand' => Card::MASTERCARD,
            'validate' => Vault::VALIDATE
        ], (new Vault($this->initializeCard()))->jsonSerialize());
    }

    public function testCanBeRepresentedAsToken(): void
    {
        $vault = new Vault($this->initializeCard());
        $vault->setToken('4efb692fc1e860542850d83378ac51bb19ea6929804339acae2c4d58d01a6171');
        $this->assertSame([
            'vaultId' => '4efb692fc1e860542850d83378ac51bb19ea6929804339acae2c4d58d01a6171',
            'securityCode' => '123'
        ], $vault->token());
    }
}
