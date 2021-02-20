<?php

return [

    'mercadopago' => [
        'class' => \Tepuilabs\PaymentProcessors\Services\MercadoPagoService::class,
    ],

    'paypal' => [
        'class' => \Tepuilabs\PaymentProcessors\Services\PayPalService::class,
    ],

    'stripe' => [
        'class' => \Tepuilabs\PaymentProcessors\Services\StripeService::class,
    ],

];
