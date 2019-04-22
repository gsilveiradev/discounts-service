<?php

namespace DiscountsService\Framework\Cache;

interface CacheProviderInterface
{
    public function setValue(string $key, string $data);

    public function getValue(string $key);

    public function expireIn(string $key, int $seconds);
}
