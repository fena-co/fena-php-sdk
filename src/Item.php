<?php

namespace FaizPay\PaymentSDK;

use FaizPay\PaymentSDK\Helper\NumberFormatter;

class Item
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var string
     */
    private $amount;

    /**
     * @param string $name
     * @param int $quantity
     * @param string $amount
     * @return Error|Item
     */
    public static function createItem(string $name,
                                      int $quantity,
                                      string $amount
    )
    {
        $name = trim($name);
        // validate greater than 255
        if (strlen($name) > 255) {
            return new Error(Errors::CODE_19);
        }

        // if quantity is less than  1
        $quantity = trim($quantity);
        if ($quantity < 1) {
            return new Error(Errors::CODE_20);
        }

        // validate amount
        if ($amount == '' || $amount == '0.00' || (float)$amount < 0) {
            return new Error(Errors::CODE_21);
        }

        // validate amount
        if (!NumberFormatter::validateTwoDecimals($amount)) {
            return new Error(Errors::CODE_22);
        }
        return new Item($name, $quantity, $amount);
    }

    /**
     * Item constructor.
     * @param string $name
     * @param int $quantity
     * @param string $amount
     */
    private function __construct(string $name, int $quantity, string $amount)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }


    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'quantity' => $this->getAmount(),
            'amount' => $this->getAmount()
        ];
    }

}