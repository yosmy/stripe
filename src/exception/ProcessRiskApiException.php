<?php

namespace Yosmy\Payment\Gateway\Stripe;

use Yosmy\Payment\Gateway;

/**
 * @di\service({
 *     tags: [
 *         'yosmy.payment.gateway.stripe.execute_charge.exception_throwed'
 *     ]
 * })
 */
class ProcessRiskApiException implements Gateway\ProcessApiException
{
    /**
     * {@inheritDoc}
     */
    public function process(Gateway\ApiException $e)
    {
        if (
            !isset($e->getResponse()['outcome'])
            || !isset($e->getResponse()['outcome']['risk_level'])
            || $e->getResponse()['outcome']['risk_level'] == 'normal'
        ) {
            return;
        }

        throw new Gateway\RiskException();
    }
}