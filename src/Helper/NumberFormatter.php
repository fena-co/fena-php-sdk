<?php

namespace FaizPay\PaymentSDK\Helper;

class NumberFormatter
{
    public static function formatNumber($number)
    {
        $number = (string)$number;
        return number_format($number, "2", ".", "");
    }
}