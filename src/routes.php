<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use DiscountsService\App\Customers\Repository\CustomerRepository;
use DiscountsService\App\Products\Repository\ProductRepository;
use DiscountsService\App\Orders\Controller\OrdersController;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        $cacheClient = $container->get('cache');
        $guzzleClient = $container->get('guzzle');

        $customers = new CustomerRepository($guzzleClient, $cacheClient);
        $container->get('logger')->info("Discounts Service '/' Customers: ".json_encode($customers->getAll()));
        $container->get('logger')->info("Discounts Service '/' Customer (2): ".json_encode($customers->findById(2)));

        $products = new ProductRepository($guzzleClient, $cacheClient);
        $container->get('logger')->info("Discounts Service '/' Products: ".json_encode($products->getAll()));
        $container->get('logger')->info(
            "Discounts Service '/' Product (B101): ".json_encode($products->findById('B101'))
        );
        $container->get('logger')->info(
            "Discounts Service '/' Product by Category (2): ".json_encode($products->findByCategoryId(2))
        );

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->post('/', function (Request $request, Response $response, array $args) use ($container) {
        $controller = new OrdersController($request, $response, $container);
        return $controller->index();
    });
};
