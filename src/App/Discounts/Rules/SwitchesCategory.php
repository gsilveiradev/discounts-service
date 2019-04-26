<?php

namespace DiscountsService\App\Discounts\Rules;

use DiscountsService\App\Discounts\CalculationInterface;
use DiscountsService\App\Products\Repository\ProductRepository;

class SwitchesCategory implements CalculationInterface
{
    const SWITCHES_CATEGORY_ID = 2;
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

            // If the product is from Switches category, then it calculates the discount
            // based on how many buckets of 6 units in quantity does it have
            if (!empty($product) && $product['category'] == self::SWITCHES_CATEGORY_ID) {
                $multiplier = floor($item['quantity'] / 6);
                
                if ($multiplier > 0) {
                    $discount = floatval($product['price']) * $multiplier;
                }
            }
        }
        return $discount;
    }
}
