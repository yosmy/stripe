<?php

namespace Yosmy\Stripe;

use Yosmy\Http\Exception;
use Yosmy\Http;

/**
 * @di\service({
 *     private: true
 * })
 */
class ExecuteRequest
{
    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    const METHOD_DELETE = 'delete';

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var Http\ExecuteRequest
     */
    private $executeRequest;

    /**
     * @var Request\LogEvent
     */
    private $logEvent;

    /**
     * @di\arguments({
     *     secretKey: "%stripe_secret_key%"
     * })
     *
     * @param string              $secretKey
     * @param Http\ExecuteRequest $executeRequest
     * @param Request\LogEvent    $logEvent
     */
    public function __construct(
        string $secretKey,
        Http\ExecuteRequest $executeRequest,
        Request\LogEvent $logEvent
    ) {
        $this->secretKey = $secretKey;
        $this->executeRequest = $executeRequest;
        $this->logEvent = $logEvent;
    }

    /**
     * @param string     $method
     * @param string     $uri
     * @param array|null $params
     *
     * @return array
     *
     * @throws ApiException
     */
    public function execute(
        string $method,
        string $uri,
        array $params = []
    ) {
        $request = [
            'method' => $method,
            'uri' => $uri,
            'params' => $params
        ];

        $options = [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'method' => $method,
                'uri' => sprintf('https://api.stripe.com/v1/%s', $uri),
                'options' => [
                    'auth' => [
                        $this->secretKey,
                        '' // No need to set password
                    ],
                    'form_params' => $params
                ]
            ]
        ];
        $method = 'POST';
        $uri = 'https://api.mundorecarga.com/forward-request';

        try {
            $response = $this->executeRequest->execute(
                $method,
                $uri,
                $options
            );

            $response = $response->getBody();

            $this->logEvent->log(
                $request,
                $response
            );

            return $response;
        } catch (Exception $e) {
            $response = $e->getResponse()['error'];

            $this->logEvent->log(
                $request,
                $response
            );

            throw new ApiException($response);
        }
    }
}
