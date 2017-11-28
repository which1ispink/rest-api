<?php
namespace Which1ispink\API\Core\Exception;

/**
 * Class EntityNotFoundException
 *
 * Should be thrown when an entity that does not exist is queried for
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
class EntityNotFoundException extends \RuntimeException implements ExceptionInterface
{
}
