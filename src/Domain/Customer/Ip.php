<?php

declare(strict_types=1);

namespace Paynet\Domain\Customer;

class Ip
{
    private string $ip;

    public function __construct(string $ip)
    {
        $this->guardAgainstInvalidIp($ip);

        $this->ip = $ip;
    }

    public  static function fromString(string $ip): self
    {
        return new self($ip);
    }

    public function __toString(): string
    {
        return $this->ip;
    }

    private function guardAgainstInvalidIp(string $ip): void
    {
        if (
            !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) &&
            !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
        ) {
            throw new \UnexpectedValueException(
                sprintf('%s is not valid IP', $ip)
            );
        }
    }
}
