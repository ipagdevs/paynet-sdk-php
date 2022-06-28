<?php

declare(strict_types=1);

namespace Paynet\Domain;

use GuzzleHttp\Psr7\Response;

class Token
{
    const SUCCESS = 'success';

    private string $status;

    private string $apiKey;

    /**
     * @param array $payload
     *
     * @return Token
     */
    public function __construct(string $apiKey, string $status)
    {
        $this->apiKey = $apiKey;
        $this->status = $status;
    }

    public static function fromArray(array $payload)
    {
        return new self(
            $payload['api_key'],
            $payload['status'],
        );
    }

    public static function createFromResponse(Response $response)
    {
        $content = $response->getBody()->getContents();
        $payload = json_decode($content, true);

        return self::fromArray($payload);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function isValidToken()
    {
        return $this->status == self::SUCCESS;
    }

    public function __toString()
    {
        return $this->apiKey;
    }
}
