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
     * Connection constructor.
     * @param $terminalId string terminal id from faizpay portal
     * @param $terminalSecret string terminal secret from faizpay portal
     * @throws \Exception
     */
    public function __construct($terminalId, $terminalSecret)
    {
        if (!$this->validate($terminalId)) {
            throw new \Exception('Invalid Terminal ID - Should be valid UUID4');
        }

        if (!$this->validate($terminalSecret)) {
            throw new \Exception('Invalid Terminal Secret - Should be valid UUID4');
        }

        $this->terminalId = $terminalId;
        $this->terminalSecret = $terminalSecret;
    }

    protected function validate($uuid)
    {
        if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getTerminalSecret()
    {
        return $this->terminalSecret;
    }

    /**
     * @return string
     */
    public function getTerminalId()
    {
        return $this->terminalId;
    }
}