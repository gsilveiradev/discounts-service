<?php

namespace DiscountsService\App\Orders\Service;

use DiscountsService\App\Orders\Validation\ArrayValidation;
use DiscountsService\App\Orders\Validation\Exception\InvalidArrayStructureException;
use DiscountsService\App\Discounts\Calculation;
use DiscountsService\App\Discounts\CalculationInterface;
use DiscountsService\App\Discounts\Rules;
use DiscountsService\App\Customers\Repository\CustomerRepository;
use DiscountsService\App\Products\Repository\ProductRepository;

class OrderDiscountsCalculator
{
    protected $order;
    protected $customersRepository;
    protected $productsRepository;

    public function __construct(CustomerRepository $customersRepository, ProductRepository $productsRepository)
    {
        $this->customersRepository = $customersRepository;
        $this->productsRepository = $productsRepository;
    }

    public function fromOrder(array $order): OrderDiscountsCalculator
    {
        $this->order = $order;
        $this->order['subtotal'] = $this->order['total'];
        $this->order['discount'] = '0.00';

        return $this;
    }

    public function applyDiscounts()
    {
        if (!(new ArrayValidation())->isValid($this->order)) {
            throw new InvalidArrayStructureException();
        }

        // Apply discounts for Switches category rule
        $switchesCategoryCalculation = new Calculation(new Rules\SwitchesCategory($this->productsRepository));
        $order = $this->applyDiscount($switchesCategoryCalculation);

        // Apply discounts for Tools category rule
        $toolsCategoryCalculation = new Calculation(new Rules\ToolsCategory($this->productsRepository));
        $order = $this->applyDiscount($toolsCategoryCalculation);
        
        // Apply discounts for user total revenue rule
        $userTotalRevenueCalculation = new Calculation(new Rules\UserTotalRevenue($this->customersRepository));
        $order = $this->applyDiscount($userTotalRevenueCalculation);

        return $this->order;
    }

    public function applyDiscount(CalculationInterface $calculation): array
    {
        $discount = $calculation->getDiscount($this->order);

        if ($discount > 0) {
            $this->order['discount'] = number_format(floatval($this->order['discount']) + $discount, 2, '.', '');
            $this->order['total'] = number_format(floatval($this->order['total']) - $discount, 2, '.', '');
        }

        return $this->order;
    }
}
