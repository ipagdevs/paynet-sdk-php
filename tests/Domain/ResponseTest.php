<?php

declare(strict_types=1);

use Paynet\Domain\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testCanBeCreatedWithValidValues(): void
    {
        $this->assertInstanceOf(
            Response::class,
            Response::fromArray([
                "description" => "Sucesso",
                "returnCode" => "00",
                "paymentId" => "0acfa3e0-b411-4dc2-abb5-09d8ebd9fffb",
                "orderNumber" => "5",
                "authorizationCode" => "709685",
                "nsu" => "000000876313",
                "amount" => 1
            ])
        );
    }

    public function testCanBeRepresentedAsArray(): void
    {
        $this->assertSame([
            "description" => "Sucesso",
            "returnCode" => "00",
            "paymentId" => "0acfa3e0-b411-4dc2-abb5-09d8ebd9fffb",
            "orderNumber" => "5",
            "authorizationCode" => "709685",
            "nsu" => "000000876313",
            "amount" => 1,
            "releaseAt" => '',
            "status" => 0,
            "date" => '',
        ], Response::fromArray([
            "description" => "Sucesso",
            "returnCode" => "00",
            "paymentId" => "0acfa3e0-b411-4dc2-abb5-09d8ebd9fffb",
            "orderNumber" => "5",
            "authorizationCode" => "709685",
            "nsu" => "000000876313",
            "amount" => 1
        ])->jsonSerialize());
    }
}
