<?php

namespace Yosmy\Stripe;

use JsonSerializable;

class Card implements JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $last4;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $fingerprint;

    /**
     * @var string
     */
    private $country;

    /**
     * @param string      $id
     * @param string|null $name
     * @param string      $last4
     * @param string      $fingerprint
     * @param string      $country
     */
    public function __construct(
        string $id,
        ?string $name,
        string $last4,
        string $fingerprint,
        string $country
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->last4 = $last4;
        $this->fingerprint = $fingerprint;
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLast4(): string
    {
        return $this->last4;
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
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'last4' => $this->last4,
            'fingerprint' => $this->fingerprint,
            'country' => $this->country,
        ];
    }
}
