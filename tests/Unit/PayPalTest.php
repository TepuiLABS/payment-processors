<?php

use Tepuilabs\PaymentProcessors\Facades\PaymentProcessors;

beforeEach(function () {
    $this->params = [
        'base_uri' => 'https://api.sandbox.paypal.com',
        'client_id' => 'ARt435S0DIjl2rljuro1TIOQkeKvNSykbp34DBZIQBUxwqccrMZavTE0r-v7QDFtNQcMU-5SIwBf7B-n',
        'client_secret' => 'EPhL66hqSoMjfWT3FQpuZPxQFnFCh-Llpf9qBb3UkXX33bjyLBE4Qu1d2dx8s3y-qeI-ycmA5kVbdmJ6',
        'return_url' => 'http://localhost/return_url',
        'cancel_url' => 'http://localhost/cancel_url',
    ];
});


test('it can resolve paypal service', function () {
    $mercadopago = PaymentProcessors::resolveService('paypal', $this->params);

    $this->assertInstanceOf(\Tepuilabs\PaymentProcessors\Services\PayPalService::class, $mercadopago);
});


test('it can resolve paypal access token', function () {
    $paypal = PaymentProcessors::resolveService('paypal', $this->params);

    $token = $paypal->resolveAccessToken();

    $this->assertIsString($token);
});


test('it can handle redirect to paypal', function () {
    $paypal = PaymentProcessors::resolveService('paypal', $this->params);

    $payment = $paypal->handlePayment(200);

    $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $payment);
});
