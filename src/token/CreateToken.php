<?php

namespace Yosmy\Payment\Gateway\Stripe;

use Yosmy\Payment\Gateway;

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
     * @param ExecuteRequest $executeRequest
     */
    public function __construct(
        ExecuteRequest $executeRequest
    ) {
        $this->executeRequest = $executeRequest;
    }

    /**
     * @param string $number
     * @param string $month
     * @param string $year
     * @param string $cvc
     *
     * @return Token
     *
     * @throws Gateway\ApiException
     */
    public function create(
        string $number,
        string $month,
        string $year,
        string $cvc
    ) {
        $params = [
            'number' => $number,
            'exp_month' => $month,
            'exp_year' => $year,
            'cvc' => $cvc
        ];

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
                new Gateway\Card(
                    $response['card']['id'],
                    $response['card']['last4']
                )
            );
        } catch (Gateway\ApiException $e) {
            throw $e;
        }
    }
}