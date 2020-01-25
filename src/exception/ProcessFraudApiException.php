<?php

namespace Yosmy\Payment\Gateway\Stripe;

use Yosmy\Payment\Gateway;

/**
 * @di\service({
 *     tags: [
 *         'yosmy.payment.gateway.stripe.add_card.exception_throwed',
 *         'yosmy.payment.gateway.stripe.execute_charge.exception_throwed'
 *     ]
 * })
 */
class ProcessFraudApiException implements Gateway\ProcessApiException
{
    /**
     * {@inheritDoc}
     */
    public function process(Gateway\ApiException $e)
    {
        if ($e->getResponse()['type'] != 'card_error') {
            return;
        }

        if ($e->getResponse()['code'] != 'card_declined') {
            return;
        }

        $declined = $e->getResponse()['decline_code'];

        if (
            !in_array(
                $declined,
                [
                    'fraudulent',
                    'lost_card',
                    'stolen_card'
                ]
            )
        ) {
            return;
        }

        throw new Gateway\FraudException();
    }
}