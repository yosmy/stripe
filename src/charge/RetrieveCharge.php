<?php

namespace Yosmy\Payment\Gateway\Stripe;

use Yosmy\Payment\Gateway;

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
     * @return Gateway\Charge
     *
     * @throws Gateway\ApiException
     */
    public function retrieve(
        string $id
    ) {
        try {
            $response = $this->executeRequest->execute(
                ExecuteRequest::METHOD_GET,
                sprintf('charges/%s', $id)
            );
        } catch (Gateway\ApiException $e) {
            throw $e;
        }

        return new Gateway\Charge(
            $response['id'],
            $response['created']
        );
    }
}