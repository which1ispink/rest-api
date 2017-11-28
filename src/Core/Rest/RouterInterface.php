<?php
namespace Which1ispink\API\Core\Rest;

use Which1ispink\API\Core\Exception\RuntimeException;
use Which1ispink\API\Core\Http\Request;

/**
 * Interface RouterInterface
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
interface RouterInterface
{
    /**
     * Tries to finds and returns a route matching the given request
     *
     * @param Request $request
     * @return RouteInterface
     * @throws RuntimeException if there is an attempt to call an existing route with a non-supported HTTP method
     * @throws RuntimeException if no route is found that matches the given request
     */
    public function route(Request $request): RouteInterface;
}
