<?php
namespace Which1ispink\API\Core\Rest;

use Which1ispink\API\Core\DependencyInjection\ContainerInterface;
use Which1ispink\API\Core\Http\Request;
use Which1ispink\API\Core\Http\Response;

/**
 * Class AbstractController
 *
 * Base controller class
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
abstract class AbstractController implements ControllerInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * AbstractController constructor
     *
     * @param ContainerInterface $container
     * @param Request $request
     * @param Response $response
     */
    public function __construct(ContainerInterface $container, Request $request, Response $response)
    {
        $this->container = $container;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @inheritdoc
     */
    public function callAction(string $methodName): Response
    {
        // could execute any "middleware" code here before calling the action
        $output = $this->{$methodName}();
        return $this->response->withJson($output);
    }

    /**
     * Alias for ContainerInterface::get for convenience
     *
     * @param string $id
     * @return mixed
     */
    public function get(string $id)
    {
        return $this->container->get($id);
    }
}
