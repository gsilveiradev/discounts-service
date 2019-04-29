<?php

namespace DiscountsService\App\Discounts\Rules;

use Phake;
use PHPUnit\Framework\TestCase;
use DiscountsService\App\Products\Repository\ProductRepository;

class ToolsCategoryTest extends TestCase
{
    protected $order;
    protected $wrongOrder;
    protected $productOne;
    protected $productTwo;

    protected $productRepository;
    protected $toolsCategoryRule;

    public function setUp()
    {
        $this->order = [
            'id' => '3',
            'customer-id' => '3',
            'items' => [
                [
                    'product-id' => 'A101',
                    'quantity' => '2',
                    'unit-price' => '9.75',
                    'total' => '19.50'
                ],
                [
                    'product-id' => 'A102',
                    'quantity' => '1',
                    'unit-price' => '49.50',
                    'total' => '49.50'
                ]
            ],
            'total' => '69.00',
        ];

        $this->wrongOrder = [
            'id' => '3',
            'customer-id' => '3',
            'items' => [
                [
                    'product-id' => 'A101',
                    'quantity' => '1',
                    'unit-price' => '9.75',
                    'total' => '9.75'
                ],
                [
                    'product-id' => 'A102',
                    'quantity' => '1',
                    'unit-price' => '49.50',
                    'total' => '49.50'
                ]
            ],
            'total' => '59.25',
        ];

        $this->productOne = [
            'id' => 'A101',
            'description' => 'Screwdriver',
            'category' => '1',
            'price' => '9.75',
        ];

        $this->productTwo = [
            'id' => 'A102',
            'description' => 'Electric screwdriver',
            'category' => '1',
            'price' => '49.50',
        ];

        $this->productRepository = Phake::mock(ProductRepository::class);
        Phake::when($this->productRepository)
            ->findById('A101')
            ->thenReturn($this->productOne);
        Phake::when($this->productRepository)
            ->findById('A102')
            ->thenReturn($this->productTwo);
        
        $this->toolsCategoryRule = new ToolsCategory($this->productRepository);
    }

    public function testDiscountRuleReturnsFloat()
    {
        $this->assertTrue(is_float($this->toolsCategoryRule->getDiscount($this->order)));
    }

    public function testDiscountRuleReturnsCorrectValue()
    {
        $this->assertEquals($this->toolsCategoryRule->getDiscount($this->order), 3.90);
    }

    public function testDiscountRuleReturnsZero()
    {   
        $this->assertEquals($this->toolsCategoryRule->getDiscount($this->wrongOrder), 0.00);
    }
}
