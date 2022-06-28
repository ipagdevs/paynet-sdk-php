<?php

declare(strict_types=1);

namespace Paynet\Domain\Seller;

class Seller implements \JsonSerializable
{
    private SoftDescriptor $softDescriptor;
    private string $orderNumber;
    private int $dynamicMcc;

    public function __construct(string $orderNumber, string $softDescriptor = '', $dynamicMcc = 0)
    {
        $this->softDescriptor = SoftDescriptor::fromString($softDescriptor);
        $this->ensureIsValidOrderNumber($orderNumber);
        $this->orderNumber = $orderNumber;
        $this->dynamicMcc = $dynamicMcc;
    }

    public static function fromValues(string $orderNumber, string $softDescriptor = '', $dynamicMcc = 0): self
    {
        return new self(
            $orderNumber,
            $softDescriptor,
            $dynamicMcc
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'orderNumber' => $this->orderNumber,
            'softDescriptor' => (string) $this->softDescriptor,
            'dynamicMcc' => $this->dynamicMcc,
        ];
    }

    private function ensureIsValidOrderNumber(string $orderNumber): void
    {
        if (!preg_match('/^[a-zA-Z0-9]*$/', $orderNumber)) {
            throw new \UnexpectedValueException(sprintf('%s is not valid order number', $orderNumber));
        }
    }
}
