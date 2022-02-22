<?php
namespace Tepuilabs\PaymentProcessors\Tests\Feature;

use Tepuilabs\PaymentProcessors\Facades\PaymentProcessors;
use Tepuilabs\PaymentProcessors\Tests\TestCase;

class PayPalTest extends TestCase
{
    /** @test */
    public function test_it_can_resolve_class()
    {
        $params = $this->params();

        $paypal = PaymentProcessors::resolveService('paypal', $params);

        $this->assertInstanceOf(\Tepuilabs\PaymentProcessors\Services\PayPalService::class, $paypal);
    }

    /** @test */
    public function test_it_can_resolve_access_token()
    {
        $params = $this->params();

        $paypal = PaymentProcessors::resolveService('paypal', $params);

        $token = $paypal->resolveAccessToken();

        $this->assertIsString($token);
    }

    /** @test */
    public function test_is_can_handle_a_payment()
    {
        $params = $this->params();

        $paypal = PaymentProcessors::resolveService('paypal', $params);

        $payment = $paypal->handlePayment(200);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $payment);
    }

    /**
     * @depends test_is_can_handle_a_payment
     */
    public function test_it_can_return_array()
    {
        $params = $this->params();

        $paypal = PaymentProcessors::resolveService('paypal', $params);

        $arr = $paypal->handleApproval();

        $this->assertIsArray($arr, 'assert variable is array or not');
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    private  function params(): array
    {
        return [
            'base_uri' => 'https://api.sandbox.paypal.com',
            'client_id' => 'ARt435S0DIjl2rljuro1TIOQkeKvNSykbp34DBZIQBUxwqccrMZavTE0r-v7QDFtNQcMU-5SIwBf7B-n',
            'client_secret' => 'EPhL66hqSoMjfWT3FQpuZPxQFnFCh-Llpf9qBb3UkXX33bjyLBE4Qu1d2dx8s3y-qeI-ycmA5kVbdmJ6',
            'return_url' => 'http://localhost/return_url',
            'cancel_url' => 'http://localhost/cancel_url',
        ];
    }
}
