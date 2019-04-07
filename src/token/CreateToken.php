<?php

namespace Yosmy\Stripe;

use LogicException;

/**
 * @di\service()
 */
class CreateToken
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
     *     processExceptionServices: '#yosmy.stripe.create_token.exception_throwed',
     * })
     *
     * @param ExecuteRequest               $executeRequest
     * @param ProcessApiException[] $processExceptionServices
     */
    public function __construct(
        ExecuteRequest $executeRequest,
        ?array $processExceptionServices
    ) {
        $this->executeRequest = $executeRequest;
        $this->processExceptionServices = $processExceptionServices;
    }

    /**
     * @param string|null $name
     * @param string      $number
     * @param string      $month
     * @param string      $year
     * @param string      $cvc
     * @param string|null $zip
     *
     * @return Token
     *
     * @throws FieldException|FundsException|IssuerException|RiskException|FraudException
     */
    public function create(
        ?string $name,
        string $number,
        string $month,
        string $year,
        string $cvc,
        ?string $zip
    ) {
        $params = [
            'name' => $name,
            'number' => $number,
            'exp_month' => $month,
            'exp_year' => $year,
            'cvc' => $cvc
        ];

        if ($zip !== null) {
            $params['address_zip'] = $zip;
        }

        try {
            $response = $this->executeRequest->execute(
                ExecuteRequest::METHOD_POST,
            'tokens',
                [
                    'card' => $params
                ]
            );

            return new Token(
                $response['id'],
                new Card(
                    $response['card']['id'],
                    $response['card']['name'],
                    $response['card']['last4'],
                    $response['card']['fingerprint'],
                    $response['card']['country']
                )
            );
        } catch (ApiException $e) {
            foreach ($this->processExceptionServices as $processExceptionThrowedService) {
                try {
                    $processExceptionThrowedService->process($e);
                } catch (FieldException $e) {
                    throw $e;
                } catch (FundsException $e) {
                    throw $e;
                } catch (IssuerException $e) {
                    throw $e;
                } catch (RiskException $e) {
                    throw $e;
                } catch (FraudException $e) {
                    throw $e;
                }
            }

            throw new LogicException(null, null, $e);
        }
    }
}