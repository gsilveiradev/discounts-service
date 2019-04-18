<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Sample Redis test
        $redisClient = $container->get('redis');
        
        if (!$redisClient->get('foo')) {
            $redisClient->set('foo', date('m/d/Y H:i:s'));
            $redisClient->expire('foo', 20); // Expire the key every 20 seconds
        }
        
        $container->get('logger')->info("Discounts Service '/' Redis: ".$redisClient->get('foo'));

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
};
