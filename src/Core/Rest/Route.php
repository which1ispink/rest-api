<?php
namespace Which1ispink\API\Core\Rest;

/**
 * Class Route
 *
 * Represents an API route
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
class Route implements RouteInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $httpVerb;

    /**
     * @var string
     */
    private $controllerClass;

    /**
     * @var string
     */
    private $controllerMethod;

    /**
     * @var array
     */
    private $parameters;

    /**
     * Route constructor
     *
     * @param string $path
     * @param string $httpVerb
     * @param string $controllerClass
     * @param string $controllerMethod
     * @param array $parameters
     */
    public function __construct(string $path, string $httpVerb, string $controllerClass, string $controllerMethod, array $parameters = [])
    {
        $this->path = $path;
        $this->httpVerb = $httpVerb;
        $this->controllerClass = $controllerClass;
        $this->controllerMethod = $controllerMethod;
        $this->parameters = $parameters;
    }

    /**
     * @inheritdoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritdoc
     */
    public function getHttpVerb(): string
    {
        return $this->httpVerb;
    }

    /**
     * @inheritdoc
     */
    public function getControllerClass(): string
    {
        return $this->controllerClass;
    }

    /**
     * @inheritdoc
     */
    public function getControllerMethod(): string
    {
        return $this->controllerMethod;
    }

    /**
     * @inheritdoc
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
