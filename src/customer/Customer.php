<?php

namespace Yosmy\Stripe;

use JsonSerializable;

class Customer implements JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    public function __construct(
        string $id
    ) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
        ];
    }
}
