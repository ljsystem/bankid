<?php

namespace LJSystem\BankID;

class BankIDResponse
{
    const STATUS_OK = 'OK';
    const STATUS_COMPLETE = 'complete';
    const STATUS_PENDING = 'pending';
    const STATUS_FAILED = 'failed';

    const HINT_CODE_NO_CLIENT = 'noClient';
    const HINT_CODE_USER_CANCEL = 'userCancel';
    const HINT_CODE_EXPIRED_TRANSACTION = 'expiredTransaction';
    const HINT_CODE_USER_SIGN = 'userSign';
    const HINT_CODE_OUTSTANDING_TRANSACTION = 'outstandingTransaction';
    const HINT_CODE_STARTED = 'started';
    const HINT_CODE_CERTIFICATE_ERROR = 'certificateErr';
    const HINT_CODE_START_FAILED = 'startFailed';

    const ERROR_CODE_CANCELED = 'canceled';
    const ERROR_CODE_ALREADY_IN_PROGRESS = 'alreadyInProgress';
    const ERROR_CODE_REQUEST_TIMEOUT = 'requestTimeout';
    const ERROR_CODE_MAINTENANCE = 'maintenance';
    const ERROR_CODE_INTERNAL_ERROR = 'internalError';

    const RFA1 = 'RFA1';
    const RFA2 = 'RFA2';
    const RFA3 = 'RFA3';
    const RFA4 = 'RFA4';
    const RFA5 = 'RFA5';
    const RFA6 = 'RFA6';
    const RFA8 = 'RFA8';
    const RFA9 = 'RFA9';
    const RFA13 = 'RFA13';
    const RFA14A = 'RFA14-A';
    const RFA14B = 'RFA14-B';
    const RFA15A = 'RFA15-A';
    const RFA15B = 'RFA15-B';
    const RFA16 = 'RFA16';
    const RFA17 = 'RFA17';
    const RFA18 = 'RFA18';
    const RFA19 = 'RFA19';
    const RFA20 = 'RFA20';
    const RFA21 = 'RFA21';
    const RFA22 = 'RFA22';

    private $status = self::STATUS_PENDING;
    private $message = '';
    private $body = null;

    /**
     * BankIDResponse constructor.
     *
     * @param string $status
     * @param null|array $body
     */
    public function __construct($status, $body = null)
    {
        $this->status = $status;
        $this->body = $body;

        switch ($this->status) {
            case self::STATUS_PENDING:
                if (isset($body['hintCode'])) {
                    switch ($body['hintCode']) {
                        case self::HINT_CODE_NO_CLIENT:
                            $this->message = 'bankid.'.self::RFA1;

                            break;
                        case self::HINT_CODE_USER_SIGN:
                            $this->message = 'bankid.'.self::RFA9;

                            break;
                        case self::HINT_CODE_OUTSTANDING_TRANSACTION:
                            $this->message = 'bankid.'.self::RFA13;

                            break;
                        case self::HINT_CODE_STARTED:
                            $this->message = 'bankid.'.self::RFA14B;

                            break;
                        default:
                            $this->message = 'bankid.'.self::RFA21;

                            break;
                    }
                }

                break;
            case self::STATUS_FAILED:
                if (isset($body['hintCode'])) {
                    switch ($body['hintCode']) {
                        case self::HINT_CODE_USER_CANCEL:
                            $this->message = 'bankid.'.self::RFA6;

                            break;
                        case self::HINT_CODE_EXPIRED_TRANSACTION:
                            $this->message = 'bankid.'.self::RFA8;

                            break;
                        case self::HINT_CODE_CERTIFICATE_ERROR:
                            $this->message = 'bankid.'.self::RFA16;

                            break;
                        case self::HINT_CODE_START_FAILED:
                            $this->message = 'bankid.'.self::RFA17;

                            break;
                        default:
                            $this->message = 'bankid.'.self::RFA22;

                            break;
                    }
                } elseif (isset($body['errorCode'])) {
                    switch ($body['errorCode']) {
                        case self::ERROR_CODE_CANCELED:
                            $this->message = 'bankid.'.self::RFA3;

                            break;
                        case self::ERROR_CODE_ALREADY_IN_PROGRESS:
                            $this->message = 'bankid.'.self::RFA4;

                            break;
                        case self::ERROR_CODE_REQUEST_TIMEOUT:
                        case self::ERROR_CODE_MAINTENANCE:
                        case self::ERROR_CODE_INTERNAL_ERROR:
                            $this->message = 'bankid.'.self::RFA5;

                            break;
                        default:
                            $this->message = 'bankid.'.self::RFA22;

                            break;
                    }
                }

                break;
        }
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return null|array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return null|string
     */
    public function getOrderRef()
    {
        return $this->body['orderRef'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getPersonalNumber()
    {
        return $this->body['completionData']['user']['personalNumber'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getErrorCode()
    {
        return $this->body['errorCode'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getErrorDetails()
    {
        return $this->body['details'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getHintCode()
    {
        return $this->body['hintCode'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getAutoStartToken()
    {
        return $this->body['autoStartToken'] ?? null;
    }
}
