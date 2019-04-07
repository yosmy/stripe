<?php

namespace Yosmy\Stripe;

use LogicException;

/**
 * @di\service()
 */
class CreateCard
{
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
     *     processExceptionServices: '#yosmy.stripe.add_card.exception_throwed',
     * })
     *
     * @param ExecuteRequest        $executeRequest
     * @param ProcessApiException[] $processExceptionServices
     */
    public function __construct(
        ExecuteRequest $executeRequest,
        array $processExceptionServices
    ) {
        $this->executeRequest = $executeRequest;
        $this->processExceptionServices = $processExceptionServices;
    }

    /**
     * @param string $customer
     * @param string $token
     *
     * @return Card
     *
     * @throws FieldException|IssuerException|RiskException|FraudException
     */
    public function create(
        string $customer,
        string $token
    ) {
        try {
            $response = $this->executeRequest->execute(
                ExecuteRequest::METHOD_POST,
                sprintf('customers/%s/sources', $customer),
                [
                    'source' => $token
                ]
            );
        } catch (ApiException $e) {
            foreach ($this->processExceptionServices as $processExceptionThrowedService) {
                try {
                    $processExceptionThrowedService->process($e);
                } catch (FieldException|IssuerException|RiskException|FraudException $e) {
                    throw $e;
                } catch (FundsException $e) {
                    throw new LogicException(null, null, $e);
                }
            }

            throw new LogicException(
                $e->getResponse()['message'],
                null,
                $e
            );
        }

        return new Card(
            $response['id'],
            $response['name'],
            $response['last4'],
            $response['fingerprint'],
            $response['country']
        );
    }
}