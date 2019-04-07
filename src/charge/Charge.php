<?php

namespace Yosmy\Stripe;

use JsonSerializable;

class Charge implements JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $customer;

    /**
     * @var string
     */
    private $fingerprint;

    /**
     * @var string
     */
    private $last4;

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
     * @param string $customer
     * @param string $fingerprint
     * @param string $last4
     * @param int    $amount
     * @param int    $created
     */
    public function __construct(
        string $id,
        string $customer,
        string $fingerprint,
        string $last4,
        int $amount,
        int $created
    ) {
        $this->id = $id;
        $this->customer = $customer;
        $this->fingerprint = $fingerprint;
        $this->last4 = $last4;
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
    public function getCustomer(): string
    {
        return $this->customer;
    }

    /**
     * @return string
     */
    public function getFingerprint(): string
    {
        return $this->fingerprint;
    }

    /**
     * @return string
     */
    public function getLast4(): string
    {
        return $this->last4;
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
            'customer' => $this->customer,
            'fingerprint' => $this->fingerprint,
            'last4' => $this->last4,
            'amount' => $this->amount,
            'created' => $this->created,
        ];
    }
}
