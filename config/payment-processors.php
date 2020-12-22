<?php

return [

    'mercadopago' => [
        'base_uri'      => env('MERCADOPAGO_BASE_URI'),
        'key'           => env('MERCADOPAGO_KEY'),
        'secret'        => env('MERCADOPAGO_SECRET'),
        'base_currency' => env('MERCADOPAGO_BASE_CURRENCY'),
        'class'         => Tepuilabs\PaymentProcessors\Services\MercadoPagoService::class,
    ],
];
