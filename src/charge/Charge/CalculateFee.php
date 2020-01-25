<?php

namespace Yosmy\Payment\Gateway\Stripe\Charge;

/**
 * @di\service()
 */
class CalculateFee
{
    /**
     * See https://support.stripe.com/questions/can-i-charge-my-stripe-fees-to-my-customers
     *
     * @param float $amount
     *
     * @return float
     */
    public function calculate($amount)
    {
        $amountWithFee = ($amount + 0.30) / (1 - 0.029);

        return round($amountWithFee - $amount, 2);
    }
}
