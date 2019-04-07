<?php

namespace Yosmy\Stripe;

use Exception as BaseException;

class ApiException extends BaseException
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @param array $response
     */
    public function __construct(
        array $response
    ) {
        parent::__construct();

        $this->response = $response;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }
}