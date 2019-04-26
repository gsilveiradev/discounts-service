<?php

namespace DiscountsService\App\Orders\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Container\ContainerInterface;
use DiscountsService\App\Orders\Validation\ArrayValidation;
use DiscountsService\App\Discounts\Calculation;
use DiscountsService\App\Discounts\Rules;
use DiscountsService\App\Customers\Repository\CustomerRepository;
use DiscountsService\App\Products\Repository\ProductRepository;

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
            $products = new ProductRepository($guzzleClient, $cacheClient);

            $totalDiscount = 0.00;

            // Apply discounts for Switches category rule
            $discountsCalculation = new Calculation(new Rules\SwitchesCategory($products));
            $discount = $discountsCalculation->getDiscount($order);
            $totalDiscount += $discount;
            $order['total'] = number_format(floatval($order['total']) - $discount, 2, '.', '');

            // Apply discounts for Tools category rule
            $discountsCalculation = new Calculation(new Rules\ToolsCategory($products));
            $discount = $discountsCalculation->getDiscount($order);
            $totalDiscount += $discount;
            $order['total'] = number_format(floatval($order['total']) - $discount, 2, '.', '');

            // Apply discounts for user total revenue rule
            $discountsCalculation = new Calculation(new Rules\UserTotalRevenue($customers));
            $discount = $discountsCalculation->getDiscount($order);
            $totalDiscount += $discount;
            $order['total'] = number_format(floatval($order['total']) - $discount, 2, '.', '');
            
            $order['discount'] = number_format($totalDiscount, '2', '.', '');

            return $this->response->withJson($order);
        }

        return $this->response->withJson(['error' => 'The order is invalid!'], 422);
    }
}
