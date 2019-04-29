<?php

namespace DiscountsService\App\Orders\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Container\ContainerInterface;
use DiscountsService\App\Orders\Validation\Exception\InvalidArrayStructureException;

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

        try {
            $discountsCalculator = $this->container->get('discountsCalculator');
            $order = $discountsCalculator->fromOrder($order)->applyDiscounts();

            return $this->response->withJson($order);
        } catch (InvalidArrayStructureException $e) {
            return $this->response->withJson(['error' => $e->getMessage()], 422);
        }
    }
}
