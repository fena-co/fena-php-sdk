<?php


use FaizPay\PaymentSDK\Errors;
use FaizPay\PaymentSDK\Item;
use FaizPay\PaymentSDK\User;
use PHPUnit\Framework\TestCase;
use \FaizPay\PaymentSDK\Error;

class ItemTest extends TestCase
{

    public function testErrorOnNameMoreThan255Characters()
    {
        $item = Item::createItem(str_repeat('a', 256), 1, '1.00');
        $this->assertInstanceOf(Error::class, $item);
        $this->assertEquals($item->getMessage(), Errors::CODE_19[1]);
    }


    public function testErrorOnQuantityLessThan1()
    {
        $item = Item::createItem('Cup', 0, '1.00');
        $this->assertInstanceOf(Error::class, $item);
        $this->assertEquals($item->getMessage(), Errors::CODE_20[1]);

        $item = Item::createItem('Cup', -10, '1.00');
        $this->assertInstanceOf(Error::class, $item);
        $this->assertEquals($item->getMessage(), Errors::CODE_20[1]);
    }

    public function testErrorOnZeroAmount()
    {
        $item = Item::createItem('Cup', 1, '0.00');
        $this->assertInstanceOf(Error::class, $item);
        $this->assertEquals($item->getMessage(), Errors::CODE_21[1]);

    }

    public function testErrorOnEmptyAmount()
    {
        $item = Item::createItem('Cup', 1, '');
        $this->assertInstanceOf(Error::class, $item);
        $this->assertEquals($item->getMessage(), Errors::CODE_21[1]);
    }

    public function testErrorOnLessThanZeroAmount()
    {
        $item = Item::createItem('Cup', 1, '-1.00');
        $this->assertInstanceOf(Error::class, $item);
        $this->assertEquals($item->getMessage(), Errors::CODE_21[1]);
    }

    public function testErrorOnMoreOrLessThan2DecimalPlacesForAmount()
    {
        $item = Item::createItem('Cup', 1, '0.00000000001');
        $this->assertInstanceOf(Error::class, $item);
        $this->assertEquals($item->getMessage(), Errors::CODE_22[1]);

        $item = Item::createItem('Cup', 1, '1');
        $this->assertInstanceOf(Error::class, $item);
        $this->assertEquals($item->getMessage(), Errors::CODE_22[1]);
    }

    public function testErrorOnValidAmount()
    {
        $item = Item::createItem('Cup', 1, 'abc');
        $this->assertInstanceOf(Error::class, $item);
        $this->assertEquals($item->getMessage(), Errors::CODE_22[1]);

        $item = Item::createItem('Cup', 1, 'abc.00');
        $this->assertInstanceOf(Error::class, $item);
        $this->assertEquals($item->getMessage(), Errors::CODE_22[1]);
    }

    public function testGetName()
    {
        $item = Item::createItem('Cup', 1, '1.00');
        $this->assertEquals('Cup', $item->getName());
    }

    public function testGetQuantity()
    {
        $item = Item::createItem('Cup', 1, '2.00');
        $this->assertEquals(1, $item->getQuantity());
    }

    public function testGetAmount()
    {
        $item = Item::createItem('Cup', 1, '1.00');
        $this->assertEquals('1.00', $item->getAmount());
    }


    public function testGetToArray()
    {
        $item = Item::createItem('Cup', 1, '1.00');
        $this->assertEquals(
            [
                'name' => 'Cup',
                'quantity' => 1,
                'amount' => '1.00'
            ], $item->toArray());
    }

}
