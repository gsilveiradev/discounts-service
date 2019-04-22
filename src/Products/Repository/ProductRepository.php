<?php

namespace DiscountsService\Products\Repository;

use DiscountsService\Framework\Repository;

class ProductRepository extends Repository
{
    protected $cachePrefix = 'products:';

    public function getAll(): ?array
    {
        $cacheKey = $this->cachePrefix.'all';

        if ($this->getCachedResult($cacheKey)) {
            return $this->getCachedResult($cacheKey);
        }

        $client = $this->guzzle;
        $res = $client->request(
            'GET',
            'https://raw.githubusercontent.com/teamleadercrm/coding-test/master/data/products.json'
        );

        $this->setCachedResult($cacheKey, $res->getBody());

        return $this->getCachedResult($cacheKey);
    }

    public function findById(string $id): ?array
    {
        $cacheKey = $this->cachePrefix.$id;

        if ($this->getCachedResult($cacheKey)) {
            return $this->getCachedResult($cacheKey);
        }

        $data = $this->getAll();
        $found = array_filter($data, function ($array) use ($id) {
            return $array['id'] == $id;
        });

        if (!empty($found)) {
            $this->setCachedResult($cacheKey, json_encode($found));

            return $this->getCachedResult($cacheKey);
        }

        return null;
    }

    public function findByCategoryId(int $id): ?array
    {
        $cacheKey = $this->cachePrefix.'category:'.$id;

        if ($this->getCachedResult($cacheKey)) {
            return $this->getCachedResult($cacheKey);
        }

        $data = $this->getAll();
        $found = array_filter($data, function ($array) use ($id) {
            return $array['category'] == $id;
        });

        if (!empty($found)) {
            $this->setCachedResult($cacheKey, json_encode($found));

            return $this->getCachedResult($cacheKey);
        }

        return null;
    }
}
