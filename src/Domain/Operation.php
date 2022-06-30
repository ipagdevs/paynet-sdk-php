<?php

declare(strict_types=1);

namespace Paynet\Domain;

use Paynet\Domain\Payment\DocumentNumber;

class Operation implements \JsonSerializable
{
    const CANCEL = 'cancel';
    const CAPTURE = 'capture';

    private DocumentNumber $documentNumber;

    private int $amount;

    private string $paymentId;

    private string $operationType;

    public function __construct(string $operation, string $documentNumber, float $amount, string $paymentId)
    {
        $this->ensureIsValidOperation($operation);
        $this->operationType = $operation;

        $this->documentNumber = DocumentNumber::fromString($documentNumber);

        $this->ensureIsValidAmount($amount);
        $this->amount = (int) number_format($amount * 100, 0, '', '');

        $this->ensureIsValidPaymentId($paymentId);
        $this->paymentId = $paymentId;
    }

    public static function capture(string $documentNumber, float $amount, string $paymentId): self
    {
        return new self(
            self::CAPTURE,
            $documentNumber,
            $amount,
            $paymentId
        );
    }

    public static function cancel(string $documentNumber, float $amount, string $paymentId): self
    {
        return new self(
            self::CANCEL,
            $documentNumber,
            $amount,
            $paymentId
        );
    }

    private function ensureIsValidAmount(float $amount): void
    {
        if (!is_numeric($amount) || $amount <= 0) {
            throw new \UnexpectedValueException(sprintf('%s is not valid amount', $amount));
        }
    }

    private function ensureIsValidPaymentId(string $paymentId): void
    {
        if (empty($paymentId)) {
            throw new \UnexpectedValueException(sprintf('PaymentID is empty!', $paymentId));
        }
    }

    private function ensureIsValidOperation(string $operation): void
    {
        $validOperation = [self::CAPTURE, self::CANCEL];
        if (!in_array($operation, $validOperation)) {
            throw new \UnexpectedValueException(sprintf('%s is not valid operation type', $operation));
        }
    }

    /**
     * Get the value of operationType
     */
    public function getOperation()
    {
        return $this->operationType;
    }

    public function jsonSerialize(): array
    {
        return [
            "documentNumber" => (string) $this->documentNumber,
            "paymentId" => $this->paymentId,
            "amount" => $this->amount,
        ];
    }
}
