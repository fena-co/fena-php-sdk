<?php

namespace FaizPay\PaymentSDK;

final class Connection
{
    /**
     * @var string
     */
    protected $terminalId;

    /**
     * @var string
     */
    protected $terminalSecret;

    /**
     * @param $terminalId string terminal id from faizpay portal
     * @param $terminalSecret string terminal secret from faizpay portal
     * @return Error|Connection
     */
    public static function createConnection(string $terminalId, string $terminalSecret)
    {
        if (!self::validate($terminalId)) {
            return new Error(Errors::CODE_1);
        }

        if (!self::validate($terminalSecret)) {
            return new Error(Errors::CODE_2);
        }
        return new Connection($terminalId, $terminalSecret);
    }

    /**
     * @param $uuid
     * @return bool
     */
    private static function validate($uuid): bool
    {
        if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
            return false;
        }
        return true;
    }


    /**
     * Connection constructor.
     * @param string $terminalId
     * @param string $terminalSecret
     */
    private function __construct(string $terminalId, string $terminalSecret)
    {
        $this->terminalId = $terminalId;
        $this->terminalSecret = $terminalSecret;
    }


    /**
     * @return string
     */
    public function getTerminalSecret(): string
    {
        return $this->terminalSecret;
    }

    /**
     * @return string
     */
    public function getTerminalId(): string
    {
        return $this->terminalId;
    }
}