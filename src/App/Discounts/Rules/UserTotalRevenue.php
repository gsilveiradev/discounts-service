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
    
    public function getDiscount(array $order)
    {
        $customer = $this->customersRepository->findById($order['customer-id']);

        if (!empty($customer)) {
            if (floatval($customer['revenue']) > 1000) {
                return (string) ($order['subtotal'] * 0.10);
            }
        }

        return "0.00";
    }
}
