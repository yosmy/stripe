<?php

namespace Yosmy\Payment\Gateway\Stripe\Charge;

/**
 * @di\service()
 */
class PreparePayload
{
    /**
     * @param float  $amount
     * @param string $description Internal description
     * @param string $statement   User's credit card statement
     *
     * @return array
     */
    public function prepare(
        float $amount,
        string $description,
        string $statement
    ) {
        return [
            'amount' => $amount,
            'currency' => 'usd',
            'description' => $description,
            'statement_descriptor_suffix' => $statement,
        ];
    }
}
