<?php
namespace Which1ispink\API\Core\Exception;

/**
 * Class InvalidOperationException
 *
 * Should be thrown when an invalid operation (according to the domain logic) is attempted
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
class InvalidOperationException extends \RuntimeException implements ExceptionInterface
{
}
