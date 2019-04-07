<?php

namespace Yosmy\Stripe;

/**
 * @di\service({
 *     private: true
 * })
 */
class RefundCharge
{
    /**
     * @var ExecuteRequest
     */
    private $executeRequest;

    /**
     * @param ExecuteRequest  $executeRequest
     */
    public function __construct(
        ExecuteRequest $executeRequest
    ) {
        $this->executeRequest = $executeRequest;
    }

    /**
     * @param string $id
     *
     * @throws ApiException
     */
    public function refund(
        string $id
    ) {
        try {
            $this->executeRequest->execute(
                ExecuteRequest::METHOD_POST,
                'refunds',
                [
                    'charge' => $id
                ]
            );
        } catch (ApiException $e) {
            throw $e;
        }
    }
}
