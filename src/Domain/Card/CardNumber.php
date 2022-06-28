<?php

declare(strict_types=1);

namespace Paynet\Domain\Card;

class CardNumber
{
    private string $number;

    public function __construct(string $number)
    {
        $number = preg_replace('/\D/', '', $number);
        $this->ensureIsValidLuhn($number);

        $this->number = $number;
    }

    public static function fromString(string $number): self
    {
        return new self($number);
    }

    public function __toString(): string
    {
        return $this->number;
    }

    private function ensureIsValidLuhn(string $number): void
    {
        $parity = strlen($number) % 2;
        $total = 0;

        $digits = str_split($number);
        foreach ($digits as $key => $digit) {
            $digit = (int) $digit;
            if (($key % 2) == $parity) {
                $digit = ($digit * 2);
            }

            if ($digit >= 10) {
                $digit_parts = str_split((string) $digit);
                $digit = $digit_parts[0] + $digit_parts[1];
            }
            $total += $digit;
        }

        if ($total % 10 !== 0) {
            throw new \UnexpectedValueException(
                sprintf('%s is not a valid card number', $number)
            );
        }
    }
}
