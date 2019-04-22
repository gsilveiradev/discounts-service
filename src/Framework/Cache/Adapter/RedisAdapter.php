<?php

namespace DiscountsService\Framework\Cache\Adapter;

use Predis\Client;
use DiscountsService\Framework\Cache\CacheProviderInterface;

class RedisAdapter implements CacheProviderInterface
{
    protected $client;
    protected $settings;

    public function __construct(Client $client, array $settings)
    {
        $this->client = $client;
        $this->settings = $settings;
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
