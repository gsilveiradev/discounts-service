<?php

namespace DiscountsService\App\Orders\Validation;

class ArrayValidationTest extends \PHPUnit\Framework\TestCase
{
    protected $arrayValidation;
    protected $invalidOrder;
    protected $validOrder;

    public function setUp()
    {
        $this->arrayValidation = new ArrayValidation();

        $this->invalidOrder = [
            'id' => '',
            'customer-id' => '',
            'total' => ''
        ];
        $this->validOrder = [
            'id' => '',
            'customer-id' => '',
            'items' => [
                ['product-id' => 'A101']
            ],
            'total' => '',
        ];
    }

    public function testOrderIsInvalid()
    {
        $this->assertFalse($this->arrayValidation->isValid($this->invalidOrder));
        $this->assertFalse($this->arrayValidation->isValid(array_merge($this->invalidOrder, ['items' => ''])));
    }

    public function testOrderIsValid()
    {
        $this->assertTrue($this->arrayValidation->isValid($this->validOrder));
    }
}
