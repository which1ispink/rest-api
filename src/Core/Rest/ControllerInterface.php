<?php
namespace Which1ispink\API\Core\Rest;

use Which1ispink\API\Core\Http\Response;

/**
 * Interface ControllerInterface
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
interface ControllerInterface
{
    /**
     * Execute any "middleware" code then call the given action method
     *
     * @param string $methodName
     * @return Response
     */
    public function callAction(string $methodName): Response;
}
