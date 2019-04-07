<?php

namespace Yosmy\Stripe;

use LogicException;

/**
 * @di\service()
 */
class ExecuteCharge
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
     * @var ProcessApiException[]
     */
    private $processExceptionServices;

    /**
     * @di\arguments({
     *     processExceptionServices: '#yosmy.stripe.execute_charge_with_card.exception_throwed',
     * })
     *
     * @param Charge\PreparePayload $preparePayload
     * @param ExecuteRequest           $executeRequest
     * @param ProcessApiException[] $processExceptionServices
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
     * @param string $customer
     * @param string $card
     * @param int    $amount // In cents @see https://stripe.com/docs/currencies#zero-decimal
     * @param string $description Internal description
     * @param string $statement   User's credit card statement
     *
     * @return Charge
     * 
     * @throws FundsException|IssuerException|RiskException|FraudException
     */
    public function executeWithCard(
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
            $response = $this->execute(
                $params
            );

            return new Charge(
                $response['id'],
                $response['customer'],
                $response['payment_method_details']['card']['fingerprint'],
                $response['payment_method_details']['card']['last4'],
                $response['amount'],
                $response['created']
            );
        } catch (ApiException $e) {
            foreach ($this->processExceptionServices as $processExceptionThrowedService) {
                try {
                    $processExceptionThrowedService->process($e);
                } catch (FieldException $e) {
                    throw new LogicException(null, null, $e);
                } catch (FundsException|IssuerException|RiskException|FraudException $e) {
                    throw $e;
                }
            }

            throw new LogicException(null, null, $e);
        }
    }

    /**
     * @param string $token
     * @param int    $amount // In cents @see https://stripe.com/docs/currencies#zero-decimal
     * @param string $description Internal description
     * @param string $statement   User's credit card statement
     *
     * @return Charge
     *
     * @throws FundsException|IssuerException|RiskException|FraudException
     */
    public function executeWithToken(
        string $token,
        int $amount,
        string $description,
        string $statement
    ) {
        $params = array_merge(
            [
                'source' => $token
            ],
            $this->preparePayload->prepare(
                $amount,
                $description,
                $statement
            )
        );

        try {
            $response = $this->execute(
                $params
            );

            return new Charge(
                $response['id'],
                $response['customer'],
                $response['payment_method_details']['card']['fingerprint'],
                $response['payment_method_details']['card']['last4'],
                $response['amount'],
                $response['created']
            );
        } catch (ApiException $e) {
            foreach ($this->processExceptionServices as $processExceptionThrowedService) {
                try {
                    $processExceptionThrowedService->process($e);
                } catch (FieldException $e) {
                    throw new LogicException(null, null, $e);
                } catch (FundsException|IssuerException|RiskException|FraudException $e) {
                    throw $e;
                }
            }

            throw new LogicException(null, null, $e);
        }
    }

    /**
     * @param array $payload
     *
     * @return array
     *
     * @throws ApiException
     */
    private function execute(
        array $payload
    ) {
        try {
            $response = $this->executeRequest->execute(
                ExecuteRequest::METHOD_POST,
                'charges',
                $payload
            );
        } catch (ApiException $e) {
            throw $e;
        }

        return $response;
    }
}
