<?php


namespace FaizPay\PaymentSDK;


use FaizPay\PaymentSDK\Helper\NumberFormatter;
use Firebase\JWT\JWT;

class Payment
{
    private $alg = "HS512";
    private $endpoint = 'https://app.faizpay.com/pay?token=';
    private $tokenExpiry = (60 * 120); // 2 hours
    protected $connection;
    protected $orderId;
    protected $amount;
    protected $user;
    protected $provider;
    protected $items = [];
    protected $deliveryAddress;


    /**
     * @param Connection $connection
     * @param string $orderId client order id
     * @param string $amount amount in 2 decimal places
     * @return Error|Payment
     */
    public static function createPayment(
        Connection $connection,
        string $orderId,
        string $amount
    )
    {
        $orderId = trim($orderId);
        // validate order Id
        if ($orderId == '') {
            return new Error(Errors::CODE_3);
        }
        // validate amount
        if ($amount == '' || $amount == '0.00' || (float)$amount < 0) {
            return new Error(Errors::CODE_4);
        }

        // validate amount
        if (!NumberFormatter::validateTwoDecimals($amount)) {
            return new Error(Errors::CODE_5);
        }

        // validate order is greater than 255
        if (strlen($orderId) > 255) {
            return new Error(Errors::CODE_6);
        }

        return new Payment($connection, $orderId, $amount);
    }

    /**
     * Payment constructor.
     * @param Connection $connection
     * @param $orderId string unique order id
     * @param $amount  string amount requested
     */
    private function __construct(
        Connection $connection,
        string $orderId,
        string $amount
    )
    {
        $this->connection = $connection;
        $this->orderId = $orderId;
        $this->amount = $amount;
    }

    /**
     * Set the optional user for payment
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): Payment
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Set the optional provider for payment
     * @param Provider|null $provider
     * @return $this
     */
    public function setProvider(?Provider $provider): Payment
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * add a new item to a order
     * @param Item $item
     * @return $this
     */
    public function addItem(Item $item): Payment
    {
        $this->items[] = $item->toArray();
        return $this;
    }

    /**
     * Set the optional delivery Address for payment
     * @param DeliveryAddress|null $deliveryAddress
     * @return $this
     */
    public function setDeliveryAddress(?DeliveryAddress $deliveryAddress): Payment
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }

    /**
     * process the payment
     * @param false $redirectBrowser
     * @return Error|string
     */
    public function process($redirectBrowser = false)
    {
        $currentUnixTimeStamp = time();
        $payload = [
            'iat' => $currentUnixTimeStamp,
            'exp' => $currentUnixTimeStamp + $this->tokenExpiry,
            'terminalID' => $this->connection->getTerminalId(),
            'orderID' => $this->orderId,
            'amount' => $this->amount,
            'email' => '',
            'firstName' => '',
            'lastName' => '',
            'contactNumber' => '',
            'bankID' => '',
            'sortCode' => '',
            'accountNumber' => '',
            'address' => '',
            'items' => $this->items
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

        if ($this->deliveryAddress instanceof DeliveryAddress) {
            $payload['address'] =
                [
                    'line1' => (string)$this->deliveryAddress->getAddressLine1(),
                    'line2' => (string)$this->deliveryAddress->getAddressLine2(),
                    'postCode' => (string)$this->deliveryAddress->getPostCode(),
                    'city' => (string)$this->deliveryAddress->getCity(),
                    'country' => (string)$this->deliveryAddress->getCountry()
                ];
        }


        $jwt = JWT::encode($payload, $this->connection->getTerminalSecret(), $this->alg);
        $url = $this->endpoint . $jwt;

        if ($redirectBrowser) {
            header("Location: {$jwt}");
            die();
        }
        return $url;
    }
}