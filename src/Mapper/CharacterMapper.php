<?php
namespace Which1ispink\API\Mapper;

use Which1ispink\API\Core\Exception\EntityNotFoundException;
use Which1ispink\API\Core\Exception\RuntimeException;
use Which1ispink\API\Entity\Character;

/**
 * Class CharacterMapper
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
class CharacterMapper extends AbstractMapper implements CharacterMapperInterface
{
    /**
     * @inheritdoc
     */
    public function find(int $id): Character
    {
        $sql = 'SELECT * FROM characters WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (! $row) {
            throw new EntityNotFoundException('Character not found', 404);
        }

        $character = $this->createEntity($row);
        $character->setId($row['id']);

        return $character;
    }

    /**
     * @inheritdoc
     */
    public function findAll(): array
    {
        $sql = 'SELECT * FROM characters
                WHERE health > 0
                ORDER BY date_created DESC';
        $stmt = $this->db->query($sql);

        $characters = [];
        while ($row = $stmt->fetch()) {
            $character = $this->createEntity($row);
            $character->setId($row['id']);
            $characters[] = $character;
        }

        if (empty($characters)) {
            throw new EntityNotFoundException('No characters found', 404);
        }

        return $characters;
    }

    /**
     * @inheritdoc
     */
    public function add(Character $character): Character
    {
        $sql = 'INSERT INTO characters (name, type, experience_points, power_points, health, date_created)
                VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $character->getName(),
            $character->getType(),
            $character->getExperiencePoints(),
            $character->getPowerPoints(),
            $character->getHealth(),
            $character->getDateCreated(true)
        ]);

        if (! $stmt->rowCount() > 0) {
            throw new RuntimeException('Character creation failed', 500);
        }

        $character->setId($this->db->lastInsertId());

        return $character;
    }

    /**
     * @inheritdoc
     */
    public function update(Character $character, int $id)
    {
        $sql = "UPDATE characters
                SET name = ?, type = ?, experience_points = ?, power_points = ?, health = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $character->getName(),
            $character->getType(),
            $character->getExperiencePoints(),
            $character->getPowerPoints(),
            $character->getHealth(),
            $id
        ]);
    }

    /**
     * @inheritdoc
     */
    public function createEntity(array $row): Character
    {
        return new Character(
            $row['name'],
            $row['type'],
            $row['experience_points'],
            $row['power_points'],
            $row['health'],
            new \DateTime($row['date_created'])
        );
    }
}
