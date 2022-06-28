<?php

declare(strict_types=1);

namespace Paynet\Domain\Card;

class HolderName
{
    private string $holder;

    public function __construct(string $holder)
    {
        $holder = trim($holder);
        $this->ensureIsValidHolder($holder);

        $this->holder = strtoupper($holder);
    }

    public static function fromString(string $holder): self
    {
        return new self($holder);
    }

    public function __toString(): string
    {
        return $this->holder;
    }

    private function ensureIsValidHolder(string $holder): void
    {
        $holderNames = explode(' ', $holder);
        if (count($holderNames) <= 1) {
            throw new \UnexpectedValueException(sprintf('%s must have at least first and one lastname', $holder));
        }

        foreach ($holderNames as $singlename) {
            if (!preg_match('/^[a-zA-Z]+$/', $singlename)) {
                throw new \UnexpectedValueException(
                    sprintf('%s is not a valid holder', $holder)
                );
            }
        }
    }
}
