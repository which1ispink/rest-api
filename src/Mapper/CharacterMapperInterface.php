<?php
namespace Which1ispink\API\Mapper;

use Which1ispink\API\Core\Exception\EntityNotFoundException;
use Which1ispink\API\Entity\Character;

/**
 * Interface CharacterMapperInterface
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
interface CharacterMapperInterface
{
    /**
     * Find character by ID
     *
     * @param int $id
     * @return Character
     * @throws EntityNotFoundException if no character is found with the given ID
     */
    public function find(int $id): Character;

    /**
     * Find all characters
     *
     * @return Character[]
     * @throws EntityNotFoundException if no characters are found
     */
    public function findAll(): array;

    /**
     * Add new character
     *
     * @param Character $character
     * @return Character
     */
    public function add(Character $character): Character;

    /**
     * Update character by ID with the given new character's data
     *
     * @param Character $character
     * @param int $id
     */
    public function update(Character $character, int $id);
}
