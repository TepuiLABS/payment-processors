<?php

namespace Tepuilabs\PaymentProcessors\Services;

use Tepuilabs\PaymentProcessors\Traits\ConsumeExternalServices;

class StripeService
{
    use ConsumeExternalServices;

    protected string $baseUri;
    protected string $key;
    protected string $secret;

    private array $apiKeys;

    final public function __construct(array $apiKeys)
    {
        $this->baseUri = 'https://api.stripe.com';
        $this->key = $apiKeys['key'];
        $this->secret = $apiKeys['secret'];
    }

    public static function paymentService(array $apiKeys): self
    {
        return new static($apiKeys);
    }

    public function resolveAuthorization(array &$queryParams, array &$formParams, array &$headers): void
    {
        $headers['Authorization'] = $this->resolveAccessToken();
    }

    public function decodeResponse(string $response): array
    {
        return (array) json_decode($response, true);
    }

    /**
     * resolveAccessToken
     *
     * @return string
     */
    public function resolveAccessToken(): string
    {
        return "Bearer {$this->secret}";
    }

    /**
     * Undocumented function
     *
     * @param array $paymentData
     * @return \Psr\Http\Message\StreamInterface|array
     * @psalm-suppress UndefinedInterfaceMethod
     */
    public function handlePayment(array $paymentData)
    {
        $currency = isset($paymentData['currency']) ? $paymentData['currency'] : 'USD';
        $amount = $paymentData['amount'];
        $paymentMethod = $paymentData['paymentMethod'];

        return $this->createIntent($amount, $currency, $paymentMethod);
    }

    /**
     * Undocumented function
     *
     * @return \Psr\Http\Message\StreamInterface|array
     */
    public function handleApproval()
    {
        return [];
    }

    /**
     * Undocumented function
     *
     * @param float $value
     * @param string $currency
     * @param string $paymentMethod
     * @return \Psr\Http\Message\StreamInterface|array
     */
    public function createIntent(float $value, string $currency, string $paymentMethod)
    {
        return $this->makeRequest(
            'POST',
            '/v1/payment_intents',
            [],
            [
                'amount' => round($value * $this->resolveFactor($currency)),
                'currency' => strtolower($currency),
                'payment_method' => $paymentMethod,
                'confirmation_method' => 'manual',
                'payment_method_types' => [
                    'card',
                ],
            ],
        );
    }

    /**
     * Undocumented function
     *
     * @param string $paymentIntentId
     * @return \Psr\Http\Message\StreamInterface|array
     */
    public function confirmPayment(string $paymentIntentId)
    {
        return $this->makeRequest(
            'POST',
            "/v1/payment_intents/{$paymentIntentId}/confirm",
        );
    }

    /**
     * Undocumented function
     *
     * @param string $currency
     * @return int
     */
    private function resolveFactor(string $currency)
    {
        $zeroDecimalCurrencies = ['JPY'];

        if (in_array(strtoupper($currency), $zeroDecimalCurrencies)) {
            return 1;
        }

        return 100;
    }
}
