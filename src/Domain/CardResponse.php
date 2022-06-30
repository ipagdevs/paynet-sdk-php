<?php

declare(strict_types=1);

namespace Paynet\Domain;

use Psr\Http\Message\ResponseInterface;

class CardResponse implements \JsonSerializable
{
    const VAULT = 'vault';
    const TOKEN = 'token';

    private string $token;

    private string $type;

    private ResponseInterface $response;

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

    public static function createFromResponse(ResponseInterface $response): self
    {
        $content = $response->getBody()->getContents();
        $payload = json_decode($content, true);

        if (!is_array($payload)) {
            throw new \UnexpectedValueException('Error!');
        }

        if (array_key_exists('data', $payload)) {
            $token = $payload['data']['vaultId'];
            $type = self::VAULT;
        }

        if (array_key_exists('numberToken', $payload)) {
            $token = $payload['numberToken'];
            $type = self::TOKEN;
        }

        $instance = new self($token, $type);
        $instance->setResponse($response);

        return $instance;
    }

    protected function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    public function jsonSerialize(): array
    {
        return [
            'token' => (string) $this->token,
            'type' => (string) $this->type,
        ];
    }

    public function response(): ResponseInterface
    {
        return $this->response;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function token(): string
    {
        return $this->token;
    }
}
