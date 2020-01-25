<?php

namespace Yosmy\Payment\Gateway\Stripe;

use Yosmy\Payment\Gateway;
use LogicException;

/**
 * @di\service({
 *     tags: ['yosmy.payment.gateway.add_card']
 * })
 */
class AddCard implements Gateway\AddCard
{
    /**
     * @var CreateToken
     */
    private $createToken;
    
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
     *     processExceptionServices: '#yosmy.payment.gateway.stripe.add_card.exception_throwed',
     * })
     *
     * @param CreateToken                   $createToken
     * @param ExecuteRequest                $executeRequest
     * @param Gateway\ProcessApiException[] $processExceptionServices
     */
    public function __construct(
        CreateToken $createToken,
        ExecuteRequest $executeRequest,
        array $processExceptionServices
    ) {
        $this->createToken = $createToken;
        $this->executeRequest = $executeRequest;
        $this->processExceptionServices = $processExceptionServices;
    }

    /**
     * {@inheritDoc}
     */
    public function add(
        string $customer,
        string $number,
        string $month,
        string $year,
        string $cvc
    ) {
        try {
            $token = $this->createToken->create(
                $number,
                $month,
                $year,
                $cvc
            );
        } catch (Gateway\ApiException $e) {
            foreach ($this->processExceptionServices as $service) {
                try {
                    $service->process($e);
                } catch (Gateway\FieldException $e) {
                    throw $e;
                } catch (Gateway\FundsException $e) {
                    throw $e;
                } catch (Gateway\IssuerException $e) {
                    throw $e;
                } catch (Gateway\RiskException $e) {
                    throw $e;
                } catch (Gateway\FraudException $e) {
                    throw $e;
                }
            }

            throw new LogicException(null, null, $e);
        }

        try {
            $response = $this->executeRequest->execute(
                ExecuteRequest::METHOD_POST,
                sprintf('customers/%s/sources', $customer),
                [
                    'source' => $token->getId()
                ]
            );
        } catch (Gateway\ApiException $e) {
            foreach ($this->processExceptionServices as $service) {
                try {
                    $service->process($e);
                } catch (Gateway\FieldException|Gateway\IssuerException|Gateway\RiskException|Gateway\FraudException $e) {
                    throw $e;
                } catch (Gateway\FundsException $e) {
                    throw new LogicException(null, null, $e);
                }
            }

            throw new LogicException(
                $e->getResponse()['message'],
                null,
                $e
            );
        }

        return new Gateway\Card(
            $response['id'],
            $response['last4']
        );
    }

    /**
     * {@inheritDoc}
     */
    public function identify() {
        return 'stripe';
    }
}