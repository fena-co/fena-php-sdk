<?php


namespace FaizPay\PaymentSDK\Tools;


class VerifyUKAccount extends Tools
{
    private $link = "aHR0cHM6Ly9wYXltZW50LmZhaXpwYXkuY29tL3Rvb2xzL3ZlcmlmeS11ay1hY2NvdW50";

    public function verify($sortCode, $accountNumber)
    {
        $errorResponse = [
            'type' => 'error',
            'message' => 'Authorization failed'
        ];
        if ($this->token == '') {
            return $errorResponse;
        }

        $result = $this->httpCall(base64_decode($this->link), [
            'sortCode' => "{$sortCode}",
            'accountNumber' => "{$accountNumber}"
        ], $this->token);

        if (!isset($result['type'])) {
            return $errorResponse;
        }

        if ($result['type'] != 'success') {
            return $errorResponse;
        }
        return ['type' => 'success', 'result' => $result['result']];
    }
}