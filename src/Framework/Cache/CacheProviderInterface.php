<?php

namespace DiscountsService\Framework\Cache;

interface CacheProviderInterface
{
    /**
     * Set a value in the cache
     * @param string $key the key to set
     * @param string $data the data to set
     */
    public function setValue(string $key, string $data);

    /**
     * Get a value from the cache
     * @param string $key the key to get
     * @return string the data
     */
    public function getValue(string $key);

    /**
     * Set a expiring time to the key
     * @param string $key the key to set
     * @param int $seconds the expiring time in seconds
     */
    public function expireIn(string $key, int $seconds);
}
