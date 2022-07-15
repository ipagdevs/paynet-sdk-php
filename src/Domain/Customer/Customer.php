<?php

declare(strict_types=1);

namespace Paynet\Domain\Customer;

use Jhernandes\BrazilianAddress\Domain\Address;
use Jhernandes\Person\Domain\Name;
use Jhernandes\Contacts\Domain\Email;
use Jhernandes\Contacts\Domain\Phone;

class Customer implements \JsonSerializable
{
    private Name $name;
    private IdentificationDocId $identificationDocId;
    private Phone $phone;
    private Email $email;
    private Address $address;
    private Ip $ip;

    public function __construct(string $name, string $document)
    {
        $this->name = Name::fromString($name);
        $this->identificationDocId = IdentificationDocId::fromString($document);
    }

    public static function fromValues(string $name, string $document): self
    {
        return new self($name, $document);
    }

    public function setEmail(string $email): void
    {
        $this->email = Email::fromString($email);
    }

    public function setPhone(string $phone): void
    {
        $this->phone = Phone::fromString($phone);
    }

    public function setIp(string $ip): void
    {
        $this->ip = Ip::fromString($ip);
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function setAddressFromString(string $address): void
    {
        $this->address = Address::fromString($address);
    }

    public function jsonSerialize(): array
    {
        $phone = (string) (isset($this->phone) ? preg_replace('/\D/', '', $this->phone->__toString()) : null);
        $response = [
            'documentType' => (string) $this->identificationDocId->docType(),
            'documentNumberCDH' => (string) $this->identificationDocId->docId(),
            'firstName' => $this->name->firstname(),
            'lastName' => $this->name->lastname(),
            'email' => (string) (isset($this->email) ? $this->email : null),
            'phoneNumber' => strlen($phone) == 11 ? $phone : '',
            'ipAddress' => (string) (isset($this->ip) ? $this->ip : null),
            'country' => 'BRA',
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
}
