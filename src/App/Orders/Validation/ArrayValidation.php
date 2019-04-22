<?php

namespace DiscountsService\App\Orders\Validation;

class ArrayValidation
{
    public function isValid(array $data)
    {
        if (empty($data)) {
            return false;
        }

        if (!array_key_exists('id', $data) ||
            !array_key_exists('customer-id', $data) ||
            !array_key_exists('items', $data) ||
            !array_key_exists('total', $data) ||
            empty($data['items'])) {
            return false;
        }

        if (!empty($data['items'])) {
            $itemsIsValid = true;

            foreach ($data['items'] as $item) {
                if (!array_key_exists('product-id', $item) ||
                    !array_key_exists('quantity', $item) ||
                    !array_key_exists('unit-price', $item) ||
                    !array_key_exists('total', $item)) {
                    return false;
                }
            }
        }

        return true;
    }
}
