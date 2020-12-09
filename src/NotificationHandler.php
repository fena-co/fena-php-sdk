<?php

namespace FaizPay\PaymentSDK;

use FaizPay\PaymentSDK\Helper\NumberFormatter;
use Firebase\JWT\JWT;

class NotificationHandler
{
    private static $alg = "HS512";
    protected $connection;
    protected $token;

    public static function createNotificationHandler(Connection $connection, $token)
    {
        try {
            $token = JWT::decode($token, $connection->getTerminalSecret(), [self::$alg]);
            if ($token instanceof \stdClass) {
                $token = json_decode(json_encode($token), true);
            }
        } catch (\Exception $exception) {
            return new Error(Errors::CODE_16);
        }


        // verify if token has field
        if (!is_array($token) ||
            !isset($token['id']) ||
            !isset($token['orderID']) ||
            !isset($token['requestAmount']) ||
            !isset($token['netAmount']) ||
            !isset($token['terminal'])
        ) {
            return new Error(Errors::CODE_17);
        }

        // validate the terminal
        if ($token['terminal'] != $connection->getTerminalId()) {
            return new Error(Errors::CODE_18);
        }

        return new NotificationHandler($connection, $token);
    }

    /**
     * NotificationHandler constructor.
     * @param Connection $connection
     * @param $token
     */
    private function __construct(Connection $connection, $token)
    {
        $this->connection = $connection;
        $this->token = $token;
    }


    /**
     * validates if requested amount matches the payment
     * @param $requestedAmount string original amount requested for user to pay
     * @return bool
     */
    public function validateAmount(string $requestedAmount): bool
    {
        if (!is_numeric($requestedAmount)) {
            return false;
        }
        // verify the amount
        if (NumberFormatter::formatNumber($this->token['requestAmount']) != NumberFormatter::formatNumber($requestedAmount)) {
            return false;
        }
        return true;
    }

    /**
     * return the order id sent in the payment
     * should be used to read the payment record client system
     * @return string
     */
    public function getOrderID(): string
    {
        return $this->token['orderID'];
    }


    /**
     * returns the amount requested
     * @return string
     */
    public function getRequestedAmount(): string
    {
        return $this->token['requestAmount'];
    }

    /**
     * returns the amount user actually paid
     * @return string
     */
    public function getNetAmount(): string
    {
        return $this->token['netAmount'];
    }

    /**
     * returns the FaizPay payment ID
     * @return string
     */
    public function getId(): string
    {
        return $this->token['id'];
    }

    /**
     * returns the payment terminal ID
     * @return string
     */
    public function getTerminal(): string
    {
        return $this->token['terminal'];
    }
}