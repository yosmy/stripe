<?php

namespace Yosmy\Stripe;

/**
 * @di\service()
 */
class AddCustomer
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
     * @return Customer
     *
     * @throws ApiException
     */
    public function add()
    {
        try {
            $response = $this->executeRequest->execute(
                ExecuteRequest::METHOD_POST,
                'customers'
            );

            return new Customer(
                $response['id']
            );
        } catch (ApiException $e) {
            throw $e;
        }
    }
}