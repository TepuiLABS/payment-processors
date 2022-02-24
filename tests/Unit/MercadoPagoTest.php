<?php

test('can resolve class', function () {
    $params = [
        'base_uri' => 'https://api.mercadopago.com',
        'key' => 'TEST-529cf55e-ccfa-4422-82f2-42f48528ae99',
        'secret' => 'TEST-1434195171484499-110822-9a6e612973312079b2f46e446e7b8bf4-310393912',
        'base_currency' => 'UYU',
    ];

    $mercadopago = \Tepuilabs\PaymentProcessors\Facades\PaymentProcessors::resolveService('mercadopago', $params);

    $this->assertInstanceOf(\Tepuilabs\PaymentProcessors\Services\MercadoPagoService::class, $mercadopago);
});

test('can resolve access token', function () {

    $params = [
        'base_uri' => 'https://api.mercadopago.com',
        'key' => 'TEST-529cf55e-ccfa-4422-82f2-42f48528ae99',
        'secret' => 'TEST-1434195171484499-110822-9a6e612973312079b2f46e446e7b8bf4-310393912',
        'base_currency' => 'UYU',
    ];

    $mercadopago = \Tepuilabs\PaymentProcessors\Facades\PaymentProcessors::resolveService('mercadopago', $params);

    $token = $mercadopago->resolveAccessToken();

    $this->assertIsString($token);
});


test('can return array of payment methods', function () {

    $params = [
        'base_uri' => 'https://api.mercadopago.com',
        'key' => 'TEST-529cf55e-ccfa-4422-82f2-42f48528ae99',
        'secret' => 'TEST-3421582647526250-050719-d0646f4f22adc6818f0e834fff1dc5be-310393912',
        'base_currency' => 'UYU',
    ];

    $mercadopago = \Tepuilabs\PaymentProcessors\Facades\PaymentProcessors::resolveService('mercadopago', $params);

    $methods = $mercadopago->getPaymentMethods();

    $this->assertIsArray($methods);
});
