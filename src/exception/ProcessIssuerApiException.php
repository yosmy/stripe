<?php

namespace Yosmy\Stripe;

/**
 * @di\service({
 *     tags: [
 *         'yosmy.stripe.add_card.exception_throwed',
 *         'yosmy.stripe.execute_charge_with_token.exception_throwed',
 *         'yosmy.stripe.execute_charge_with_card.exception_throwed'
 *     ]
 * })
 */
class ProcessIssuerApiException implements ProcessApiException
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
                    'approve_with_id', 'call_issuer', 'card_not_supported', 'card_velocity_exceeded',
                    'currency_not_supported', 'do_not_honor', 'expired_card', 'generic_decline', 'invalid_account',
                    'invalid_amount', 'issuer_not_available', 'issuer_not_available', 'new_account_information_available',
                    'no_action_taken', 'not_permitted', 'pickup_card', 'reenter_transaction', 'restricted_card',
                    'revocation_of_all_authorizations', 'revocation_of_authorization', 'security_violation',
                    'service_not_allowed', 'stop_payment_order', 'transaction_not_allowed', 'try_again_later'
                ]
            )
        ) {
            return;
        }

        throw new IssuerException();
    }
}