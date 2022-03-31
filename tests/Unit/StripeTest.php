<?php

use Tepuilabs\PaymentProcessors\Facades\PaymentProcessors;


beforeEach(function () {

    $this->params = [
        'key' => 'pk_test_51IMzM0JcoyM5FfOypXlbYcOcg9EsrAwfhLikFrK436CqIGIRxysFz1G45NtJJik4NCVAje8xUddeeD0KjVeNe5Rf00JyWoyvEi',
        'secret' => 'sk_test_51IMzM0JcoyM5FfOyoV47UeCAMpHFvkrPOrVRM0B83tE6NEffMlLJCuX09jh6Gv6nXKwx5pR3nWaBp5z4TPP08zIY00z3CLwJJw',
    ];
});


test('it can resolve stripe service', function () {

    $stripe = PaymentProcessors::resolveService('stripe', $this->params);

    $this->assertInstanceOf(\Tepuilabs\PaymentProcessors\Services\StripeService::class, $stripe);
});


test('it can get stripe balance', function () {

    $stripe = PaymentProcessors::resolveService('stripe', $this->params);

    $balance = $stripe->makeRequest('GET', '/v1/balance');

    expect($balance)->toBeArray();
});

test('it can generate a stripe payment method id', function () {

    $stripe = PaymentProcessors::resolveService('stripe', $this->params);

    // only for test
    $paymentMethod = $stripe->makeRequest('POST', '/v1/payment_methods', [], [
        'type' => 'card',
        'card' => [
            'number' => '4242424242424242',
            'exp_month' => 12,
            'exp_year' => 2026,
            'cvc' => '314',
        ],
    ]);

    $paymentMethodId = $paymentMethod['id'];

    expect($paymentMethodId)->toBeString();

    return $paymentMethodId;
});

test('it can handle a stripe payment', function ($paymentMethodId) {

    $stripe = PaymentProcessors::resolveService('stripe', $this->params);

    $paymentData = [
        'amount' => 501.52,
        'paymentMethod' => $paymentMethodId,
    ];

    $intent = $stripe->handlePayment($paymentData);

    expect($intent)->toBeArray();

    return $intent;
})->depends('it can generate a stripe payment method id');

test('it can confirm stripe payment', function($intent) {

    $stripe = PaymentProcessors::resolveService('stripe', $this->params);

    $confirm = $stripe->confirmPayment($intent['id']);

    expect($confirm['status'])->toEqual('succeeded');
})->depends('it can handle a stripe payment');
