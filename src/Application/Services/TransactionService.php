<?php
namespace Paynet\Application;

use Paynet\Domain\Response;
use Paynet\Domain\Operation;
use Paynet\Domain\Transaction;

class TransactionService extends PaynetService
{
    public function authorize(Transaction $payload)
    {
        $response = $this->request('/financial', 'POST', $payload);

        return Response::createFromResponse($response);
    }
    
    public function capture(Operation $payload)
    {
        $response = $this->request("/capture", 'POST', $payload);

        return Response::createFromResponse($response);
    }

    public function cancel(Operation $payload)
    {
        $response = $this->request("/cancel", 'POST', $payload);

        return Response::createFromResponse($response);
    }

    public function consult(string $orderNumber)
    {
        $response = $this->request("/getTransaction", 'POST', ['orderNumber' => $orderNumber]);

        return Response::createFromResponse($response);
    }
}