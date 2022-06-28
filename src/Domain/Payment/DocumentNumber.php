<?php

declare(strict_types=1);

namespace Paynet\Domain\Payment;

class DocumentNumber
{
    private string $documentNumber;

    public function __construct(string $documentNumber)
    {
        $documentNumber = preg_replace('/\D/', '', $documentNumber);

        $this->guardAgainstFromInvalidDocumentNumber($documentNumber);
 
        $this->documentNumber = $documentNumber;
    }

    public static function fromString(string $documentNumber): self
    {
        return new self($documentNumber);
    }

    public function doc(): string
    {
        return $this->documentNumber;
    }

    public function __toString(): string
    {
        return $this->documentNumber;
    }

    private function guardAgainstFromInvalidDocumentNumber(string $documentNumber): void
    {
        if (!preg_match('/^[0-9]{11,14}$/', $documentNumber)) {
            throw new \UnexpectedValueException(
                sprintf('%s is not a valid documentNumber. [0-9a-zA-Z]{11,14}', $documentNumber)
            );
        }

        if (strlen($documentNumber) === 11) {
            if (!$this->validateCPF($documentNumber)) {
                throw new \UnexpectedValueException(sprintf("CPF (%s) is invalid.", $documentNumber));
            }
        } else {
            if (!$this->validateCNPJ($documentNumber)) {
                throw new \UnexpectedValueException(sprintf("CNPJ (%s) is invalid.", $documentNumber));
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
