<?php

namespace Yosmy\Payment\Gateway\Stripe;

use Yosmy\Payment\Gateway;
use LogicException;

/**
 * @di\service({
 *     tags: ['yosmy.payment.gateway.execute_charge']
 * })
 */
class ExecuteCharge implements Gateway\ExecuteCharge
{
    /**
     * @var Charge\PreparePayload
     */
    private $preparePayload;

    /**
     * @var ExecuteRequest
     */
    private $executeRequest;

    /**
     * @var Gateway\ProcessApiException[]
     */
    private $processExceptionServices;

    /**
     * @di\arguments({
     *     processExceptionServices: '#yosmy.payment.gateway.stripe.execute_charge.exception_throwed',
     * })
     *
     * @param Charge\PreparePayload         $preparePayload
     * @param ExecuteRequest                $executeRequest
     * @param Gateway\ProcessApiException[] $processExceptionServices
     */
    public function __construct(
        Charge\PreparePayload $preparePayload,
        ExecuteRequest $executeRequest,
        ?array $processExceptionServices
    ) {
        $this->preparePayload = $preparePayload;
        $this->executeRequest = $executeRequest;
        $this->processExceptionServices = $processExceptionServices;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(
        string $customer,
        string $card,
        int $amount,
        string $description,
        string $statement
    ) {
        $params = array_merge(
            [
                'customer' => $customer,
                'source' => $card
            ],
            $this->preparePayload->prepare(
                $amount,
                $description,
                $statement
            )
        );

        try {
            $response = $this->executeRequest->execute(
                ExecuteRequest::METHOD_POST,
                'charges',
                $params
            );

            return new Gateway\Charge(
                $response['id'],
                $response['created']
            );
        } catch (Gateway\ApiException $e) {
            foreach ($this->processExceptionServices as $service) {
                try {
                    $service->process($e);
                } catch (Gateway\FundsException|Gateway\IssuerException|Gateway\RiskException|Gateway\FraudException $e) {
                    throw $e;
                } catch (Gateway\FieldException $e) {
                    throw new LogicException(null, null, $e);
                }
            }

            throw new LogicException(null, null, $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function identify() {
        return 'stripe';
    }
}
