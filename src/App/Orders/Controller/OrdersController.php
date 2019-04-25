<?php

namespace DiscountsService\App\Orders\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Container\ContainerInterface;
use DiscountsService\App\Orders\Validation\ArrayValidation;
use DiscountsService\App\Discounts\Calculation;
use DiscountsService\App\Discounts\Rules;
use DiscountsService\App\Customers\Repository\CustomerRepository;

class OrdersController
{
    protected $request;
    protected $response;
    protected $container;

    public function __construct(Request $request, Response $response, ContainerInterface $container)
    {
        $this->request = $request;
        $this->response = $response;
        $this->container = $container;
    }

    public function indexPost()
    {
        $order = $this->request->getParsedBody();
        $order['subtotal'] = $order['total'];

        if ((new ArrayValidation())->isValid($order)) {
            $cacheClient = $this->container->get('cache');
            $guzzleClient = $this->container->get('guzzle');

            $customers = new CustomerRepository($guzzleClient, $cacheClient);

            $discountsCalculation = new Calculation(new Rules\UserTotalRevenue($customers));
            $discount = $discountsCalculation->getDiscount($order);

            $order['total'] = (string) (floatval($order['total']) - floatval($discount));
            
            return $this->response->withJson(array_merge($order, ['discount' => $discount]));
        }

        return $this->response->withJson(['error' => 'The order is invalid!'], 422);
    }
}
