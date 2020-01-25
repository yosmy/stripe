<?php

namespace Yosmy\Payment\Gateway\Stripe;

use Yosmy\Payment\Gateway;

/**
 * @di\service()
 */
class CollectRefunds
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
     * @param int $from
     * @param int $to
     *
     * @return Refund[]
     *
     * @throws Gateway\ApiException
     */
    public function collect(
        ?int $from,
        ?int $to
    ) {
        $criteria = [];

        if ($from) {
            $criteria['created']['gte'] = $from;
        }

        if ($to) {
            $criteria['created']['lt'] = $to;
        }

        try {
            $response = $this->executeRequest->execute(
                ExecuteRequest::METHOD_GET,
                'refunds',
                $criteria
            );
        } catch (Gateway\ApiException $e) {
            throw $e;
        }

        $refunds = [];

        foreach ($response['data'] as $refund) {
            $refunds[] = new Refund(
                $refund['id'],
                $refund['charge'],
                $refund['amount'],
                $refund['created']
            );
        }

        return $refunds;
    }
}