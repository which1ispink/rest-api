<?php
namespace Which1ispink\API\Core\Http;

/**
 * Class AbstractMessage
 *
 * Represents an HTTP message
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
abstract class AbstractMessage
{
    /**
     * @var string
     */
    protected $protocolVersion = '1.1';

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var string
     */
    protected $body;

    /**
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * @param string $protocolVersion
     * @return static
     */
    public function setProtocolVersion(string $protocolVersion): self
    {
        $this->protocolVersion = $protocolVersion;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $header
     * @return static
     */
    public function addHeader(string $header): self
    {
        $this->headers[] = $header;

        return $this;
    }

    /**
     * @param array $headers
     * @return static
     */
    public function addHeaders(array $headers): self
    {
        foreach ($headers as $header) {
            $this->addHeader($header);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return (! empty($this->body)) ? $this->body : '';
    }

    /**
     * @param string $body
     * @return static
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }
}
