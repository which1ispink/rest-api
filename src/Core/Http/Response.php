<?php
namespace Which1ispink\API\Core\Http;

/**
 * Class Response
 *
 * Represents an HTTP response
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
class Response extends AbstractMessage
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var string
     */
    protected $reasonPhrase;

    /**
     * Status codes and reason phrases
     *
     * @var array
     */
    protected static $messages = [
        //Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        //Successful 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        //Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        //Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        444 => 'Connection Closed Without Response',
        451 => 'Unavailable For Legal Reasons',
        499 => 'Client Closed Request',
        //Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        599 => 'Network Connect Timeout Error',
    ];

    const EOL = "\r\n";

    /**
     * Response constructor
     *
     * @param int $statusCode
     * @param array $headers
     * @param string $body
     */
    public function __construct($statusCode = 200, array $headers = [], string $body = '')
    {
        $this->setStatusCode($statusCode);
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return static
     */
    public function setStatusCode(int $statusCode): self
    {
        if ($statusCode < 100 || $statusCode > 599) {
            throw new \InvalidArgumentException(
                sprintf('Invalid HTTP statusCode code "%s"', $statusCode)
            );
        }

        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getReasonPhrase(): string
    {
        if (! empty($this->reasonPhrase)) {
            return $this->reasonPhrase;
        }

        if (isset(self::$messages[$this->statusCode])) {
            return self::$messages[$this->statusCode];
        }

        return '';
    }

    /**
     * Prepare response to return with the encoded JSON object
     *
     * @param mixed $data
     * @return static
     * @throws \RuntimeException if JSON encoding failed
     */
    public function withJson($data = null): self
    {
        if (! empty($data)) {
            $this->setBody($json = json_encode($data, JSON_PRETTY_PRINT));

            // ensure JSON encoding passed successfully
            if ($json === false) {
                throw new \RuntimeException(json_last_error_msg(), 500);
            }
        }

        $this->addHeader('Content-type: application/json;charset=utf-8');

        return $this;
    }

    /**
     * Whether or not this response has a body
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return in_array($this->getStatusCode(), [204, 205, 304]);
    }

    /**
     * String representation
     *
     * @return string
     */
    public function __toString(): string
    {
        $output = sprintf(
            'HTTP/%s %s %s',
            $this->getProtocolVersion(),
            $this->getStatusCode(),
            $this->getReasonPhrase()
        );
        $output .= self::EOL;
        foreach ($this->getHeaders() as $header) {
            $output .= $header . Response::EOL;
        }
        $output .= self::EOL;
        $output .= $this->getBody();

        return $output;
    }
}
