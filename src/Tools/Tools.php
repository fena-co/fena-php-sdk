<?php


namespace FaizPay\PaymentSDK\Tools;


use FaizPay\PaymentSDK\Connection;

abstract class Tools
{
    private $tokenLink = "aHR0cHM6Ly9hcGkuZmFpenBheS5jb20vbWVyY2hhbnQvbWVyY2hhbnQtdG9vbC10b2tlbg==";
    protected $connection;
    protected $token;

    function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->getAccessKey();
    }

    protected function getAccessKey()
    {
        $token = $this->httpCall(base64_decode($this->tokenLink), [
            'terminal' => $this->connection->getTerminalId(),
            'terminalSecret' => $this->connection->getTerminalSecret()
        ]);
        if (isset($token['token'])) {
            $this->token = $token['token'];
        }
    }

    protected function httpCall($url, $data, $authToken = null)
    {
        try {
            $ch = curl_init($url);
            $payload = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            $headers = ['Content-Type:application/json'];
            if (!is_null($authToken)) {
                $headers[] = "Authorization:{$authToken}";
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            return json_decode($result, true);
        } catch (\Exception $exception) {
            throw new \Exception('Unable to connect to api');
        }
    }

}