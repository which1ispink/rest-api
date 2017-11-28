<?php
namespace Which1ispink\API\Entity;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Which1ispink\API\Entity\Character
 */
class CharacterTest extends TestCase
{
    /**
     * @var Character
     */
    private $defaultCharacter;

    /**
     * @var \DateTime
     */
    private $creationDate;

    public function setUp()
    {
        $this->creationDate = new \DateTime();
        $this->defaultCharacter = new Character(
            'Jack',
            Character::NINJA,
            30,
            60,
            80,
            $this->creationDate
        );
    }

    /**
     * @covers ::__construct
     */
    public function test_new_character_goes_into_expected_state()
    {
        $this->assertNull($this->defaultCharacter->getId());
        $this->assertEquals('Jack', $this->defaultCharacter->getName());
        $this->assertEquals(Character::NINJA, $this->defaultCharacter->getType());
        $this->assertEquals(30, $this->defaultCharacter->getExperiencePoints());
        $this->assertEquals(1, $this->defaultCharacter->getLevel());
        $this->assertEquals(60, $this->defaultCharacter->getPowerPoints());
        $this->assertEquals(80, $this->defaultCharacter->getHealth());
        $this->assertTrue($this->defaultCharacter->isAlive());
        $this->assertEquals($this->creationDate, $this->defaultCharacter->getDateCreated());
    }

    /**
     * @covers ::__construct
     */
    public function test_new_character_without_optional_parameters_gets_expected_defaults()
    {
        $character = new Character('Jack');

        $this->assertEquals(Character::VAMPIRE_HUNTER, $character->getType());
        $this->assertEquals(10, $character->getExperiencePoints());
        $this->assertEquals(1, $character->getLevel());
        $this->assertEquals(10, $character->getPowerPoints());
        $this->assertEquals(Character::MAX_HEALTH, $character->getHealth());
    }

