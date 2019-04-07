<?php

namespace Yosmy\Stripe;

use Exception;

class FieldException extends Exception
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @param string $field
     */
    public function __construct(
        string $field
    ) {
        parent::__construct();

        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }
}