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
        $connection = Connection::createConnection($terminalId, $terminalSecret);
        $notificationHandler = NotificationHandler::createNotificationHandler($connection, $token);

        $orderId = $notificationHandler->getOrderID();

        // fetch the order from your database
        $data = findFromDatabase($orderId);

        // if order is not found in system
        if (checkIfEntryFound($data)) {
            echo "Invalid Token";
            die();
        }

        // validate if the requested payment and terminal matches with token
        if (!$notificationHandler->validateAmount($data['amount'])) {
            echo "Invalid Token";
            die();
        }

        // all checks are passed - update the database to mark payment complete optionally add net amount and FaizPay ID
        updateDatabase($orderId, ['completed' => true, 'netAmount' => $notificationHandler]);
    }

}