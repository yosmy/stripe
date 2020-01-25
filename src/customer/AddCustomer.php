<?php

namespace Yosmy\Payment\Gateway\Stripe;

use Yosmy\Payment\Gateway;

/**
 * @di\service({
 *     tags: ['yosmy.payment.gateway.add_customer']
 * })
 */
class AddCustomer implements Gateway\AddCustomer
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
     * {@inheritDoc}
     */
    public function add()
    {
        try {
            $response = $this->executeRequest->execute(
                ExecuteRequest::METHOD_POST,
                'customers',
                []
            );

            return new Gateway\Customer(
                $response['id']
            );
        } catch (Gateway\ApiException $e) {
            throw $e;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function identify() {
        return 'stripe';
    }
}