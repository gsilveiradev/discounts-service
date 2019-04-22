<?php

namespace DiscountsService\Framework;

use DiscountsService\Framework\Cache\CacheProviderInterface;

class Repository
{
    protected $cacheLimit = 3600;
    protected $guzzle;
    protected $cache;

    public function __construct(\GuzzleHttp\Client $guzzle, CacheProviderInterface $cacheProvider)
    {
        $this->guzzle = $guzzle;
        $this->cache = $cacheProvider;
    }

    protected function setCachedResult(string $key, string $data)
    {
        $this->cache->setValue($key, $data);
        $this->cache->expireIn($key, $this->cacheLimit);
    }

    protected function getCachedResult(string $key): ?array
    {
        if ($this->cache->getValue($key)) {
            return json_decode($this->cache->getValue($key), true);
        }

        return null;
    }
}
