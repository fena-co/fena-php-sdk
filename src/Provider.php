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
     * @throws \Exception
     */
    public function setSortCode($sortCode)
    {
        if ($sortCode != '' && strlen(trim($sortCode)) != '6') {
            throw new \Exception('Invalid Sort Code');
        }
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
     * @throws \Exception
     */
    public function setAccountNumber($accountNumber)
    {
        if ($accountNumber != '' && strlen(trim($accountNumber)) != '8') {
            throw new \Exception('Invalid Account Number');
        }
        $this->accountNumber = trim($accountNumber);
    }
}