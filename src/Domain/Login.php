<?php

declare(strict_types=1);

namespace Paynet\Domain;

use Psr\Http\Message\ResponseInterface;

class Login
{
    const SUCCESS = 'success';

    private string $status;

    private string $apiKey;

    private ResponseInterface $response;

    /**
     * @param array $payload
     *
     * @return Login
     */
    public function __construct(string $apiKey, string $status)
    {
        $this->apiKey = $apiKey;
        $this->status = $status;
    }

    public static function fromArray(array $payload)
    {
        return new self(
            $payload['api_key'] ?? '',
            $payload['status'],
        );
    }

    public static function createFromResponse(ResponseInterface $response): self
    {
        $content = $response->getBody()->getContents();
        $payload = json_decode($content, true);

        $instance = self::fromArray($payload);
        $instance->setResponse($response);

        return $instance;
    }

    protected function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function isValidLogin(): bool
    {
        return $this->status == self::SUCCESS;
    }

    public function __toString(): string
    {
        return $this->apiKey;
    }

    public function response(): ResponseInterface
    {
        return $this->response;
    }
}
