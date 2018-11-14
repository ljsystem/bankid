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
     * @param        $certificate
     * @param null   $rootCertificate
     * @param string $environment
     */
    public function __construct($certificate, $rootCertificate = null, $environment = self::ENVIRONMENT_TEST)
    {
        $httpOptions = [
            'base_uri' => 'https://'.self::HOSTS[$environment].'/rp/v5/',
            'cert' => $certificate,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        if ($rootCertificate) {
            $httpOptions['verify'] = $rootCertificate;
        }

        $this->httpClient = new Client($httpOptions);
    }

    /**
     * Authenticate a user using their personal number.
     *
     * @param $personalNumber
     *
     * @param $ip
     *
     * @return BankIDResponse
     */
    public function authenticate($personalNumber, $ip)
    {
        try {
            $httpResponse = $this->httpClient->post('auth', [
                RequestOptions::JSON => [
                    'personalNumber' => $personalNumber,
                    'endUserIp' => $ip,
                ],
            ]);
        } catch (RequestException $e) {
            return self::requestExceptionToBankIDResponse($e);
        }

        $httpResponseBody = json_decode($httpResponse->getBody());

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
            return self::requestExceptionToBankIDResponse($e);
        }

        $httpResponseBody = json_decode($httpResponse->getBody());

        switch ($httpResponseBody->status) {
            case BankIDResponse::STATUS_COMPLETE:
                return new BankIDResponse(BankIDResponse::STATUS_COMPLETE, $httpResponseBody);
            default:
                return new BankIDResponse($httpResponseBody->status, $httpResponseBody);
        }
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
            return self::requestExceptionToBankIDResponse($e);
        }

        $httpResponseBody = json_decode($httpResponse->getBody());

        return new BankIDResponse(BankIDResponse::STATUS_OK, $httpResponseBody);
    }

    /**
     * Transform GuzzleHttp request exception into a BankIDResponse.
     *
     * @param RequestException $e
     *
     * @return BankIDResponse
     */
    private static function requestExceptionToBankIDResponse(RequestException $e)
    {
        $httpResponseBody = json_decode($e->getResponse()->getBody());

        return new BankIDResponse(BankIDResponse::STATUS_FAILED, $httpResponseBody);
    }
}
