<?php

declare(strict_types=1);

namespace Paynet\Domain\Card;

interface CardInterface
{
    public function token(): array;

    public function setToken(string $token): void;
}
