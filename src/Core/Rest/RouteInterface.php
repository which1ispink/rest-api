<?php
namespace Which1ispink\API\Core\Rest;

/**
 * Interface RouteInterface
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
interface RouteInterface
{
    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return string
     */
    public function getHttpVerb(): string;

    /**
     * @return string
     */
    public function getControllerClass(): string;

    /**
     * @return string
     */
    public function getControllerMethod(): string;

    /**
     * @return array
     */
    public function getParameters(): array;
}
