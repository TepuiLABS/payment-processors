<?php

return [

    'mercadopago' => [
        'base_uri'      => env('MERCADOPAGO_BASE_URI'),
        'key'           => env('MERCADOPAGO_KEY'),
        'secret'        => env('MERCADOPAGO_SECRET'),
        'base_currency' => env('MERCADOPAGO_BASE_CURRENCY'),
        'class'         => \Tepuilabs\PaymentProcessors\Services\MercadoPagoService::class,
    ],

    'paypal' => [
        'base_uri' => env('PAYPAL_BASE_URI'),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
        'return_url' => env('PAYPAL_RETURN_URL'),
        'cancel_url' => env('PAYPAL_CANCEL_URL'),
        'class' => \Tepuilabs\PaymentProcessors\Services\PayPalService::class,
    ],


];
