<?php

namespace LJSystem\BankID;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\RequestException;

class BankID
{
    const ENVIRONMENT_PRODUCTION = 'prod';
    const ENVIRONMENT_TEST = 'test';

    const HOSTS = [
        self::ENVIRONMENT_PRODUCTION => 'appapi2.bankid.com',
        self::ENVIRONMENT_TEST => 'appapi2.test.bankid.com',
    ];

    private $httpClient;

    /**
     * BankID constructor.
     *
     * @param string $environment
     * @param string $certificate
     * @param string $caCertificate
     * @param string $key
     * @param string $passphrase
     */
    public function __construct($environment = self::ENVIRONMENT_TEST, $certificate = null, $caCertificate = null, $key = null, $passphrase = null)
    {
        if (is_null($certificate)) {
            $certificate = __DIR__.'/../certs/test.pem';
        }

        if (! is_null($passphrase)) {
            $certificate = [$certificate, $passphrase];
        }

        if (is_null($caCertificate)) {
            $caCertificate = __DIR__.'/../certs/test_cacert.cer';
        }

        $httpOptions = [
            'base_uri' => 'https://'.self::HOSTS[$environment].'/rp/v5.1/',
            'cert' => $certificate,
            'verify' => $caCertificate,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        if (! is_null($key)) {
            $httpOptions['ssl_key'] = $key;
        }

        $this->httpClient = new Client($httpOptions);
    }

    /**
     * Authenticate a user using their personal number.
     *
     * @param $personalNumber
     * @param $ip
     *
     * @return BankIDResponse
     */
    public function authenticate($personalNumber, $ip)
    {
        $payload['endUserIp'] = $ip;

        if (!empty($personalNumber)) {
            $payload['personalNumber'] = $personalNumber;
        }

        try {


            $httpResponse = $this->httpClient->post('auth', [
                RequestOptions::JSON => $payload,
            ]);
        } catch (RequestException $e) {
            return $this->requestExceptionToBankIDResponse($e);
        }

        $httpResponseBody = json_decode($httpResponse->getBody(), true);

        return new BankIDResponse(BankIDResponse::STATUS_PENDING, $httpResponseBody);
    }

    /**
     * Request a signing order for a user.
     *
     * @param $personalNumber
     * @param $ip
     * @param $userVisibleData
     * @param $userNonVisibleData
     *
     * @return BankIDResponse
     */
    public function sign($personalNumber, $ip, $userVisibleData = '', $userNonVisibleData = NULL)
    {
        try {
            $parameters = [
                'endUserIp' => $ip,
                'userVisibleData' => base64_encode($userVisibleData),
            ];

            if (!empty($personalNumber)) {
                $parameters['personalNumber'] = $personalNumber;
            }

            if (!empty($userNonVisibleData)) {
                $parameters['userNonVisibleData'] = base64_encode($userNonVisibleData);
            }

            $httpResponse = $this->httpClient->post('sign', [
                RequestOptions::JSON => $parameters,
            ]);
        } catch (RequestException $e) {
            return $this->requestExceptionToBankIDResponse($e);
        }

        $httpResponseBody = json_decode($httpResponse->getBody(), true);

        return new BankIDResponse(BankIDResponse::STATUS_PENDING, $httpResponseBody);
    }

    /**
     * Collect an ongoing user request.
     *
     * @param $orderReference
     *
     * @return BankIDResponse
     */
    public function collect($orderReference)
    {
        try {
            $httpResponse = $this->httpClient->post('collect', [
                RequestOptions::JSON => [
                    'orderRef' => $orderReference,
                ],
            ]);
        } catch (RequestException $e) {
            return $this->requestExceptionToBankIDResponse($e);
        }

        $httpResponseBody = json_decode($httpResponse->getBody(), true);

        return new BankIDResponse($httpResponseBody['status'], $httpResponseBody);
    }

    /**
     * Cancel an ongoing order per the users request.
     *
     * @param $orderReference
     *
     * @return BankIDResponse
     */
    public function cancel($orderReference)
    {
        try {
            $httpResponse = $this->httpClient->post('cancel', [
                RequestOptions::JSON => [
                    'orderRef' => $orderReference,
                ],
            ]);
        } catch (RequestException $e) {
            return $this->requestExceptionToBankIDResponse($e);
        }

        $httpResponseBody = json_decode($httpResponse->getBody(), true);

        return new BankIDResponse(BankIDResponse::STATUS_OK, $httpResponseBody);
    }

    /**
     * Transform GuzzleHttp request exception into a BankIDResponse.
     *
     * @param RequestException $e
     *
     * @return BankIDResponse
     */
    private function requestExceptionToBankIDResponse(RequestException $e)
    {
        $body = $e->hasResponse() ? $e->getResponse()->getBody() : null;

        if ($body) {
            return new BankIDResponse(BankIDResponse::STATUS_FAILED, json_decode($body, true));
        }

        return new BankIDResponse(BankIDResponse::STATUS_FAILED, ['errorMessage' => $e->getMessage()]);
    }
}
