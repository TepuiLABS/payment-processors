<?php
namespace Tepuilabs\PaymentProcessors\Tests\Feature;

use Tepuilabs\PaymentProcessors\Facades\PaymentProcessors;
use Tepuilabs\PaymentProcessors\Tests\TestCase;

class MercadoPagoTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config()->set('payment-processors.mercadopago.base_uri', 'https://api.mercadopago.com');
        config()->set('payment-processors.mercadopago.key', 'TEST-529cf55e-ccfa-4422-82f2-42f48528ae99');
        config()->set('payment-processors.mercadopago.secret', 'TEST-1434195171484499-110822-9a6e612973312079b2f46e446e7b8bf4-310393912');
        config()->set('payment-processors.mercadopago.base_currency', 'UYU');
    }

    /** @test */
    public function test_it_can_resolve_class()
    {
        $mercadopago = PaymentProcessors::resolveService('mercadopago');

        $this->assertInstanceOf(\Tepuilabs\PaymentProcessors\Services\MercadoPagoService::class, $mercadopago);
    }

    /** @test */
    public function test_it_can_resolve_access_token()
    {
        $mercadopago = PaymentProcessors::resolveService('mercadopago');

        $token = $mercadopago->resolveAccessToken();

        $this->assertIsString($token);
    }
}
