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
        if ($isSandbox) {
            $this->endpoint = self::SANDBOX;
        }
        $this->endpoint = self::PRODUCTION;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }
}
