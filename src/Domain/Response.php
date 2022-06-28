<?php

declare(strict_types=1);

namespace Paynet\Domain;

use GuzzleHttp\Psr7\Response as PsrResponse;

class Response implements \JsonSerializable
{
    private string $description;

    private string $returnCode;

    private string $paymentId;

    private string $orderNumber;

    private string $authorizationCode;

    private string $nsu;

    private string $amount;

    private string $releaseAt;

    /**
     * @param array $payload
     *
     * @return Token
     */
    public function __construct(
        string $description,
        string $returnCode,
        string $paymentId,
        string $orderNumber,
        string $authorizationCode,
        string $nsu,
        string $amount,
        string $releaseAt
    ) {
        $this->$description = $description;
        $this->$returnCode = $returnCode;
        $this->$paymentId = $paymentId;
        $this->$orderNumber = $orderNumber;
        $this->$authorizationCode = $authorizationCode;
        $this->$nsu = $nsu;
        $this->$amount = $amount;
        $this->$releaseAt = $releaseAt;
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            $payload['description'] ?? '',
            $payload['returnCode'] ?? '',
            $payload['paymentId'] ?? '',
            $payload['orderNumber'] ?? '',
            $payload['authorizationCode'] ?? '',
            $payload['nsu'] ?? '',
            $payload['amount'] ?? '',
            $payload['releaseAt'] ?? '',
        );
    }

    public static function createFromResponse(PsrResponse $response): self
    {
        $content = $response->getBody()->getContents();
        $payload = json_decode($content, true);

        return self::fromArray($payload);
    }

    public function jsonSerialize(): array
    {
        return [
            'description' => (string) $this->description,
            'returnCode' => (string) $this->returnCode,
            'paymentId' => (string) $this->paymentId,
            'orderNumber' => (string) $this->orderNumber,
            'authorizationCode' => (string) $this->authorizationCode,
            'nsu' => (string) $this->nsu,
            'amount' => (string) $this->amount,
            'releaseAt' => (string) $this->releaseAt,
        ];
    }
}