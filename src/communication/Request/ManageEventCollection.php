<?php

namespace Yosmy\Payment\Gateway\Stripe\Request;

use Yosmy\Mongo\ManageCollection;

/**
 * @di\service({
 *     private: true
 * })
 */
class ManageEventCollection extends ManageCollection
{
    /**
     * @di\arguments({
     *     uri: "%mongo_uri%",
     *     db:  "%mongo_db%"
     * })
     *
     * @param string $uri
     * @param string $db
     */
    public function __construct(
        string $uri,
        string $db
    ) {
        parent::__construct(
            $uri,
            $db,
            'yosmy_payment_gateway_stripe_request_events',
            [
                'typeMap' => array(
                    'root' => Event::class,
                ),
            ]
        );
    }
}
