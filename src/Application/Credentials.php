<?php

declare(strict_types=1);

namespace Paynet\Application;

use Jhernandes\Contacts\Domain\Email;

class Credentials implements \JsonSerializable
{
    private Email $email;
    private string $password;
    private string $apiKey;

    public function __construct($email, $password)
    {
        $this->email = Email::fromString($email);
        $this->ensureIsValidPassword($password);
        $this->password = $password;
    }

    public function jsonSerialize(): array
    {
        return [
            'email' => (string) $this->email,
            'password' => (string) $this->password
        ];
    }

    public function apiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey($apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    protected function ensureIsValidPassword(string $password): void
    {
        if (empty($password)) {
            throw new \UnexpectedValueException(sprintf('%s is not valid password', $password));
        }
    }
}
