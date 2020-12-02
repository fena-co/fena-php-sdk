FaizPay PHP Payment SDK
=======
SDK for working with FaizPay payment APIs.


Installation
------------

Use composer to manage your dependencies and download FaizPay PHP Payment SDK:

```bash
composer require faizpay/php-payment-sdk
```

__New Payment__
```php
use FaizPay\PaymentSDK\Connection;
use FaizPay\PaymentSDK\Payment;

$connection = new Connection(
                    $terminalId = '8afa74ae-6ef9-48bb-93b2-9fe8be53db50',
                    $terminalSecret = '55d7d5ed-be22-4321-bb3f-aec8524d8be2'
);

$payment = new Payment(
    $connection,
    $orderId = 'AA-11', 
    $amount = '10.00'
);
 
$payment->process($redirectBrowser  = true);

// OR

$url = $payment->process($redirectBrowser  = false);
```

__Notification Handling__

```php
use FaizPay\PaymentSDK\Connection;
use FaizPay\PaymentSDK\NotificationHandler;

$connection = new Connection($terminalId, $terminalSecret);
$notificationHandler = new NotificationHandler($connection, $token = $_POST['token']);

 // validate the given token
if (!$notificationHandler->isValidToken()) {
    echo "Invalid Token";
    die();
}

$orderId = $notificationHandler->getOrderID();

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
```

__Set User or Pre Selected Provider For New Payment__

```php
use FaizPay\PaymentSDK\Provider;
use FaizPay\PaymentSDK\User;

$user = new User();
$user->setEmail("john.doe@test.com");
$user->setFirstName("John");
$user->setLastName("Doe");
$user->setContactNumber("07000845953");

// payment object
$payment->setUser($user);

$provider = new Provider();
$provider->setProviderId('lloyds-bank');
$provider->setSortCode('123456');
$provider->setAccountNumber('12345678');

// payment object
$payment->setProvider($provider);
```

__Validate UK Account Number And Sort Number__
1. This tools checks if the given sort code and account number is valid
and enabled for faster payment network.

```php
use \FaizPay\PaymentSDK\Connection;
use \FaizPay\PaymentSDK\Tools\VerifyUKAccount;
$connection = new Connection($terminalId, $terminalSecret);

$test = new VerifyUKAccount($connection);

# invalid code

$result = $test->verify($sortCode = '938063', $accountNumber = '15764273');

print_r($result);

Array
(
    [type] => success
    [result] => false
)

```
