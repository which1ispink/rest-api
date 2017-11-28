<?php
namespace Which1ispink\API\Entity;

use Which1ispink\API\Core\Exception\InvalidArgumentException;
use Which1ispink\API\Core\Exception\InvalidOperationException;

/**
 * Class Character
 *
 * Represents a character of any type
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
class Character implements EntityInterface, \JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $experiencePoints;

    /**
     * @var int
     */
    private $powerPoints;

    /**
     * @var int
     */
    private $health;

    /**
     * @var \DateTime
     */
    private $dateCreated;

    /**
     * Max character health
     */
    const MAX_HEALTH = 100;

    /**
     * Character types
     */
    const VAMPIRE = 'Vampire';
    const VAMPIRE_HUNTER = 'Vampire hunter'; // used as default if no type is provided upon character creation, it's fun
    const ZOMBIE = 'Zombie';
    const NINJA = 'Ninja';
    const PIRATE = 'Pirate';

    const CHARACTER_TYPES = [
        self::VAMPIRE,
        self::VAMPIRE_HUNTER,
        self::ZOMBIE,
        self::NINJA,
        self::PIRATE,
    ];

    /**
     * Character constructor
     *
     * @param string $name
     * @param string $type
     * @param int $experiencePoints
     * @param int $powerPoints
     * @param int $health
     * @param \DateTime|null $dateCreated
     */
    public function __construct(
        string $name,
        string $type = self::VAMPIRE_HUNTER,
        int $experiencePoints = 10,
        int $powerPoints = 10,
        int $health = self::MAX_HEALTH,
        \DateTime $dateCreated = null
    ) {
        $this->setName($name);
        $this->setType($type);
        $this->experiencePoints = $experiencePoints;
        $this->powerPoints = $powerPoints;
        $this->setHealth($health);
        $this->setDateCreated($dateCreated);
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return static
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Change character name
     *
     * @param string $name
     * @return static
     */
    public function changeName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Transform the character to a different character type
     *
     * @param string $type
     * @return static
     */
    public function transform(string $type): self
    {
        $this->setType($type);

        return $this;
    }

    /**
     * @return int
     */
    public function getExperiencePoints(): int
    {
        return $this->experiencePoints;
    }

    /**
     * Increases a character's experience by the given amount
     *
     * @param int $amount
     * @return static
     */
    public function gainExperience(int $amount): self
    {
        $this->experiencePoints += $amount;

        return $this;
    }

    /**
     * Calculates the character's current level based on its experience points
     *
     * @return int
     */
    public function getLevel(): int
    {
        if ($this->experiencePoints < 1) {
            return 1;
        }

        return (int) ceil($this->experiencePoints / 100);
    }

    /**
     * @return int
     */
    public function getPowerPoints(): int
    {
        return $this->powerPoints;
    }

    /**
     * Increase the character's power points by given amount
     *
     * @param int $amount
     * @return static
     */
    public function powerUp(int $amount): self
    {
        $this->powerPoints += $amount;

        return $this;
    }

    /**
     * Decrease the character's power points by given amount
     *
     * @param int $amount
     * @return static
     */
    public function powerDown(int $amount): self
    {
        $this->powerPoints -= $amount;
        if ($this->powerPoints < 0) {
            $this->powerPoints = 0;
        }

        return $this;
    }

    /**
     * Get health
     *
     * @return int
     */
    public function getHealth(): int
    {
        return $this->health;
    }

    /**
     * Increase the character's health by given amount, unless it's dead
     *
     * @param int $amount
     * @return static
     * @throws InvalidOperationException if performed on a dead character
     */
    public function heal(int $amount): self
    {
        if (! $this->isAlive()) {
            throw new InvalidOperationException('Character is beyond healing :(', 409);
        }

        $this->setHealth($this->getHealth() + $amount);

        return $this;
    }

    /**
     * Restore character to full health
     *
     * @return static
     * @throws InvalidOperationException if performed on a dead character
     */
    public function restoreFullHealth(): self
    {
        if (! $this->isAlive()) {
            throw new InvalidOperationException('Character is dead', 409);
        }

        $this->setHealth(self::MAX_HEALTH);

        return $this;
    }

    /**
     * Revive a dead character, giving it a low amount of its health back
     *
     * @return static
     * @throws InvalidOperationException if performed on a living character
     */
    public function revive(): self
    {
        if ($this->isAlive()) {
            throw new InvalidOperationException('Why revive a living character?', 409);
        }

        $this->setHealth(20);

        return $this;
    }

    /**
     * Decrease the character's health by given amount
     *
     * @param int $amount
     * @return static
     * @throws InvalidOperationException if performed on a dead character
     */
    public function damage(int $amount): self
    {
        if (! $this->isAlive()) {
            throw new InvalidOperationException('Character is already dead', 409);
        }

        $this->setHealth($this->getHealth() - $amount);

        return $this;
    }

    /**
     * Kill the character
     *
     * @return static
     * @throws InvalidOperationException if performed on a dead character
     */
    public function kill(): self
    {
        if (! $this->isAlive()) {
            throw new InvalidOperationException('What is dead may never die', 409);
        }

        $this->setHealth(0);

        return $this;
    }

    /**
     * Check if character is currently alive
     *
     * @return bool
     */
    public function isAlive(): bool
    {
        return ($this->health > 0);
    }

    /**
     * @param bool $asString whether you want the date as a string (in MySQL datetime format)
     * @return \DateTime|string
     */
    public function getDateCreated(bool $asString = false)
    {
        if ($asString) {
            return $this->dateCreated->format('Y-m-d H:i:s');
        }

        return $this->dateCreated;
    }

    /**
     * @param string $name
     * @return self
     * @throws InvalidArgumentException if the name given is more than 60 characters in length
     */
    private function setName(string $name): self
    {
        if (strlen($name) > 60) {
            throw new InvalidArgumentException('Character name can\'t be longer than 60 characters', 400);
        }

        $this->name = $name;

        return $this;
    }

    /**
     * @param string $type
     * @return self
     * @throws InvalidArgumentException if the type given is not supported
     */
    private function setType(string $type): self
    {
        if (! in_array($type, self::CHARACTER_TYPES)) {
            throw new InvalidArgumentException('Character type not supported', 400);
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @param int $health
     * @return self
     */
    private function setHealth(int $health): self
    {
        if ($health < 0) {
            $this->health = 0;
        } elseif ($health > self::MAX_HEALTH) {
            $this->health = self::MAX_HEALTH;
        } else {
            $this->health = $health;
        }

        return $this;
    }

    /**
     * @param \DateTime|null $dateCreated
     * @return self
     */
    private function setDateCreated(\DateTime $dateCreated = null): self
    {
        if (is_null($dateCreated)) {
            $dateCreated = new \DateTime();
        }

        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'type' => $this->getType(),
            'experience_points' => $this->getExperiencePoints(),
            'level' => $this->getLevel(),
            'power_points' => $this->getPowerPoints(),
            'health' => $this->getHealth(),
            'is_alive' => $this->isAlive(),
            'date_created' => $this->getDateCreated()->format('Y-m-d H:i:s'),
        ];
    }
}
