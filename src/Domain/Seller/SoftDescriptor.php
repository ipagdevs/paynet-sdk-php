<?php

declare(strict_types=1);

namespace Paynet\Domain\Seller;

class SoftDescriptor
{
    private string $softDescriptor;

    public function __construct(string $softDescriptor)
    {
        $this->guardAgainstInvalidSoftDescriptor($softDescriptor);

        $this->setSoftDescriptor($softDescriptor);
    }

    public  static function fromString(string $softDescriptor): self
    {
        return new self($softDescriptor);
    }

    public function __toString(): string
    {
        return $this->softDescriptor;
    }

    private function guardAgainstInvalidSoftDescriptor(string $softDescriptor): void
    {
        if (!preg_match('/^[a-zA-Z0-9 ]*$/', $softDescriptor)) {
            throw new \UnexpectedValueException(sprintf('%s is not valid soft descriptor', $softDescriptor));
        }

        if (strlen($softDescriptor) > 18) {
            throw new \LengthException(sprintf('%s soft descriptor is too long', $softDescriptor));
        }
    }

    private function setSoftDescriptor(string $softDescriptor): void
    {
        $this->softDescriptor = str_replace(' ', '*', $softDescriptor);
    }
}
