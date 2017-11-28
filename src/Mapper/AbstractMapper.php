<?php
namespace Which1ispink\API\Mapper;

use Which1ispink\API\Entity\EntityInterface;
use PDO;

/**
 * Class AbstractMapper
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
abstract class AbstractMapper
{
    /**
     * @var PDO
     */
    protected $db;

    /**
     * AbstractMapper constructor
     *
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Create an entity from the given row and return it
     *
     * @param array $row
     * @return EntityInterface
     */
    abstract public function createEntity(array $row);
}
