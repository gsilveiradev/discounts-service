<?php

namespace DiscountsService\App;

class Repository
{
    protected $cacheLimit = 3600;
    protected $guzzle;
    protected $redis;

    public function __construct(\GuzzleHttp\Client $guzzle, \Predis\Client $redis)
    {
        $this->guzzle = $guzzle;
        $this->redis = $redis;
    }

    protected function setCachedResult(string $key, string $data)
    {
        $this->redis->set($key, $data);
        $this->redis->expire($key, $this->cacheLimit);
    }

    protected function getCachedResult(string $key): ?array
    {
        if ($this->redis->get($key)) {
            return json_decode($this->redis->get($key), true);
        }

        return null;
    }
}
