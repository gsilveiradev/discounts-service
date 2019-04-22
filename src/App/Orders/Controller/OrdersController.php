<?php

namespace DiscountsService\App\Orders\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Container\ContainerInterface;
use DiscountsService\App\Orders\Validation\ArrayValidation;

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

    public function index()
    {
        $order = $this->request->getParsedBody();

        if ((new ArrayValidation())->isValid($order)) {
            // calculate the discounts
            return $this->response->withJson($order);
        }

        return $this->response->withJson(['error' => 'The order is invalid!'], 422);
    }
}