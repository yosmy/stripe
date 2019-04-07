<?php

namespace Yosmy\Stripe;

/**
 * @di\service()
 */
class RetrieveCharge
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
     * @param string $id
     *
     * @return Charge
     *
     * @throws ApiException
     */
    public function retrieve(
        string $id
    ) {
        try {
            $response = $this->executeRequest->execute(
                ExecuteRequest::METHOD_GET,
                sprintf('charges/%s', $id)
            );
        } catch (ApiException $e) {
            throw $e;
        }

        return new Charge(
            $response['id'],
            $response['customer'],
            $response['payment_method_details']['card']['fingerprint'],
            $response['payment_method_details']['card']['last4'],
            $response['amount'],
            $response['charge']
        );
    }
}