    /**
     * @covers ::__construct
     * @expectedException \Which1ispink\API\Core\Exception\InvalidArgumentException
     * @expectedExceptionMessage Character name can't be longer than 60 characters
     * @expectedExceptionCode 400
     */
    public function test_new_character_with_invalid_name_throws_InvalidArgumentException()
    {
        $character = new Character('Jakeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee
        eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee');
    }

    /**
     * @covers ::__construct
     * @expectedException \Which1ispink\API\Core\Exception\InvalidArgumentException
     * @expectedExceptionMessage Character type not supported
     * @expectedExceptionCode 400
     */
    public function test_new_character_with_unsupported_type_throws_InvalidArgumentException()
    {
        $character = new Character('Jack', 'Watermelon');
    }

    /**
     * @covers ::__construct
     */
    public function test_new_characters_with_invalid_health_gets_corrected_to_proper_default()
    {
        $character1 = new Character('Jack', Character::ZOMBIE, 10, 10, -10);
        $this->assertEquals(0, $character1->getHealth());

        $character2 = new Character('Jack', Character::ZOMBIE, 10, 10, 140);
        $this->assertEquals(Character::MAX_HEALTH, $character2->getHealth());
    }

    /**
     * @covers ::__construct
     */
    public function test_new_character_with_null_creation_date_gets_current_time_anyway()
    {
        $character = new Character('Jack', Character::ZOMBIE, 10, 10, 50);

        $this->assertInstanceOf(\DateTime::class, $character->getDateCreated());
    }

    /**
     * @covers ::gainExperience
     */
    public function test_gainExperience_increases_experience_points()
    {
        $this->defaultCharacter->gainExperience(20);

        $this->assertEquals(50, $this->defaultCharacter->getExperiencePoints());
    }

    /**
     * @covers ::getLevel
     */
    public function test_getLevel_calculates_level_correctly()
    {
        $character = new Character('Jack', Character::ZOMBIE, 333);

        $this->assertEquals(4, $character->getLevel());
    }

    /**
     * @covers ::powerUp
     */
    public function test_powerUp_increases_power_points()
    {
        $this->defaultCharacter->powerUp(40);

        $this->assertEquals(100, $this->defaultCharacter->getPowerPoints());

    }

    /**
     * @covers ::powerDown
     */
    public function test_powerDown_decreases_power_points()
    {
        $this->defaultCharacter->powerDown(30);
        $this->assertEquals(30, $this->defaultCharacter->getPowerPoints());

        $this->defaultCharacter->powerDown(50);
        $this->assertEquals(0, $this->defaultCharacter->getPowerPoints());
    }

    /**
     * @covers ::heal
     */
    public function test_heal_increases_health()
    {
        $this->defaultCharacter->heal(10);

        $this->assertEquals(90, $this->defaultCharacter->getHealth());
    }

    /**
     * @covers ::heal
     * @expectedException \Which1ispink\API\Core\Exception\InvalidOperationException
     * @expectedExceptionMessage Character is beyond healing :(
     * @expectedExceptionCode 409
     */
    public function test_heal_on_dead_character_throws_InvalidOperationException()
    {
        $this->defaultCharacter->kill();
        $this->defaultCharacter->heal(20);
    }

    /**
     * @covers ::restoreFullHealth
     */
    public function test_restoreFullHealth_restores_full_health()
    {
        $this->defaultCharacter->restoreFullHealth();

        $this->assertEquals(Character::MAX_HEALTH, $this->defaultCharacter->getHealth());
    }

    /**
     * @covers ::restoreFullHealth
     * @expectedException \Which1ispink\API\Core\Exception\InvalidOperationException
     * @expectedExceptionMessage Character is dead
     * @expectedExceptionCode 409
     */
    public function test_restoreFullHealth_on_dead_character_throws_InvalidOperationException()
    {
        $this->defaultCharacter->kill();
        $this->defaultCharacter->restoreFullHealth();
    }

    /**
     * @covers ::revive
     */
    public function test_revive_revives_dead_character_and_restores_20_health_points()
    {
        $this->defaultCharacter->kill();
        $this->defaultCharacter->revive();

        $this->assertTrue($this->defaultCharacter->isAlive());
        $this->assertEquals(20, $this->defaultCharacter->getHealth());
    }

    /**
     * @covers ::revive
     * @expectedException \Which1ispink\API\Core\Exception\InvalidOperationException
     * @expectedExceptionMessage Why revive a living character?
     * @expectedExceptionCode 409
     */
    public function test_revive_on_living_character_throws_InvalidOperationException()
    {
        $this->defaultCharacter->revive();
    }

    /**
     * @covers ::damage
     */
    public function test_damage_decreases_health()
    {
        $this->defaultCharacter->damage(20);

        $this->assertEquals(60, $this->defaultCharacter->getHealth());
    }

    /**
     * @covers ::damage
     * @expectedException \Which1ispink\API\Core\Exception\InvalidOperationException
     * @expectedExceptionMessage Character is already dead
     * @expectedExceptionCode 409
     */
    public function test_damage_on_dead_character_throws_InvalidOperationException()
    {
        $this->defaultCharacter->kill();
        $this->defaultCharacter->damage(20);
    }

    /**
     * @covers ::kill
     */
    public function test_kill_kills_character()
    {
        $this->defaultCharacter->kill();

        $this->assertEquals(0, $this->defaultCharacter->getHealth());
        $this->assertFalse($this->defaultCharacter->isAlive());
    }

    /**
     * @covers ::kill
     * @expectedException \Which1ispink\API\Core\Exception\InvalidOperationException
     * @expectedExceptionMessage What is dead may never die
     * @expectedExceptionCode 409
     */
    public function test_kill_on_dead_character_throws_InvalidOperationException()
    {
        $this->defaultCharacter->kill();
        $this->defaultCharacter->kill();
    }

    /**
     * @covers ::getDateCreated
     */
    public function test_getDateCreated_can_return_date_as_string()
    {
        $this->assertInternalType('string', $this->defaultCharacter->getDateCreated(true));
    }

    /**
     * @covers ::jsonSerialize
     */
    public function test_can_be_serialized_into_JSON()
    {
        $data = [
            'id' => null,
            'name' => 'Jack',
            'type' => Character::NINJA,
            'experience_points' => 30,
            'level' => 1,
            'power_points' => 60,
            'health' => 80,
            'is_alive' => true,
            'date_created' => $this->creationDate->format('Y-m-d H:i:s'),
        ];

        $this->assertEquals(json_encode($data), json_encode($this->defaultCharacter));
    }
}
