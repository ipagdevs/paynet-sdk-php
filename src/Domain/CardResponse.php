<?php

declare(strict_types=1);

namespace Paynet\Domain;

use GuzzleHttp\Psr7\Response as PsrResponse;

class CardResponse implements \JsonSerializable
{
    const VAULT = 'vault';
    const TOKEN = 'token';

    private string $token;

    private string $type;

    /**
     * @param array $payload
     *
     * @return self
     */
    public function __construct($token, $type)
    {
        $this->token = $token;
        $this->type = $type;
    }

    public static function createFromResponse(PsrResponse $response): self
    {
        $content = $response->getBody()->getContents();
        $payload = json_decode($content, true);

        if (array_key_exists('vaultId', $payload)) {
            $token = $payload['vaultId'];
            $type = self::VAULT;
        }

        if (array_key_exists('numberToken', $payload)) {
            $token = $payload['numberToken'];
            $type = self::TOKEN;
        }

        return new self($token, $type);
    }

    public function jsonSerialize(): array
    {
        return [
            'token' => (string) $this->token,
            'type' => (string) $this->type,
        ];
    }
}
