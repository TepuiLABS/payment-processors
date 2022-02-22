<?php
namespace Tepuilabs\PaymentProcessors\Tests\Feature;

use Tepuilabs\PaymentProcessors\Facades\PaymentProcessors;
use Tepuilabs\PaymentProcessors\Tests\TestCase;

class MercadoPagoTest extends TestCase
{
    /** @test */
    public function test_it_can_resolve_class()
    {
        $params = $this->params();

        $mercadopago = PaymentProcessors::resolveService('mercadopago', $params);

        $this->assertInstanceOf(\Tepuilabs\PaymentProcessors\Services\MercadoPagoService::class, $mercadopago);
    }

    /** @test */
    public function test_it_can_resolve_access_token()
    {

        $params = $this->params();

        $mercadopago = PaymentProcessors::resolveService('mercadopago', $params);

        $token = $mercadopago->resolveAccessToken();

        $this->assertIsString($token);
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    private function params(): array
    {
        return [
            'base_uri' => 'https://api.mercadopago.com',
            'key' => 'TEST-529cf55e-ccfa-4422-82f2-42f48528ae99',
            'secret' => 'TEST-1434195171484499-110822-9a6e612973312079b2f46e446e7b8bf4-310393912',
            'base_currency' => 'UYU',
        ];
    }
}
