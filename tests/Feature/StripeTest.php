<?php

namespace Tepuilabs\PaymentProcessors\Tests\Feature;

use Tepuilabs\PaymentProcessors\Facades\PaymentProcessors;
use Tepuilabs\PaymentProcessors\Tests\TestCase;

class StripeTest extends TestCase
{
    /** @test */
    public function test_it_can_resolve_class()
    {
        $params = [
            'key' => 'pk_test_51IMzM0JcoyM5FfOypXlbYcOcg9EsrAwfhLikFrK436CqIGIRxysFz1G45NtJJik4NCVAje8xUddeeD0KjVeNe5Rf00JyWoyvEi',
            'secret' => 'sk_test_51IMzM0JcoyM5FfOyoV47UeCAMpHFvkrPOrVRM0B83tE6NEffMlLJCuX09jh6Gv6nXKwx5pR3nWaBp5z4TPP08zIY00z3CLwJJw',
        ];

        $stripe = PaymentProcessors::resolveService('stripe', $params);

        $this->assertInstanceOf(\Tepuilabs\PaymentProcessors\Services\StripeService::class, $stripe);
    }

    /** @test */
    public function test_it_can_get_balance()
    {
        $params = [
            'key' => 'pk_test_51IMzM0JcoyM5FfOypXlbYcOcg9EsrAwfhLikFrK436CqIGIRxysFz1G45NtJJik4NCVAje8xUddeeD0KjVeNe5Rf00JyWoyvEi',
            'secret' => 'sk_test_51IMzM0JcoyM5FfOyoV47UeCAMpHFvkrPOrVRM0B83tE6NEffMlLJCuX09jh6Gv6nXKwx5pR3nWaBp5z4TPP08zIY00z3CLwJJw',
        ];

        $stripe = PaymentProcessors::resolveService('stripe', $params);

        $balance = $stripe->makeRequest('GET', '/v1/balance');

        $this->assertIsArray($balance, 'assert variable is array or not');
    }

    /** @test */
    public function test_it_can_generate_a_payment_method_id()
    {
        $params = [
            'key' => 'pk_test_51IMzM0JcoyM5FfOypXlbYcOcg9EsrAwfhLikFrK436CqIGIRxysFz1G45NtJJik4NCVAje8xUddeeD0KjVeNe5Rf00JyWoyvEi',
            'secret' => 'sk_test_51IMzM0JcoyM5FfOyoV47UeCAMpHFvkrPOrVRM0B83tE6NEffMlLJCuX09jh6Gv6nXKwx5pR3nWaBp5z4TPP08zIY00z3CLwJJw',
        ];

        $stripe = PaymentProcessors::resolveService('stripe', $params);

        // only for test
        $paymentMethod = $stripe->makeRequest('POST', '/v1/payment_methods', [], [
            'type' => 'card',
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 2,
                'exp_year' => 2022,
                'cvc' => '314',
            ],
        ]);

        $paymentMethodId = $paymentMethod['id'];

        $this->assertIsString($paymentMethodId);

        return $paymentMethodId;
    }

    /** @depends test_it_can_generate_a_payment_method_id */
    public function test_it_can_handle_a_payment($paymentMethodId)
    {
        $params = [
            'key' => 'pk_test_51IMzM0JcoyM5FfOypXlbYcOcg9EsrAwfhLikFrK436CqIGIRxysFz1G45NtJJik4NCVAje8xUddeeD0KjVeNe5Rf00JyWoyvEi',
            'secret' => 'sk_test_51IMzM0JcoyM5FfOyoV47UeCAMpHFvkrPOrVRM0B83tE6NEffMlLJCuX09jh6Gv6nXKwx5pR3nWaBp5z4TPP08zIY00z3CLwJJw',
        ];

        $stripe = PaymentProcessors::resolveService('stripe', $params);

        $paymentData = [
            'amount' => 501.52,
            'paymentMethod' => $paymentMethodId,
        ];

        $intent = $stripe->handlePayment($paymentData);

        $this->assertIsArray($intent, 'assert variable is array or not');

        return $intent;
    }

    /** @depends test_it_can_handle_a_payment */
    public function test_it_can_confirm_a_payment($intent)
    {
        $params = [
            'key' => 'pk_test_51IMzM0JcoyM5FfOypXlbYcOcg9EsrAwfhLikFrK436CqIGIRxysFz1G45NtJJik4NCVAje8xUddeeD0KjVeNe5Rf00JyWoyvEi',
            'secret' => 'sk_test_51IMzM0JcoyM5FfOyoV47UeCAMpHFvkrPOrVRM0B83tE6NEffMlLJCuX09jh6Gv6nXKwx5pR3nWaBp5z4TPP08zIY00z3CLwJJw',
        ];

        $stripe = PaymentProcessors::resolveService('stripe', $params);

        $confirm = $stripe->confirmPayment($intent['id']);

        $this->assertEquals('succeeded', $confirm['status'], 'actual value is not equals to expected');
    }
}
