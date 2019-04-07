<?php

namespace Yosmy\Stripe;

use JsonSerializable;

class Token implements JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var Card
     */
    private $card;

    /**
     * @param string $id
     * @param Card $card
     */
    public function __construct(
        string $id,
        Card $card
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
     * @return Card
     */
    public function getCard(): Card
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
