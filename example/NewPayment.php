<?php


use FaizPay\PaymentSDK\Connection;
use FaizPay\PaymentSDK\Payment;
use FaizPay\PaymentSDK\Provider;
use FaizPay\PaymentSDK\User;

class NewPayment
{

    public function createNewPayment()
    {
        $terminalId = '8afa74ae-6ef9-48bb-93b2-9fe8be53db50';
        $terminalSecret = '55d7d5ed-be22-4321-bb3f-aec8524d8be2';
        $orderId = 'ABC';
        $amount = '10.00';

        $connection = new Connection($terminalId, $terminalSecret);

        $payment = new Payment(
            $connection,
            $orderId,
            $amount
        );

        $user = new User();
        $user->setEmail("john.doe@test.com");
        $user->setFirstName("John");
        $user->setLastName("Doe");
        $user->setContactNumber("07000845953");
        $payment->setUser($user);

        $provider = new Provider();
        $provider->setProviderId('lloyds-bank');
        $provider->setSortCode('123456');
        $provider->setAccountNumber('12345678');
        $payment->setProvider($provider);

        $url = $payment->process($redirectBrowser = false);
    }


}