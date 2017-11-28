<?php
namespace Which1ispink\API\Core;

use Which1ispink\API\Core\DependencyInjection\Container;
use Which1ispink\API\Core\Http\Request;
use Which1ispink\API\Core\Http\Response;
use Which1ispink\API\Core\Exception\ExceptionInterface;
use Which1ispink\API\Core\Rest\Dispatcher;
use Which1ispink\API\Core\Rest\Router;
use Which1ispink\API\DependencyInjection\ServicesProvider;

/**
 * Class Api
 *
 * Bootstraps the application and sends back the response to the client
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
class Api
{
    /**
     * @var array
     */
    private $appConfig;

    /**
     * API constructor
     *
     * @param array $appConfig
     */
    public function __construct(array $appConfig)
    {
        $this->appConfig = $appConfig;
    }

    /**
     * @return array
     */
    public function getAppConfig(): array
    {
        return $this->appConfig;
    }

    /**
     * Run the application
     *
     * @return string the JSON response
     */
    public function run()
    {
        try {
            $container = (new ServicesProvider())->registerServices(new Container(), $this->appConfig);
            $request = Request::createFromSuperglobals();
            $response = new Response();
            $router = new Router($this->appConfig['routes']);
            $dispatcher = new Dispatcher();
            $route = $router->route($request);

            $response = $dispatcher->dispatch($route, $container, $request, $response);
        } catch (ExceptionInterface $e) {
            $response = new Response($e->getCode());
            $response->withJson([
                'code'    => $e->getCode(),
                'message' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            $response = new Response(500);
            $response->withJson([
                'code'    => 500,
                'message' => 'Server error'
            ]);
        }

        $this->respond($response);
    }

    /**
     * Send back the response to the client
     *
     * @param Response $response
     */
    private function respond(Response $response)
    {
        if (! headers_sent()) {
            // status line
            header(sprintf(
                'HTTP/%s %s %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ));

            // response headers
            foreach ($response->getHeaders() as $header) {
                header($header);
            }
        }

        // response body
        if (! $response->isEmpty()) {
            echo $response->getBody();
        }
    }
}
