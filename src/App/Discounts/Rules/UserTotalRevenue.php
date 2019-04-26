<?php

namespace DiscountsService\App\Discounts\Rules;

use DiscountsService\App\Discounts\CalculationInterface;
use DiscountsService\App\Customers\Repository\CustomerRepository;

class UserTotalRevenue implements CalculationInterface
{
    const DISCOUNT_PERCENTAGE_MULTIPLIER = 0.10;

    protected $customersRepository;

    public function __construct(CustomerRepository $customersRepository)
    {
        $this->customersRepository = $customersRepository;
    }
    
    public function getDiscount(array $order): float
    {
        $customer = $this->customersRepository->findById($order['customer-id']);

        if (!empty($customer)) {
            if (floatval($customer['revenue']) > 1000) {
                return floatval($order['total']) * self::DISCOUNT_PERCENTAGE_MULTIPLIER;
            }
        }

        return 0.00;
    }
}
