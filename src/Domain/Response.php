<?php

declare(strict_types=1);

namespace Paynet\Domain;

use Psr\Http\Message\ResponseInterface;

class Response implements \JsonSerializable
{
    private string $description;

    private string $returnCode;

    private string $paymentId;

    private string $orderNumber;

    private string $authorizationCode;

    private string $nsu;

    private int $amount;

    private string $releaseAt;

    public function __construct(
        string $description,
        string $returnCode,
        string $paymentId,
        string $orderNumber,
        string $authorizationCode,
        string $nsu,
        int $amount,
        string $releaseAt
    ) {
        $this->description = $description;
        $this->returnCode = $returnCode;
        $this->paymentId = $paymentId;
        $this->orderNumber = $orderNumber;
        $this->authorizationCode = $authorizationCode;
        $this->nsu = $nsu;
        $this->amount = $amount;
        $this->releaseAt = $releaseAt;
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

    public static function createFromResponse(ResponseInterface $response): self
    {
        $content = $response->getBody()->getContents();
        $payload = json_decode($content, true);

        if (!is_array($payload)) {
            throw new \Exception('Error!');
        }
        if ($response->getStatusCode() >= 400) {
            throw new \UnexpectedValueException($payload['errors']['0']['description']);
        }
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
            'amount' => $this->amount,
            'releaseAt' => (string) $this->releaseAt,
        ];
    }
}
