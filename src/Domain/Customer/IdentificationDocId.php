<?php

declare(strict_types=1);

namespace Paynet\Domain\Customer;

class IdentificationDocId implements \JsonSerializable
{
    const CNPJ = '1';
    const CPF = '2';

    private string $identificationDocType;
    private string $identificationDocId;

    public function __construct(string $identificationDocId)
    {
        $identificationDocId = preg_replace('/\D/', '', $identificationDocId);

        $this->guardAgainstFromInvalidIdentificationDocId($identificationDocId);

        $this->identificationDocType = (strlen($identificationDocId) === 11) ? self::CPF : self::CNPJ;
        $this->identificationDocId = $identificationDocId;
    }

    public static function fromString(string $identificationDocId): self
    {
        return new self($identificationDocId);
    }

    public function docType(): string
    {
        return $this->identificationDocType;
    }

    public function docId(): string
    {
        return $this->identificationDocId;
    }

    public function jsonSerialize(): array
    {
        return [
            'documentType' => $this->identificationDocType,
            'documentNumberCDH' => $this->identificationDocId,
        ];
    }

    private function guardAgainstFromInvalidIdentificationDocId(string $identificationDocId): void
    {
        if (!preg_match('/^[0-9]{11,14}$/', $identificationDocId)) {
            throw new \UnexpectedValueException(
                sprintf('%s is not a valid documentNumberCDH. [0-9a-zA-Z]{11,14}', $identificationDocId)
            );
        }

        if (strlen($identificationDocId) === 11) {
            if (!$this->validateCPF($identificationDocId)) {
                throw new \UnexpectedValueException(sprintf("CPF (%s) is invalid.", $identificationDocId));
            }
        } else {
            if (!$this->validateCNPJ($identificationDocId)) {
                throw new \UnexpectedValueException(sprintf("CNPJ (%s) is invalid.", $identificationDocId));
            }
        }
    }

    /**
     * Check if is a valid CPF number
     * @param string $number
     *
     * @return bool
     */
    private function validateCPF(string $number)
    {
        // Extrai somente os números
        $cpf = preg_replace('/\D/', '', $number);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if is a valid CNPJ number
     * @param string $number
     *
     * @return bool
     */
    private function validateCNPJ(string $number)
    {
        $cnpj = preg_replace('/\D/', '', (string) $number);
        // Valida tamanho
        if (strlen($cnpj) != 14) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 11111111111111
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;

        return (bool) ($cnpj[13] == ($resto < 2 ? 0 : 11 - $resto));
    }
}
