<?php

namespace DiscountsService\App\Discounts;

class Calculation implements CalculationInterface
{
    protected $rule;
    
    public function __construct(CalculationInterface $rule)
    {
        $this->rule = $rule;
    }

    public function getDiscount(array $order)
    {
        return $this->rule->getDiscount($order);
    }
}
