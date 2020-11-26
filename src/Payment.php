<?php


namespace FaizPay\PaymentSDK;


use FaizPay\PaymentSDK\Helper\NumberFormatter;
use Firebase\JWT\JWT;

class Payment
{
    private $alg = "HS512";
    private $endpoint = 'https://faizpay-staging.netlify.app/pay?token=';
    private $tokenExpiry = (60 * 120); // 2 hours
    protected $connection;
    protected $orderId;
    protected $amount;
    protected $user;
    protected $provider;

    /**
     * Payment constructor.
     * @param Connection $connection
     * @param $orderId string unique order id
     * @param $amount  string amount requested
     * @throws \Exception
     */
    public function __construct(
        Connection $connection,
        $orderId,
        $amount
    )
    {
        $this->connection = $connection;
        $this->orderId = $orderId;
        $this->amount = NumberFormatter::formatNumber($amount);

        // validate order Id
        if ($this->orderId == '') {
            throw new \Exception('Order ID cannot be empty');
        }

        // validate amount
        if ($this->amount == '' || $this->amount == '0.00') {
            throw new \Exception('Invalid Amount');
        }
    }

    /**
     * Set the optional user for payment
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Set the optional provider for payment
     * @param Provider $provider
     * @return $this
     */
    public function setProvider(Provider $provider)
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * process the payment
     * @param false $redirectBrowser
     * @return string
     * @throws \Exception
     */
    public function process($redirectBrowser = false)
    {
        $currentUnixTimeStamp = time();
        $payload = [
            'iat' => $currentUnixTimeStamp,
            'exp' => $currentUnixTimeStamp + $this->tokenExpiry,
            'terminalID' => $this->connection->getTerminalId(),
            'orderID' => $this->orderId,
            'amount' => $this->amount
        ];

        if ($this->user instanceof User) {
            $payload['email'] = (string)$this->user->getEmail();
            $payload['firstName'] = (string)$this->user->getFirstName();
            $payload['lastName'] = (string)$this->user->getLastName();
            $payload['contactNumber'] = (string)$this->user->getContactNumber();
        }

        if ($this->provider instanceof Provider) {
            $payload['bankID'] = (string)$this->provider->getProviderId();
            $payload['sortCode'] = (string)$this->provider->getSortCode();
            $payload['accountNumber'] = (string)$this->provider->getAccountNumber();
        }

        try {
            $jwt = JWT::encode($payload, $this->connection->getTerminalSecret(), $this->alg);
        } catch (\Exception  $exception) {
            throw new \Exception('Unable to generate a singed token' . $exception);
        }

        $url = $this->endpoint . $jwt;

        if ($redirectBrowser) {
            header("Location: {$jwt}");
            die();
        }
        return $url;
    }
}