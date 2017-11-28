<?php
namespace Which1ispink\API\Core\Rest;

use Which1ispink\API\Core\Exception\RuntimeException;
use Which1ispink\API\Core\Http\Request;

/**
 * Class Router
 *
 * Routes the current request to the appropriate route, if found
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
class Router implements RouterInterface
{
    /**
     * @var array
     */
    private $routeRules;

    /**
     * Router constructor
     *
     * @param array $routeRules
     */
    public function __construct(array $routeRules = [])
    {
        foreach ($routeRules as $routeRule) {
            $this->addRouteRule($routeRule);
        }
    }

    /**
     * @return array
     */
    public function getRouteRules(): array
    {
        return $this->routeRules;
    }

    /**
     * Add a route rule
     *
     * @param array $routeRule
     * @return static
     */
    public function addRouteRule(array $routeRule): self
    {
        $this->routeRules[] = $routeRule;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function route(Request $request): RouteInterface
    {
        $wrongMethod = false;
        foreach ($this->routeRules as $rule) {
            if (preg_match('%'. $rule['path'] .'%', $request->getPathInfo(), $matches)) {
                if ($rule['verb'] != $request->getVerb()) {
                    $wrongMethod = true;
                    continue;
                }

                unset($matches[0]);
                $parameters = array_values($matches);

                if (! $request->checkAllowedContentType()) {
                    throw new RuntimeException('Unsupported media type', 415);
                }
                $request->setRouteParameters($parameters);
                return new Route($rule['path'], $rule['verb'], $rule['controller'], $rule['action'], $parameters);
            }
        }

        if ($wrongMethod) {
            throw new RuntimeException('Method not supported for this endpoint', 405);
        }

        throw new RuntimeException('Endpoint not found', 404);
    }
}
