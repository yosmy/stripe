<?php

namespace Yosmy\Stripe;

/**
 * @di\service()
 */
class DeleteCard
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
     * @throws ApiException
     */
    public function delete(
        string $customer,
        string $card
    ) {
        try {
            $this->executeRequest->execute(
                ExecuteRequest::METHOD_DELETE,
                sprintf('customers/%s/sources/%s', $customer, $card)
            );
        } catch (ApiException $e) {
            throw $e;
        }
    }
}