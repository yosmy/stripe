<?php

namespace Yosmy\Stripe;

/**
 * @di\service({
 *     tags: [
 *         'yosmy.stripe.execute_charge_with_token.exception_throwed',
 *         'yosmy.stripe.execute_charge_with_card.exception_throwed'
 *     ]
 * })
 */
class ProcessRiskApiException implements ProcessApiException
{
    /**
     * {@inheritDoc}
     */
    public function process(ApiException $e)
    {
        if (
            !isset($e->getResponse()['outcome'])
            || !isset($e->getResponse()['outcome']['risk_level'])
            || $e->getResponse()['outcome']['risk_level'] == 'normal'
        ) {
            return;
        }

        throw new RiskException();
    }
}