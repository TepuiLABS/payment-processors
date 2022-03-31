<?php

namespace Tepuilabs\PaymentProcessors\Services;

use Tepuilabs\PaymentProcessors\Traits\ConsumeExternalServices;

class MercadoPagoService
{
    use ConsumeExternalServices;

    protected string $baseUri;
    protected string $key;
    protected string $secret;
    protected string $baseCurrency;
    protected string $integratorId;

    final public function __construct(array $apiKeys)
    {
        $this->baseUri = $apiKeys['base_uri'];
        $this->key = $apiKeys['key'];
        $this->secret = $apiKeys['secret'];
        $this->baseCurrency = $apiKeys['base_currency'];
        $this->integratorId = $apiKeys['integrator_id'] ?? '';
    }

    public static function paymentService(array $apiKeys): self
    {
        return new static($apiKeys);
    }

    public function resolveAuthorization(array &$queryParams, array &$formParams, array &$headers): void
    {
        $queryParams['access_token'] = $this->resolveAccessToken();
        $headers['Content-Type'] = 'application/json';
        $headers['Accept'] = 'application/json';

        if (!empty($this->integratorId)) {
            $headers['integrator_id'] = $this->integratorId;
        }
    }

    public function decodeResponse(string $response): array
    {
        return json_decode($response, true);
    }

    public function resolveAccessToken(): string
    {
        return $this->secret;
    }

    /**
     * Undocumented function
     *
     * @return \Psr\Http\Message\StreamInterface|array
     */
    public function getPaymentMethods()
    {
        return $this->makeRequest('GET', '/v1/payment_methods');
    }

    public function createPreference(array $params)
    {
        return $this->makeRequest(
            'POST',
            '/checkout/preferences',
            [],
            $params,
            [],
            true
        );
    }

    /**
     * handlePayment
     *
     * @param string $cardNetwork
     * @param string $cardToken
     * @param int $amount
     * @param string $userEmail
     * @return \Psr\Http\Message\StreamInterface|array
     */
    public function handlePayment(string $cardNetwork, string $cardToken, int $amount, string $userEmail)
    {
        return $this->createPayment(
            $amount,
            $cardNetwork,
            $cardToken,
            $userEmail
        );
    }

    /**
     * en mercado pago no se maneja aprobaciones
     * por defecto los aprueba
     *
     * @return void
     */
    public function handleApproval(): void
    {
    }

    /**
     * createPayment
     *
     * @param int $amount
     * @param string $cardNetwork
     * @param string $cardToken
     * @param string $email
     * @param int $installments
     * @return \Psr\Http\Message\StreamInterface|array
     */
    private function createPayment(int $amount, string $cardNetwork, string $cardToken, string $email, int $installments = 1)
    {
        return $this->makeRequest(
            'POST',
            '/v1/payments',
            [],
            [
                'token' => $cardToken,
                'installments' => $installments,
                'transaction_amount' => $amount,
                'description' => config('app.name') . ' MercadoPago',
                'payment_method_id' => $cardNetwork,
                'statement_descriptor' => config('app.name') . ' MercadoPago',
                'payer' => [
                    'email' => $email,
                ],
                'binary_mode' => true,
            ],
            [],
            true
        );
    }
}
