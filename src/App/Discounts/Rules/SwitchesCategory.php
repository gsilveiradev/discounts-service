<?php

namespace DiscountsService\App\Discounts\Rules;

use DiscountsService\App\Discounts\CalculationInterface;
use DiscountsService\App\Products\Repository\ProductRepository;

class SwitchesCategory implements CalculationInterface
{
    const SWITCHES_CATEGORY_ID = 2;
    const MIN_QUANTITY_REQUIRED = 5;

    protected $productsRepository;

    public function __construct(ProductRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function getDiscount(array $order): float
    {
        $discount = 0.00;

        foreach ($order['items'] as $key => $item) {
            $product = $this->productsRepository->findById($item['product-id']);

            if (!empty($product) && $product['category'] == self::SWITCHES_CATEGORY_ID) {
                if (intval($item['quantity']) > self::MIN_QUANTITY_REQUIRED) {
                    $discount = floatval($product['price']);
                }
            }
        }
        
        return $discount;
    }
}
