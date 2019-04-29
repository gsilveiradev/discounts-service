<?php

namespace DiscountsService\App\Orders\Validation\Exception;

class InvalidArrayStructureException extends \Exception
{
    public function __construct()
    {
        parent::__construct('The order has an invalid structure!', 1);
    }
}
