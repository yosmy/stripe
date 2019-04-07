<?php

namespace Yosmy\Stripe;

interface ProcessApiException
{
    /**
     * @param ApiException $e
     *
     * @throws FieldException|FundsException|IssuerException|RiskException|FraudException
     */
    public function process(ApiException $e);
}
