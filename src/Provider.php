<?php


namespace FaizPay\PaymentSDK;


class Provider
{
    /**
     * @var string | null
     */
    private $providerId;

    /**
     * @var string | null
     */
    private $sortCode;

    /**
     * @var string | null
     */
    private $accountNumber;

    /**
     * @return string|null
     */
    public function getProviderId()
    {
        return $this->providerId;
    }

    /**
     * @param string|null $providerId
     */
    public function setProviderId($providerId)
    {
        $this->providerId = trim($providerId);
    }

    /**
     * @return string|null
     */
    public function getSortCode()
    {
        return $this->sortCode;
    }

    /**
     * @param string|null $sortCode
     */
    public function setSortCode($sortCode)
    {
        $this->sortCode = trim($sortCode);
    }

    /**
     * @return string|null
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @param string|null $accountNumber
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = trim($accountNumber);
    }
}