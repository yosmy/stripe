<?php

namespace Yosmy\Payment\Gateway\Stripe;

use Yosmy\Payment\Gateway;

/**
 * @di\service()
 */
class RetrieveCard
{
    /**
     * @var ExecuteRequest
     */
    private $executeRequest;

    /**
     * @param ExecuteRequest $executeRequest
     */
    public function __construct(
        ExecuteRequest $executeRequest
    ) {
        $this->executeRequest = $executeRequest;
    }

    /**
     * @param string $customer
     * @param string $card
     *
     * @return Gateway\Card
     *
     * @throws Gateway\ApiException
     */
    public function retrieve(
        string $customer,
        string $card
    ) {
        try {
            $response = $this->executeRequest->execute(
                ExecuteRequest::METHOD_GET,
                sprintf('customers/%s/sources/%s', $customer, $card)
            );
        } catch (Gateway\ApiException $e) {
            throw $e;
        }

        return new Gateway\Card(
            $response['id'],
            $response['last4']
        );
    }
}