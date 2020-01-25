<?php

namespace Yosmy\Payment\Gateway\Stripe;

use JsonSerializable;
use Yosmy\Payment\Gateway;

class Token implements JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var Gateway\Card
     */
    private $card;

    /**
     * @param string $id
     * @param Gateway\Card $card
     */
    public function __construct(
        string $id,
        Gateway\Card $card
    ) {
        $this->id = $id;
        $this->card = $card;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Gateway\Card
     */
    public function getCard(): Gateway\Card
    {
        return $this->card;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'card' => $this->card->jsonSerialize(),
        ];
    }
}
