<?php

namespace DiscountsService\App\Discounts;

interface CalculationInterface
{
    /**
     * Get a discount for an order
     * @param array $order the order to have discount
     */
    public function getDiscount(array $order);
}
