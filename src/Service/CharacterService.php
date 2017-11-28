<?php
namespace Which1ispink\API\Service;

use Which1ispink\API\Core\Exception\InvalidArgumentException;
use Which1ispink\API\Core\Exception\InvalidOperationException;
use Which1ispink\API\Entity\Character;
use Which1ispink\API\Mapper\CharacterMapperInterface;

/**
 * Class CharacterService
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
class CharacterService
{
    /**
     * @var CharacterMapperInterface
     */
    private $characterMapper;

    /**
     * CharacterService constructor
     *
     * @param CharacterMapperInterface $characterMapper
     */
    public function __construct(CharacterMapperInterface $characterMapper)
    {
        $this->characterMapper = $characterMapper;
    }

    /**
     * Find character by ID
     *
     * @param int $id
     * @return Character
     */
    public function find(int $id): Character
    {
        return $this->characterMapper->find($id);
    }

    /**
     * Find all characters
     *
     * @return Character[]
     */
    public function findAll(): array
    {
        return $this->characterMapper->findAll();
    }

    /**
     * Create new character
     *
     * @param array $parameters
     * @return Character
     */
    public function create(array $parameters): Character
    {
        if (empty($parameters['name'])) {
            throw new InvalidArgumentException('Character name must be provided', 400);
        }

        if (! empty($parameters['type'])) {
            $character = new Character($parameters['name'], $parameters['type']);
        } else {
            $character = new Character($parameters['name']);
        }

        $character = $this->characterMapper->add($character);

        return $character;
    }

    /**
     * Apply given actions on character with the given ID
     *
     * @param array $parameters
     * @param int $id
     * @return Character
     */
    public function update(array $parameters, int $id): Character
    {
        if (empty($parameters['action']) || empty($parameters['value'])) {
            throw new InvalidArgumentException('Invalid action description', 400);
        }

        $character = $this->find($id);

        switch ($parameters['action']) {
            case 'change_name':
                $character->changeName($parameters['value']);
                break;
            case 'transform':
                $character->transform($parameters['value']);
                break;
            case 'gain_experience':
                $character->gainExperience($parameters['value']);
                break;
            case 'power_up':
                $character->powerUp($parameters['value']);
                break;
            case 'power_down':
                $character->powerDown($parameters['value']);
                break;
            case 'heal':
                $character->heal($parameters['value']);
                break;
            case 'restore_full_health':
                $character->restoreFullHealth();
                break;
            case 'revive':
                $character->revive();
                break;
            case 'damage':
                $character->damage($parameters['value']);
            default:
                throw new InvalidOperationException('Unsupported action', 400);
        }

        $this->characterMapper->update($character, $id);

        return $character;
    }

    /**
     * Kill character with the given ID
     *
     * @param int $id
     */
    public function kill(int $id)
    {
        $character = $this->find($id);
        $character->kill();

        $this->characterMapper->update($character, $id);
    }
}
