<?php

namespace DiscountsService\App\Discounts\Rules;

use Phake;
use PHPUnit\Framework\TestCase;
use DiscountsService\App\Products\Repository\ProductRepository;

class SwitchesCategoryTest extends TestCase
{
    protected $order;
    protected $wrongOrder;
    protected $product;

    protected $productRepository;
    protected $switchesCategoryRule;

    public function setUp()
    {
        $this->order = [
            'id' => '1',
            'customer-id' => '1',
            'items' => [
                [
                    'product-id' => 'B102',
                    'quantity' => '10',
                    'unit-price' => '4.99',
                    'total' => '49.90'
                ]
            ],
            'total' => '49.90',
        ];

        $this->wrongOrder = [
            'id' => '1',
            'customer-id' => '1',
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

        $this->product = [
            'id' => 'B102',
            'description' => 'Press button',
            'category' => '2',
            'price' => '4.99',
        ];

        $this->productRepository = Phake::mock(ProductRepository::class);
        Phake::when($this->productRepository)
            ->findById(Phake::anyParameters())
            ->thenReturn($this->product);
        
        $this->switchesCategoryRule = new SwitchesCategory($this->productRepository);
    }

    public function testDiscountRuleReturnsFloat()
    {
        $this->assertTrue(is_float($this->switchesCategoryRule->getDiscount($this->order)));
    }

    public function testDiscountRuleReturnsCorrectValue()
    {
        $this->assertEquals($this->switchesCategoryRule->getDiscount($this->order), 4.99);
    }

    public function testDiscountRuleReturnsZero()
    {   
        $this->assertEquals($this->switchesCategoryRule->getDiscount($this->wrongOrder), 0.00);
    }
}
