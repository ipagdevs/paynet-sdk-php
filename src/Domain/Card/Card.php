<?php

declare(strict_types=1);

namespace Paynet\Domain\Card;

abstract class Card implements CardInterface
{
    const MASTERCARD = 1;
    const VISA = 3;
    const ELO = 5;
    const HIPERCARD = 7;
    const AMEX = 9;

    private CardInterface $card;

    public function jsonSerialize(): array
    {
        return $this->card->jsonSerialize();
    }

    public function token(): array
    {
        return $this->card->token();
    }

    protected function ensureIsValidExpiryMonth(string $month): void
    {
        $monthAsInt = (int) $month;
        if (!is_numeric($month) || $monthAsInt < 1 || $monthAsInt > 12) {
            throw new \UnexpectedValueException(sprintf('%s is not valid expiry month', $month));
        }
    }

    protected function ensureIsValidExpiryYear(string $year): void
    {
        if (!preg_match("/^(\d{2})$/", $year)) {
            throw new \UnexpectedValueException(sprintf('%s is not a valid expiry year format (YY)', $year));
        }

        if (!is_numeric($year)) {
            throw new \UnexpectedValueException(sprintf('%s is not valid expiry year', $year));
        }
    }

    protected function ensureIsValidBrand(int $brand): void
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
}
