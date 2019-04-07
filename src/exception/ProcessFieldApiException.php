<?php

namespace Yosmy\Stripe;

/**
 * @di\service({
 *     tags: [
 *         'yosmy.stripe.create_token.exception_throwed',
 *         'yosmy.stripe.add_card.exception_throwed'
 *     ]
 * })
 */
class ProcessFieldApiException implements ProcessApiException
{
    /**
     * {@inheritDoc}
     */
    public function process(ApiException $e)
    {
        if ($e->getResponse()['type'] != 'card_error') {
            return;
        }

        $code = $e->getResponse()['code'];

        switch ($code) {
            case 'incorrect_number':
            case 'invalid_number':
                $field = 'number';

                break;
            case 'invalid_expiry_month':
                $field = 'month';

                break;
            case 'invalid_expiry_year':
                $field = 'year';

                break;
            case 'invalid_cvc':
            case 'incorrect_cvc':
                $field = 'cvc';

                break;
            case 'incorrect_zip':
                $field = 'zip';

                break;
            default:
                return;
        }

        throw new FieldException($field);
    }
}