<?php

declare(strict_types=1);

namespace Paynet\Domain\CardInfo;

use Paynet\Domain\Card\HolderName;

class CardInfo implements \JsonSerializable
{
    const MASTERCARD = 1;
    const VISA = 3;
    const ELO = 5;
    const HIPERCARD = 7;
    const AMEX = 9;

    private HolderName $holder;
    private string $numberToken;
    private string $expiryMonth;
    private string $expiryYear;

    public function __construct(
        string $holder,
        string $numberToken,
        string $securityCode,
        int $brand,
        string $expiryMonth,
        string $expiryYear
    ) {
        $this->holder = HolderName::fromString($holder);
        $this->securityCode = $securityCode;

        $this->ensureIsValidToken($numberToken);
        $this->numberToken = $numberToken;

        $this->ensureIsValidBrand($brand);
        $this->brand = $brand;

        $this->ensureIsValidExpiryMonth($expiryMonth);
        $this->expiryMonth = sprintf("%02d", $expiryMonth);

        $this->ensureIsValidExpiryYear($expiryYear);
        $this->expiryYear = sprintf("%02d", $expiryYear);
    }

    public static function fromValues(
        string $holder,
        string $numberToken,
        string $securityCode,
        int $brand,
        string $expiryMonth,
        string $expiryYear
    ): self {
        return new self($holder, $numberToken, $securityCode, $brand, $expiryMonth, $expiryYear);
    }

    public function jsonSerialize(): array
    {
        return [
            'cardholderName' => (string) $this->holder,
            'numberToken' => (string) $this->numberToken,
            'securityCode' => (string) $this->securityCode,
            'brand' => (int) $this->brand,
            'expirationMonth' => $this->expiryMonth,
            'expirationYear' => $this->expiryYear,
        ];
    }

    private function ensureIsValidExpiryMonth(string $month): void
    {
        $monthAsInt = (int) $month;
        if (!is_numeric($month) || $monthAsInt < 1 || $monthAsInt > 12) {
            throw new \UnexpectedValueException(sprintf('%s is not valid expiry month', $month));
        }
    }

    private function ensureIsValidExpiryYear(string $year): void
    {
        if (!preg_match("/^(\d{2})$/", $year)) {
            throw new \UnexpectedValueException(sprintf('%s is not a valid expiry year format (YY)', $year));
        }

        if (!is_numeric($year)) {
            throw new \UnexpectedValueException(sprintf('%s is not valid expiry year', $year));
        }
    }

    private function ensureIsValidBrand(int $brand): void
    {
        $brands = [
            self::MASTERCARD,
            self::VISA,
            self::ELO,
            self::HIPERCARD,
            self::AMEX,
        ];

        if (!in_array($brand, $brands)) {
            throw new \UnexpectedValueException(sprintf('%d is not valid brand', $brand));
        }
    }

    private function ensureIsValidToken(string $numberToken): void
    {
        if (strlen($numberToken) < 36) {
            throw new \LengthException(sprintf('%s numberToken is too short', $numberToken));
        }

        if (strlen($numberToken) > 36) {
            throw new \LengthException(sprintf('%s numberToken is too long', $numberToken));
        }
    }
}
