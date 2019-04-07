<?php

namespace Yosmy\Stripe;

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
     * @return Card
     *
     * @throws ApiException
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
        } catch (ApiException $e) {
            throw $e;
        }

        return new Card(
            $response['id'],
            $response['name'],
            $response['last4'],
            $response['fingerprint'],
            $response['country']
        );
    }
}