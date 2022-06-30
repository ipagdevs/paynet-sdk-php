<?php

declare(strict_types=1);

namespace Paynet\Application\Http;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\BadResponseException;
use Paynet\Application\Credentials;
use Paynet\Application\Environment;

class Request
{
    const API_VERSION = 'v2';
    const ROUTE = '1';

    private Credentials $credentials;
    private Environment $environment;
    private Client $client;

    public function __construct(Credentials $credentials, Environment $environment)
    {
        $this->environment = $environment;
        $this->credentials = $credentials;
        $this->client = new Client([
            'base_uri' => (string) $this->environment,
        ]);
    }

    public static function defaultHeaders()
    {
        return [
            'Route' => self::ROUTE,
            'Version' => self::API_VERSION
        ];
    }

    public function setApiKey(string $apiKey): void
    {
        $this->credentials->setApiKey($apiKey);
    }

    public function apiKey(): string
    {
        return $this->credentials->apiKey();
    }

    public function post($url, $body, $headers): ResponseInterface
    {
        try {
            return $this->client->post($url, [
                'headers' => $headers,
                'json' => $body
            ]);
        } catch (BadResponseException $e) {
            return $e->getResponse();
        }
    }

    public function get($url, $headers): ResponseInterface
    {
        try {
            return $this->client->get($url, [
                'headers' => array_merge([
                    'Authorization' => sprintf("%s", $this->credentials->apiKey()),
                ], $headers),
            ]);
        } catch (BadResponseException $e) {
            return $e->getResponse();
        }
    }
}
