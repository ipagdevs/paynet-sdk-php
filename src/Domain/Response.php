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

    private int $status;

    private string $date;

    public function __construct(
        string $description,
        string $returnCode,
        string $paymentId,
        string $orderNumber,
        string $authorizationCode,
        string $nsu,
        int $amount,
        string $releaseAt,
        int $status,
        string $date
    ) {
        $this->description = $description;
        $this->returnCode = $returnCode;
        $this->paymentId = $paymentId;
        $this->orderNumber = $orderNumber;
        $this->authorizationCode = $authorizationCode;
        $this->nsu = $nsu;
        $this->amount = $amount;
        $this->releaseAt = $releaseAt;
        $this->status = $status;
        $this->date = $date;
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
            $payload['amount'] ?? 0,
            $payload['releaseAt'] ?? '',
            $payload['status'] ?? 0,
            $payload['date'] ?? '',
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
            'status' => $this->status,
            'date' => (string) $this->date,
        ];
    }

    /**
     * Get the value of description
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * Get the value of returnCode
     */
    public function returnCode()
    {
        return $this->returnCode;
    }

    /**
     * Get the value of paymentId
     */
    public function paymentId()
    {
        return $this->paymentId;
    }

    /**
     * Get the value of orderNumber
     */
    public function orderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * Get the value of authorizationCode
     */
    public function authorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * Get the value of nsu
     */
    public function nsu()
    {
        return $this->nsu;
    }

    /**
     * Get the value of amount
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * Get the value of releaseAt
     */
    public function releaseAt()
    {
        return $this->releaseAt;
    }

    /**
     * Get the value of status
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * Get the value of date
     */
    public function date()
    {
        return $this->date;
    }
}
