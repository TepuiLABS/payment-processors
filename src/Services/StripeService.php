<?php

namespace Tepuilabs\PaymentProcessors\Services;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\StreamInterface;
use Tepuilabs\PaymentProcessors\Traits\ConsumeExternalServices;

class StripeService
{
    use ConsumeExternalServices;

    protected string $baseUri;

    protected string $key;

    protected string $secret;

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
     */
    public function resolveAccessToken(): string
    {
        return "Bearer {$this->secret}";
    }

    /**
     * Undocumented function
     *
     *
     * @psalm-suppress UndefinedInterfaceMethod
     */
    public function handlePayment(array $paymentData): StreamInterface|array
    {
        $currency = $paymentData['currency'] ?? 'USD';
        $amount = $paymentData['amount'];
        $paymentMethod = $paymentData['paymentMethod'];

        return $this->createIntent($amount, $currency, $paymentMethod);
    }

    /**
     * Undocumented function
     */
    public function handleApproval(): StreamInterface|array
    {
        return [];
    }

    /**
     * Undocumented function
     *
     *
     * @throws GuzzleException
     */
    public function createIntent(float $value, string $currency, string $paymentMethod): StreamInterface|array
    {
        return $this->makeRequest(
            'POST',
            '/v1/payment_intents',
            [],
            [
                'amount' => $value,
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
     *
     * @throws GuzzleException
     */
    public function confirmPayment(string $paymentIntentId): StreamInterface|array
    {
        return $this->makeRequest(
            'POST',
            "/v1/payment_intents/{$paymentIntentId}/confirm",
        );
    }
}
