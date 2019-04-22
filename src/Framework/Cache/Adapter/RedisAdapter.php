<?php

namespace DiscountsService\Framework\Cache\Adapter;

use Predis\Client;
use DiscountsService\Framework\Cache\CacheProviderInterface;

class RedisAdapter implements CacheProviderInterface
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function setValue(string $key, string $data)
    {
        $this->client->set($key, $data);
    }

    public function getValue(string $key)
    {
        return $this->client->get($key);
    }

    public function expireIn(string $key, int $seconds)
    {
        $this->client->expire($key, $seconds);
    }
}
