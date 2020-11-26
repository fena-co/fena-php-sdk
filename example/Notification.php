<?php


use FaizPay\PaymentSDK\Connection;
use FaizPay\PaymentSDK\NotificationHandler;

class Notification
{
    public function handleCallBack()
    {
        if (!isset($_POST['token'])) {
            die();
        }

        $terminalId = '8afa74ae-6ef9-48bb-93b2-9fe8be53db50';
        $terminalSecret = '55d7d5ed-be22-4321-bb3f-aec8524d8be2';
        $token = $_POST['token'];
        $connection = new Connection($terminalId, $terminalSecret);
        $notificationHandler = new NotificationHandler($connection, $token);

        // validate the given token
        if (!$notificationHandler->isValidToken()) {
            echo "Invalid Token";
            die();
        }

        $orderId = $notificationHandler->getOrderID();

        $db = null;  // some sort of database connector

        // fetch the order from your database
        $data = $db->fetch("select * from orders where id = ?", [$orderId]);

        // if order is not found in system
        if ($data == null) {
            echo "Invalid Token";
            die();
        }

        // validate if the requested payment and terminal matches with token
        if (!$notificationHandler->validatePayment($data['amount'])) {
            echo "Invalid Token";
            die();
        }

        // all checks are passed - update the database to mark payment complete optionally add net amount and FaizPay ID
        $db->update("UPDATE orders completed=1, external_id=?, net_amount=? WHERE id = ?", [$notificationHandler->getId(), $notificationHandler->getNetAmount(), $orderId]);
    }

}