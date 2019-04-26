<?php

namespace DiscountsService\App\Discounts\Rules;

use DiscountsService\App\Discounts\CalculationInterface;
use DiscountsService\App\Products\Repository\ProductRepository;

class ToolsCategory implements CalculationInterface
{
    const TOOLS_CATEGORY_ID = 1;
    const MIN_QUANTITY_REQUIRED = 2;
    const DISCOUNT_PERCENTAGE_MULTIPLIER = 0.20;

    protected $productsRepository;

    public function __construct(ProductRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function getDiscount(array $order): float
    {
        $discount = 0.00;
        $cheapestPrice = 999999999999999;
        $cheapestQuantity = 0;

        foreach ($order['items'] as $key => $item) {
            $product = $this->productsRepository->findById($item['product-id']);

            if (empty($product) || $product['category'] != self::TOOLS_CATEGORY_ID) {
                continue;
            }

            if ($item['quantity'] < self::MIN_QUANTITY_REQUIRED) {
                continue;
            }

            if ((floatval($product['price']) * intval($item['quantity'])) < $cheapestPrice) {
                $cheapestPrice = floatval($product['price']);
                $cheapestQuantity = intval($item['quantity']);
            }
        }

        if ($cheapestQuantity > 0) {
            $discount = ($cheapestPrice * $cheapestQuantity) * self::DISCOUNT_PERCENTAGE_MULTIPLIER;
        }

        return $discount;
    }
}
