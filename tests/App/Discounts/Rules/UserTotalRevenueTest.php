<?php

namespace DiscountsService\App\Discounts\Rules;

use Phake;
use PHPUnit\Framework\TestCase;
use DiscountsService\App\Customers\Repository\CustomerRepository;

class UserTotalRevenueTest extends TestCase
{
    protected $order;
    protected $customer;
    protected $wrongCustomer;

    protected $customerRepository;
    protected $userTotalRevenueRule;

    public function setUp()
    {
        $this->order = [
            'id' => '2',
            'customer-id' => '2',
            'items' => [
                [
                    'product-id' => 'B102',
                    'quantity' => '5',
                    'unit-price' => '4.99',
                    'total' => '24.95'
                ]
            ],
            'total' => '24.95',
        ];

        $this->customer = [
            'id' => '2',
            'name' => 'TeamLeader',
            'since' => '2015-01-15',
            'revenue' => '1505.95',
        ];

        $this->wrongCustomer = [
            'id' => '2',
            'name' => 'TeamLeader',
            'since' => '2015-01-15',
            'revenue' => '1000',
        ];

        $this->customerRepository = Phake::mock(CustomerRepository::class);
        Phake::when($this->customerRepository)
            ->findById(Phake::anyParameters())
            ->thenReturn($this->customer);
        
        $this->userTotalRevenueRule = new UserTotalRevenue($this->customerRepository);
    }

    public function testDiscountRuleReturnsFloat()
    {
        $this->assertTrue(is_float($this->userTotalRevenueRule->getDiscount($this->order)));
    }

    public function testDiscountRuleReturnsCorrectValue()
    {
        $this->assertEquals($this->userTotalRevenueRule->getDiscount($this->order), 2.495);
    }

    public function testDiscountRuleReturnsZero()
    {
        Phake::when($this->customerRepository)
            ->findById(Phake::anyParameters())
            ->thenReturn($this->wrongCustomer);
        
        $this->assertEquals($this->userTotalRevenueRule->getDiscount($this->order), 0.00);
    }
}
