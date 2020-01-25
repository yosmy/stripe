<?php

namespace Yosmy\Payment\Gateway\Stripe;

use Yosmy\Payment\Gateway;

/**
 * @di\service()
 */
class CollectCharges
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
     * @return Gateway\Charge[]
     *
     * @throws Gateway\ApiException
     */
    public function collect(
        ?int $from,
        ?int $to
    ) {
        $allCharges = [];

        $charges = $this->collectStartingAfter(
            $from,
            $to,
            null
        );

        while (count($charges) > 0) {
            $allCharges = array_merge(
                $allCharges,
                $charges
            );

            $after = $charges[count($charges) - 1]->getId();

            $charges = $this->collectStartingAfter(
                $from,
                $to,
                $after
            );
        }

        return $allCharges;
    }

    /**
     * @param int    $from
     * @param int    $to
     * @param string $after
     *
     * @return Gateway\Charge[]
     *
     * @throws Gateway\ApiException
     */
    public function collectStartingAfter(
        ?int $from,
        ?int $to,
        ?string $after
    ) {
        $criteria = [];

        if ($from) {
            $criteria['created']['gte'] = $from;
        }

        if ($to) {
            $criteria['created']['lt'] = $to;
        }

        if ($after) {
            $criteria['starting_after'] = $after;
        }

        try {
            $response = $this->executeRequest->execute(
                ExecuteRequest::METHOD_GET,
                'charges',
                $criteria
            );
        } catch (Gateway\ApiException $e) {
            throw $e;
        }

        $charges = [];

        foreach ($response['data'] as $charge) {
            $charges[] = new Gateway\Charge(
                $charge['id'],
                $charge['created']
            );
        }

        return $charges;
    }
}