<?php
namespace Which1ispink\API\Core\Http;

use Which1ispink\API\Core\Exception\RuntimeException;

/**
 * Class Request
 *
 * Represents an HTTP request
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
class Request
{
    /**
     * @var string
     */
    private $verb;

    /**
     * @var string
     */
    private $scheme;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $pathInfo;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var array
     */
    private $routeParameters;

    /**
     * @var string
     */
    private $contentType;

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    const SUPPORTED_HTTP_VERBS = [
        self::METHOD_GET,
        self::METHOD_POST,
        self::METHOD_PUT,
        self::METHOD_PATCH,
        self::METHOD_DELETE,
    ];

    const FORMAT_JSON = 'json';
    const CONTENT_TYPE_JSON = 'application/json';

    const SUPPORTED_FORMATS = [
        self::CONTENT_TYPE_JSON,
    ];

    /**
     * Request constructor
     *
     * @param array $server the $_SERVER superglobal
     */
    public function __construct(array $server)
    {
        $this->init($server);
    }

    /**
     * Creates an instance from PHP's superglobals
     *
     * @return static
     */
    public static function createFromSuperglobals(): self
    {
        return new self($_SERVER);
    }

    /**
     * @return string
     */
    public function getVerb(): string
    {
        return $this->verb;
    }

    /**
     * @param string $verb
     * @return static
     */
    public function setVerb(string $verb): self
    {
        $this->verb = $verb;

        return $this;
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     * @return static
     */
    public function setScheme(string $scheme): self
    {
        $this->scheme = $scheme;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return static
     */
    public function setHost(string $host): self
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getPathInfo(): string
    {
        return $this->pathInfo;
    }

    /**
     * @param string $pathInfo
     *
     * @return static
     */
    public function setPathInfo(string $pathInfo): self
    {
        $this->pathInfo = $pathInfo;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Get request parameter by name, or a default if it doesn't exist
     *
     * @param string $parameter
     * @param string $default
     * @return mixed
     */
    public function getParameter(string $parameter, string $default = '')
    {
        $value = $default;
        if (array_key_exists($parameter, $this->parameters)) {
            $value = $this->parameters[$parameter];
        }

        return $value;
    }

    /**
     * Get route parameter by its numerical order
     *
     * @param int $order
     * @return string
     */
    public function getRouteParameter(int $order): string
    {
        return (isset($this->routeParameters[$order - 1])) ? $this->routeParameters[$order - 1] : '';
    }

    /**
     * @param array $routeParameters
     * @return static
     */
    public function setRouteParameters(array $routeParameters): self
    {
        $this->routeParameters = $routeParameters;

        return $this;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return (! empty($this->contentType)) ? $this->contentType : '';
    }

    /**
     * @param string $contentType
     * @return static
     */
    public function setContentType(string $contentType): self
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get base URL
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->getScheme() . $this->getHost();
    }

    /**
     * Get the raw body from POST or PUT calls
     *
     * @return string
     */
    public function getRawBody(): string
    {
        return file_get_contents('php://input');
    }

    /**
     * Return whether the content type is allowed (we only allow JSON request bodies)
     *
     * @return bool
     */
    public function checkAllowedContentType(): bool
    {
        if ($this->getVerb() == self::METHOD_POST ||
            $this->getVerb() == self::METHOD_PUT ||
            $this->getVerb() == self::METHOD_PATCH) {
            return ($this->getContentType() == self::CONTENT_TYPE_JSON);
        }

        return true;
    }

    /**
     * Fills up the request object from the $server array
     *
     * @param array $server the $_SERVER superglobal
     * @return static
     */
    private function init(array $server): self
    {
        if (isset($server['REQUEST_METHOD'])) {
            $this->setVerb($server['REQUEST_METHOD']);
        }

        if (isset($server['HTTPS']) && ($server['HTTPS'] == "on")) {
            $this->setScheme('https://');
        } else {
            $this->setScheme('http://');
        }

        if (isset($server['HTTP_HOST'])) {
            $this->setHost($server['HTTP_HOST']);
        }

        if (isset($server['PATH_INFO'])) {
            $this->setPathInfo($server['PATH_INFO']);
        } elseif (isset($server['REQUEST_URI'])) {
            $this->setPathInfo(parse_url($server['REQUEST_URI'], PHP_URL_PATH));
        }

        if (isset($server['CONTENT_TYPE'])) {
            $this->setContentType($server['CONTENT_TYPE']);
        } elseif (isset($server['HTTP_CONTENT_TYPE'])) {
            $this->setContentType($server['HTTP_CONTENT_TYPE']);
        }

        $this->parseParameters($server);

        return $this;
    }

    /**
     * Set the request parameters depending on the request method
     *
     * @param array $server the $_SERVER superglobal
     * @return static
     */
    private function parseParameters(array $server): self
    {
        $this->parameters = [];

        // GET
        if (isset($server['QUERY_STRING'])) {
            parse_str($server['QUERY_STRING'], $parameters);
            $this->parameters = $parameters;
        }

        // PUT/POST request body. Override what we had
        if ($this->getVerb() == self::METHOD_POST ||
            $this->getVerb() == self::METHOD_PUT ||
            $this->getVerb() == self::METHOD_PATCH) {
            $body = $this->getRawBody();
            if ($this->getContentType() == self::CONTENT_TYPE_JSON) {
                $bodyParameters = json_decode($body);
                if ($bodyParameters) {
                    foreach ($bodyParameters as $key => $value) {
                        $this->parameters[$key] = $value;
                    }
                }
            }
        }

        return $this;
    }
}
