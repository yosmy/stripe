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
class ProcessFundsApiException implements ProcessApiException
{
    /**
     * {@inheritDoc}
     */
    public function process(ApiException $e)
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
                    'insufficient_funds',
                    'withdrawal_count_limit_exceeded'
                ]
            )
        ) {
            return;
        }

        throw new FundsException();
    }
}