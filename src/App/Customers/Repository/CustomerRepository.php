<?php

namespace DiscountsService\App\Customers\Repository;

use DiscountsService\Framework\Repository;

class CustomerRepository extends Repository
{
    protected $cachePrefix = 'customers:';

    /**
     * Return all customers using cache layer
     * @return null|array
     */
    public function getAll(): ?array
    {
        $cacheKey = $this->cachePrefix.'all';

        if ($this->getCachedResult($cacheKey)) {
            return $this->getCachedResult($cacheKey);
        }

        $client = $this->guzzle;
        $res = $client->request(
            'GET',
            'https://raw.githubusercontent.com/teamleadercrm/coding-test/master/data/customers.json'
        );

        $this->setCachedResult($cacheKey, $res->getBody());

        return $this->getCachedResult($cacheKey);
    }

    /**
     * Return one customer filtering by ID
     * @return null|array
     */
    public function findById(int $id): ?array
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
            $this->setCachedResult($cacheKey, json_encode(reset($found)));

            return $this->getCachedResult($cacheKey);
        }

        return null;
    }
}
