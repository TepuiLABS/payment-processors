<?php

namespace Tepuilabs\PaymentProcessors\Services;

use GuzzleHttp\Client as PayPalClient;
use Tepuilabs\PaymentProcessors\Traits\ConsumeExternalServices;

class PayPalService
{
    use ConsumeExternalServices;

    protected string $baseUri;
    protected string $clientId;
    protected string $clientSecret;
    protected string $return_url;
    protected string $cancel_url;

    final public function __construct(array $apiKeys)
    {
        $this->baseUri = $apiKeys['base_uri'];
        $this->clientId = $apiKeys['client_id'];
        $this->clientSecret = $apiKeys['client_secret'];
        $this->return_url = $apiKeys['return_url'];
        $this->cancel_url = $apiKeys['cancel_url'];
    }

    public static function paymentService(array $apiKeys): self
    {
        return new static($apiKeys);
    }

    public function resolveAuthorization(array &$queryParams, array &$formParams, array &$headers): void
    {
        $headers['Authorization'] = $this->resolveAccessToken();
        $headers['Content-Type'] = 'application/json';
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
        $credentials = base64_encode("{$this->clientId}:{$this->clientSecret}");

        $basicCredentials = "Basic {$credentials}";

        $paypalClient = new PayPalClient([
            'base_uri' => $this->baseUri,
        ]);

        $response = $paypalClient->request('POST', '/v1/oauth2/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
            ],
            'headers' => [
                'Authorization' => $basicCredentials,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'query' => [],
            'debug' => false,
        ]);

        $response = $response->getBody();

        $token = json_decode((string) $response, true);

        return "Bearer {$token['access_token']}";
    }

    /**
     * Undocumented function
     *
     * @param float $amount
     * @param string $currency
     * @return \Illuminate\Http\RedirectResponse
     * @psalm-suppress UndefinedInterfaceMethod
     */
    public function handlePayment(float $amount, string $currency = 'USD')
    {
        $order = $this->createOrder($amount, $currency);
        $orderLinks = collect($order['links']);

        $approve = $orderLinks->where('rel', 'approve')->first();

        session()->put('approvalId', $order['id']);

        return redirect()->away($approve['href']);
    }

    /**
     * Undocumented function
     *
     * @return \Psr\Http\Message\StreamInterface|array
     */
    public function handleApproval()
    {
        if (session()->has('approvalId')) {
            $approvalId = session()->get('approvalId');

            return $this->capturePayment($approvalId);
        }

        return [];
    }

    /**
     * Undocumented function
     *
     * @param float $value
     * @param string $currency
     * @return \Psr\Http\Message\StreamInterface|array
     */
    public function createOrder(float $value, string $currency)
    {
        return $this->makeRequest(
            'POST',
            '/v2/checkout/orders',
            [],
            [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    0 => [
                        'amount' => [
                            'currency_code' => strtoupper($currency),
                            'value' => $value,
                        ],
                    ],
                ],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                    'return_url' => $this->return_url,
                    'cancel_url' => $this->cancel_url,
                ],
            ],
            [],
            true,
        );
    }

    /**
     * Undocumented function
     *
     * @param string $approvalId
     * @return \Psr\Http\Message\StreamInterface|array
     */
    public function capturePayment(string $approvalId)
    {
        return $this->makeRequest(
            'POST',
            "/v2/checkout/orders/{$approvalId}/capture",
            [],
            [],
            [
                'Content-Type' => 'application/json',
            ],
        );
    }
}
