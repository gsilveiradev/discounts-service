<?php

use Slim\App;
use DiscountsService\Framework\Cache\Adapter\RedisAdapter;
use DiscountsService\App\Orders\Service\OrderDiscountsCalculator;
use DiscountsService\App\Customers\Repository\CustomerRepository;
use DiscountsService\App\Products\Repository\ProductRepository;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };

    // cache client
    $container['cache'] = function ($c) {
        $settings = $c->get('settings')['redis'];
        $redisClient = new Predis\Client($settings);
        return new RedisAdapter($redisClient);
    };

    // GuzzleHttp client
    $container['guzzle'] = function ($c) {
        $settings = $c->get('settings')['guzzle'];
        return new GuzzleHttp\Client();
    };

    // OrderDiscountsCalculator service
    $container['discountsCalculator'] = function ($c) {
        $customers = new CustomerRepository($c['guzzle'], $c['cache']);
        $products = new ProductRepository($c['guzzle'], $c['cache']);

        return new OrderDiscountsCalculator($customers, $products);
    };
};
