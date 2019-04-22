<?php

use Slim\App;
use DiscountsService\Framework\Cache\Adapter\RedisAdapter;

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
};
