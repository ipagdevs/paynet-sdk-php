<?php

declare(strict_types=1);

namespace Paynet\Domain\Payment;

use Paynet\Domain\Payment\DocumentNumber;

class Payment implements \JsonSerializable
{
    const TRANSACTION_TYPE = 1;
    const A_VISTA = 1;
    const PARCELADO = 4;
    const AUTH_AND_CAPTURE = 1;
    const PRE_AUTH = 2;
    const CURRENCY_BRL = 986;

    private DocumentNumber $documentNumber;
    private int $transactionType;
    private int $amount;
    private int $currencyCode;
    private int $installments;
    private bool $recurrent;

    public function __construct(string $documentNumber, float $amount, int $installments, int $captureType, bool $recurrent = false)
    {
        $this->documentNumber = DocumentNumber::fromString($documentNumber);
        $this->transactionType = self::TRANSACTION_TYPE;

        $this->ensureIsValidAmount($amount);
        $this->amount = (int) number_format($amount * 100, 0, '', '');

        $this->ensureIsValidInstallment($installments);
        $this->installments = $installments;

        $this->ensureIsValidCaptureType($captureType);
        $this->captureType = $captureType;

        $this->currencyCode = self::CURRENCY_BRL;
        $this->recurrent = $recurrent;
        $this->productType = $installments > 1 ? self::PARCELADO : self::A_VISTA;
    }

    public static function fromValues(string $documentNumber, float $amount, int $installments, int $captureType, bool $recurrent = false): self
    {
        return new self(
            $documentNumber,
            $amount,
            $installments,
            $captureType,
            $recurrent
        );
    }

    public function jsonSerialize(): array
    {
        $response = [
            'documentNumber' => (string) $this->documentNumber->doc(),
            'transactionType' => $this->transactionType,
            'amount' => $this->amount,
            'currencyCode' => $this->currencyCode,
            'productType' => $this->productType,
            'installments' => $this->installments,
            'captureType' => $this->captureType,
            'recurrent' => $this->recurrent,
        ];

        if (isset($this->address)) {
            $address = $this->address->jsonSerialize();
            $response['address'] = sprintf("%s %s", $address['street'], $address['number']);
            $response['complement'] = $address['complement'];
            $response['city'] = $address['city'];
            $response['state'] = $address['state'];
            $response['zipCode'] = $address['cep'];
        }

        return $response;
    }

    private function ensureIsValidAmount(float $amount): void
    {
        if (!is_numeric($amount) || $amount <= 0) {
            throw new \UnexpectedValueException(sprintf('%s is not valid amount', $amount));
        }
    }

    private function ensureIsValidInstallment(int $installment): void
    {
        if (!is_numeric($installment) || $installment < 1 || $installment > 18) {
            throw new \UnexpectedValueException(sprintf('%s is not valid installment', $installment));
        }
    }

    private function ensureIsValidCaptureType(int $captureType): void
    {
        $validCaptureTypes = [self::AUTH_AND_CAPTURE, self::PARCELADO];
        if (!in_array($captureType, $validCaptureTypes)) {
            throw new \UnexpectedValueException(sprintf('%s is not valid capture type', $captureType));
        }
    }
}
