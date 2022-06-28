<?php
namespace Paynet\Application;

use GuzzleHttp\Client;
use Paynet\Domain\Token;
use GuzzleHttp\Psr7\Response;

class PaynetService
{
    const SANDBOX = 'https://sandbox-cm.paynet.net.br';
    const PRODUCTION = 'https://cm.paynet.net.br';

    const API_VERSION = 'v2';
    const ROUTE = '1';

    private $email;

    private $password;

    private $token;

    private Client $client;

    public function __construct($email, $password, $isSandbox = true)
    {
        $this->email = $email;
        $this->password = $password;
        $this->environment = $isSandbox ? self::SANDBOX : self::PRODUCTION;
        $this->client = new Client([
            'base_uri' => $this->environment, 
        ]);
    }

    public function auth()
    {
        $url = '/login';
        $response = $this->request($url, 'AUTH');
        
        $tokenResponse = Token::createFromResponse($response);
        $this->token = $tokenResponse->getApiKey();

        return $tokenResponse;
    }

    protected function headers()
    {
        return [
            'Route' => self::ROUTE,
            'Version' => self::API_VERSION
        ];
    }

    protected function request($url, $type, $json = ''):Response
    {
        try {
            switch ($type) {
                case 'AUTH':
                    $response = $this->client->post($url, [
                        'body' => [
                            'email' => $this->email,
                            'password' => $this->password
                        ]
                    ]);
                    break;
                case 'POST':
                    $response = $this->client->post($url, [
                        'headers' => array_merge([
                            'Authorization' => sprintf("%s", $this->token),
                        ], $this->headers()),
                        'json' => $json
                    ]);
                    break;
                default:
                    //GET
                    $response = $this->client->get($url, [
                        'headers' => array_merge([
                            'Authorization' => sprintf("%s", $this->token),
                        ], $this->headers()),
                    ]);
                    break;
            }

            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new \Exception($e->getResponse()->getBody()->getContents());
        }
    }
}
