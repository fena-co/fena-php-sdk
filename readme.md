FaizPay PHP Payment SDK
=======
SDK for working with FaizPay payment APIs.

Requirements
------------
PHP 7.0.0 and later.

Installation
------------

You can install the bindings via Composer. Run the following command:

```bash
composer require faizpay/php-payment-sdk
```

To use the bindings, use Composer's autoload:

```php
require_once('vendor/autoload.php');
```

Dependencies
------------
-   [`json`](https://secure.php.net/manual/en/book.json.php)
-   [`mbstring`](https://secure.php.net/manual/en/book.mbstring.php) (Multibyte String)


Getting Started
------------
Simple new payment looks like:

```php
use FaizPay\PaymentSDK\Connection;
use FaizPay\PaymentSDK\Payment;

$connection = Connection::createConnection(
    $terminalId = '8afa74ae-6ef9-48bb-93b2-9fe8be53db50',
    $terminalSecret = '55d7d5ed-be22-4321-bb3f-aec8524d8be2'
);

$payment = Payment::createPayment(
    $connection,
    $orderId = 'AA-11', 
    $amount = '10.00'
);
 
$payment->process($redirectBrowser  = true);

// OR

$url = $payment->process($redirectBrowser  = false);
```

__Webhook / Notification Handling__

```php
use FaizPay\PaymentSDK\Connection;
use FaizPay\PaymentSDK\NotificationHandler;

$connection = Connection::createConnection($terminalId, $terminalSecret);
$notificationHandler = NotificationHandler::createNotificationHandler($connection, $token = $_POST['token']);

// extract the order id
$orderId = $notificationHandler->getOrderID();

// fetch the order from your database
$data = findFromDatabase($orderId);

// if order is not found in system
if (checkIfEntryFound($data)) {
    echo "Invalid Token";
    die();
}

// validate if the requested payment matches with token
if (!$notificationHandler->validateAmount($data['amount'])) {
    echo "Invalid Token";
    die();
}

// all checks are passed - update the database to mark payment complete
updateDatabase($orderId, ['completed' => true]);
```

__Optional: Set User or Pre Selected Provider For New Payment__

```php
use FaizPay\PaymentSDK\Connection;
use FaizPay\PaymentSDK\Payment;
use FaizPay\PaymentSDK\Provider;
use FaizPay\PaymentSDK\User;

$connection = Connection::createConnection($terminalId, $terminalSecret);
$payment = Payment::createPayment(
    $connection,
    $orderId = 'AA-11', 
    $amount = '10.00'
);

$user = User::createUser(
    $email = 'john.doe@test.com',
    $firstName = 'John',
    $lastName = 'Doe',
    $contactNumber = '07000845953'
);
$payment->setUser($user);

$provider = Provider::createProvider(
    $providerId = 'lloyds-bank',
    $sortCode = '123456',
    $accountNumber = '12345678'
);
$payment->setProvider($provider);
```