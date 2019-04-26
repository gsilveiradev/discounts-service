<?php

namespace DiscountsService\App\Discounts\Rules;

use DiscountsService\App\Discounts\CalculationInterface;
use DiscountsService\App\Customers\Repository\CustomerRepository;

class UserTotalRevenue implements CalculationInterface
{
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
                return floatval($order['total']) * 0.10;
            }
        }

        return 0.00;
    }
}
