<?php

declare(strict_types=1);

namespace Paynet\Application;

class Environment
{
    const SANDBOX = 'https://sandbox-cm.paynet.net.br';
    const PRODUCTION = 'https://cm.paynet.net.br';

    private string $endpoint;

    public function __construct($isSandbox = false)
    {
        $this->endpoint = ($isSandbox) ? self::SANDBOX : self::PRODUCTION;
    }

    public static function sandbox()
    {
        return new self(true);
    }

    public static function production()
    {
        return new self();
    }

    public function __toString()
    {
        return $this->endpoint;
    }
}
