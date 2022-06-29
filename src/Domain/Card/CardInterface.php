<?php

declare(strict_types=1);

namespace Paynet\Domain\Card;

interface CardInterface extends \JsonSerializable
{
    public function token(): array;
}
