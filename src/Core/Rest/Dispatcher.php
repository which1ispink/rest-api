<?php
namespace Which1ispink\API\Core\Rest;

use Which1ispink\API\Core\DependencyInjection\ContainerInterface;
use Which1ispink\API\Core\Http\Request;
use Which1ispink\API\Core\Http\Response;

/**
 * Class Dispatcher
 *
 * Dispatches the request to a given route
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * @inheritdoc
     */
    public function dispatch(RouteInterface $route, ContainerInterface $container, Request $request, Response $response): Response
    {
        $className = DispatcherInterface::CONTROLLERS_NAMESPACE . $route->getControllerClass();
        $methodName = $route->getControllerMethod();

        if (! class_exists($className)) {
            throw new \RuntimeException(
                sprintf('The controller class "%s" couldn\'t be found', $className),
                500
            );
        }

        /** @var ControllerInterface $controller */
        $controller = new $className($container, $request, $response);
        if (! method_exists($controller, $methodName)) {
            throw new \RuntimeException(
                sprintf('The controller action "%s" couldn\'t be found', $methodName),
                500
            );
        }

        return $controller->callAction($methodName);
    }
}
