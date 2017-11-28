<?php
namespace Which1ispink\API\Core\Rest;

use Which1ispink\API\Core\DependencyInjection\ContainerInterface;
use Which1ispink\API\Core\Http\Request;
use Which1ispink\API\Core\Http\Response;

interface DispatcherInterface
{
    /**
     * The namespace where the controller classes live
     */
    const CONTROLLERS_NAMESPACE = '\\Which1ispink\\API\\Controller\\';

    /**
     * Dispatches the request to the route given
     *
     * @param RouteInterface $route
     * @param ContainerInterface $container
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function dispatch(RouteInterface $route, ContainerInterface $container, Request $request, Response $response): Response;
}
