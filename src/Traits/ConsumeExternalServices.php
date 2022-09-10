<?php

namespace Tepuilabs\PaymentProcessors\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\StreamInterface;

trait ConsumeExternalServices
{
    /**
     * makeRequest
     *
     * @param  string  $method
     * @param  string  $requestUrl
     * @param  array  $queryParams
     * @param  array  $formParams
     * @param  array  $headers
     * @param  bool  $isJsonRequest
     * @return StreamInterface|array
     *
     * @throws GuzzleException
     */
    public function makeRequest(string $method, string $requestUrl, array $queryParams = [], array $formParams = [], array $headers = [], bool $isJsonRequest = false): StreamInterface|array
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);

        if (method_exists($this, 'resolveAuthorization')) {
            $this->resolveAuthorization($queryParams, $formParams, $headers);
        }

        $response = $client->request($method, $requestUrl, [
            $isJsonRequest ? 'json' : 'form_params' => $formParams,
            'headers' => $headers,
            'query' => $queryParams,
            'debug' => false,
        ]);

        $response = $response->getBody();

        if (method_exists($this, 'decodeResponse')) {
            $response = $this->decodeResponse((string) $response);
        }

        return $response;
    }
}
