<?php


namespace FaizPay\PaymentSDK;


use FaizPay\PaymentSDK\Helper\NumberFormatter;
use Firebase\JWT\JWT;

class NotificationHandler
{
    private $alg = "HS512";
    protected $connection;
    protected $token;

    /**
     * NotificationHandler constructor.
     * @param Connection $connection
     * @param $token
     */
    public function __construct(Connection $connection, $token)
    {
        $this->connection = $connection;
        try {
            $token = JWT::decode($token, $this->connection->getTerminalSecret(), [$this->alg]);
            if ($token instanceof \stdClass) {
                $this->token = json_decode(json_encode($token), true);
            }
        } catch (\Exception $exception) {

        }
    }

    /**
     * verify if the given token is valid
     * @return bool
     */
    public function isValidToken()
    {
        if (!is_array($this->token) ||
            !isset($this->token['id']) ||
            !isset($this->token['orderID']) ||
            !isset($this->token['requestAmount']) ||
            !isset($this->token['netAmount']) ||
            !isset($this->token['terminal'])
        ) {
            return false;
        }
        return true;
    }

    /**
     * return the order id sent in the payment
     * should be used to read the payment record client system
     * @return string
     */
    public function getOrderID()
    {
        return $this->token['orderID'];
    }

    /**
     * validates if requested amount and terminal matches the payment
     * @param $requestedAmount string original amount requested for user to pay
     * @return bool
     */
    public function validatePayment($requestedAmount)
    {
        // verify the terminal
        if ($this->token['terminal'] != $this->connection->getTerminalId()) {
            return false;
        }

        // verify the amount
        if (NumberFormatter::formatNumber($this->token['requestAmount']) != NumberFormatter::formatNumber($requestedAmount)) {
            return false;
        }
        return true;
    }


    /**
     * returns the amount requested
     * @return string
     */
    public function getRequestedAmount()
    {
        return $this->token['requestAmount'];
    }

    /**
     * returns the amount user actually paid
     * @return string
     */
    public function getNetAmount()
    {
        return $this->token['netAmount'];
    }

    /**
     * returns the FaizPay payment ID
     * @return string
     */
    public function getId()
    {
        return $this->token['id'];
    }

    /**
     * returns the payment terminal ID
     * @return string
     */
    public function getTerminal()
    {
        return $this->token['terminal'];
    }

}