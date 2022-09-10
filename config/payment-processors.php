<?php

use Tepuilabs\PaymentProcessors\Services\MercadoPagoService;
use Tepuilabs\PaymentProcessors\Services\PayPalService;
use Tepuilabs\PaymentProcessors\Services\StripeService;

return [

    'mercadopago' => [
        'class' => MercadoPagoService::class,
    ],

    'paypal' => [
        'class' => PayPalService::class,
    ],

    'stripe' => [
        'class' => StripeService::class,
    ],

];
