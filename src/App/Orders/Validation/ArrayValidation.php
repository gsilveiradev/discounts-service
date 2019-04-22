<?php

namespace DiscountsService\App\Orders\Validation;

class ArrayValidation
{
    public function isValid(array $data)
    {
        if (empty($data)) {
            return false;
        }

        if (array_key_exists('id', $data) &&
            array_key_exists('customer-id', $data) &&
            array_key_exists('items', $data) &&
            array_key_exists('total', $data) &&
            !empty($data['items'])) {
            return true;
        }

        return false;
    }
}
