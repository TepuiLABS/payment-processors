<?php
namespace Tepuilabs\PaymentProcessors\Tests\Feature;

use Tepuilabs\PaymentProcessors\Facades\PaymentProcessors;
use Tepuilabs\PaymentProcessors\Tests\TestCase;

class PayPalTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config()->set('payment-processors.paypal.base_uri', 'https://api.sandbox.paypal.com');
        config()->set('payment-processors.paypal.client_id', 'ARt435S0DIjl2rljuro1TIOQkeKvNSykbp34DBZIQBUxwqccrMZavTE0r-v7QDFtNQcMU-5SIwBf7B-n');
        config()->set('payment-processors.paypal.client_secret', 'EPhL66hqSoMjfWT3FQpuZPxQFnFCh-Llpf9qBb3UkXX33bjyLBE4Qu1d2dx8s3y-qeI-ycmA5kVbdmJ6');
        config()->set('payment-processors.paypal.return_url', 'http://localhost/return_url');
        config()->set('payment-processors.paypal.cancel_url', 'http://localhost/cancel_url');
    }

    /** @test */
    public function test_it_can_resolve_class()
    {
        $paypal = PaymentProcessors::resolveService('paypal');

        $this->assertInstanceOf(\Tepuilabs\PaymentProcessors\Services\PayPalService::class, $paypal);
    }

    /** @test */
    public function test_it_can_resolve_access_token()
    {
        $paypal = PaymentProcessors::resolveService('paypal');

        $token = $paypal->resolveAccessToken();

        $this->assertIsString($token);
    }

    /** @test */
    public function test_is_can_handle_a_payment()
    {
        $paypal = PaymentProcessors::resolveService('paypal');

        $payment = $paypal->handlePayment(200);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $payment);
    }

    /**
     * @depends test_is_can_handle_a_payment
     */
    public function test_it_can_return_array()
    {
        $paypal = PaymentProcessors::resolveService('paypal');

        $arr = $paypal->handleApproval();

        $this->assertIsArray($arr, 'assert variable is array or not');
    }
}
