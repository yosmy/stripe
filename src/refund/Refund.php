<?php

namespace Yosmy\Stripe;

use JsonSerializable;

class Refund implements JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $charge;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var int
     */
    private $created;

    /**
     * @param string $id
     * @param string $charge
     * @param int    $amount
     * @param int    $created
     */
    public function __construct(
        string $id,
        string $charge,
        int $amount,
        int $created
    ) {
        $this->id = $id;
        $this->charge = $charge;
        $this->amount = $amount;
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCharge(): string
    {
        return $this->charge;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getCreated(): int
    {
        return $this->created;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'charge' => $this->charge,
            'amount' => $this->amount,
            'created' => $this->created,
        ];
    }
}
