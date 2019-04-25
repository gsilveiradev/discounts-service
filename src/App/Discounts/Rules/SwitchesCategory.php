<?php

namespace DiscountsService\App\Discounts\Rules;

use DiscountsService\App\Discounts\CalculationInterface;

class SwitchesCategory implements CalculationInterface
{
    public function getDiscount(array $order)
    {
        return "0.00";
    }
}
