<?php

namespace FaizPay\PaymentSDK;


class User
{
    /**
     * @var string | null
     */
    private $email;

    /**
     * @var string | null
     */
    private $firstName;

    /**
     * @var string | null
     */
    private $lastName;

    /**
     * @var string | null
     */
    private $contactNumber;

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @throws \Exception
     */
    public function setEmail($email)
    {
        if ($email != '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email is given');
        }
        $this->email = trim($email);
    }

    /**
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = trim($firstName);
    }

    /**
     * @return string|null
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = trim($lastName);
    }

    /**
     * @return string|null
     */
    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    /**
     * @param string|null $contactNumber
     */
    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = trim($contactNumber);
    }
}