<?php
namespace Which1ispink\API\Service;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Which1ispink\API\Entity\Character;
use Which1ispink\API\Mapper\CharacterMapperInterface;

/**
 * @coversDefaultClass \Which1ispink\API\Service\CharacterService
 */
class CharacterServiceTest extends TestCase
{
    /**
     * @covers ::find
     */
    public function test_find_returns_result_returned_from_mapper()
    {
        $characterStub = new Character('Jack');

        $characterMapperProphecy = $this->prophesize(CharacterMapperInterface::class);
        $characterMapperProphecy
            ->find(10)
            ->shouldBeCalled()
            ->willReturn($characterStub);

        $characterMapper = $characterMapperProphecy->reveal();

        $characterService = new CharacterService($characterMapper);
        $character = $characterService->find(10);

        $this->assertInstanceOf(Character::class, $character);
        $this->assertEquals('Jack', $character->getName());
    }

    /**
     * @covers ::findAll
     */
    public function test_findAll_returns_result_returned_from_mapper()
    {
        $characterStubs = [
            new Character('Jack'),
            new Character('Max')
        ];

        $characterMapperProphecy = $this->prophesize(CharacterMapperInterface::class);
        $characterMapperProphecy
            ->findAll()
            ->shouldBeCalled()
            ->willReturn($characterStubs);

        $characterMapper = $characterMapperProphecy->reveal();

        $characterService = new CharacterService($characterMapper);
        $characters = $characterService->findAll();

        $this->assertInstanceOf(Character::class, $characters[0]);
        $this->assertEquals('Jack', $characters[0]->getName());
        $this->assertEquals('Max', $characters[1]->getName());
    }

    /**
     * @covers ::create
     */
    public function test_create_creates_new_character_with_only_name_and_calls_mapper_to_persist_it()
    {
        $characterStub = new Character('Max');

        $characterMapperProphecy = $this->prophesize(CharacterMapperInterface::class);
        $characterMapperProphecy
            ->add(Argument::type(Character::class))
            ->shouldBeCalled()
            ->willReturn($characterStub);

        $characterMapper = $characterMapperProphecy->reveal();

        $characterService = new CharacterService($characterMapper);
        $character = $characterService->create([
            'name' => 'Max'
        ]);

        $this->assertInstanceOf(Character::class, $character);
        $this->assertEquals('Max', $character->getName());
    }

    /**
     * @covers ::create
     */
    public function test_create_creates_new_character_with_name_and_type_and_calls_mapper_to_persist_it()
    {
        $characterStub = new Character('Max', Character::ZOMBIE);

        $characterMapperProphecy = $this->prophesize(CharacterMapperInterface::class);
        $characterMapperProphecy
            ->add(Argument::type(Character::class))
            ->shouldBeCalled()
            ->willReturn($characterStub);

        $characterMapper = $characterMapperProphecy->reveal();

        $characterService = new CharacterService($characterMapper);
        $character = $characterService->create([
            'name' => 'Max',
            'type' => Character::ZOMBIE
        ]);

        $this->assertInstanceOf(Character::class, $character);
        $this->assertEquals('Max', $character->getName());
        $this->assertEquals(Character::ZOMBIE, $character->getType());
    }

    /**
     * @covers ::create
     * @expectedException \Which1ispink\API\Core\Exception\InvalidArgumentException
     * @expectedExceptionMessage Character name must be provided
     * @expectedExceptionCode 400
     */
    public function test_create_throws_InvalidArgumentException_if_name_parameter_is_empty()
    {
        $characterMapper = $this->prophesize(CharacterMapperInterface::class)->reveal();
        $characterService = new CharacterService($characterMapper);
        $characterService->create([
            'type' => Character::ZOMBIE
        ]);
    }

    /**
     * @covers ::update
     */
    public function test_update_with_transform_action_applies_action_on_character_and_calls_mapper_to_persist_the_update()
    {
        $characterStub = new Character('Max', Character::ZOMBIE);

        $characterMapperProphecy = $this->prophesize(CharacterMapperInterface::class);
        $characterMapperProphecy
            ->find(10)
            ->shouldBeCalled()
            ->willReturn($characterStub);

        $characterMapperProphecy
            ->update($characterStub, 10)
            ->shouldBeCalled()
            ->willReturn($characterStub);

        $characterMapper = $characterMapperProphecy->reveal();

        $characterService = new CharacterService($characterMapper);
        $character = $characterService->update([
            'action' => 'transform',
            'value' => Character::VAMPIRE
        ], 10);

        $this->assertInstanceOf(Character::class, $character);
        $this->assertEquals('Max', $character->getName());
        $this->assertEquals(Character::VAMPIRE, $character->getType());
    }

    /**
     * @covers ::update
     */
    public function test_update_with_damage_action_applies_action_on_character_and_calls_mapper_to_persist_the_update()
    {
        $characterStub = new Character('Max', Character::ZOMBIE, 10, 10);

        $characterMapperProphecy = $this->prophesize(CharacterMapperInterface::class);
        $characterMapperProphecy
            ->find(10)
            ->shouldBeCalled()
            ->willReturn($characterStub);

        $characterMapperProphecy
            ->update($characterStub, 10)
            ->shouldBeCalled()
            ->willReturn($characterStub);

        $characterMapper = $characterMapperProphecy->reveal();

        $characterService = new CharacterService($characterMapper);
        $character = $characterService->update([
            'action' => 'damage',
            'value' => 20
        ], 10);

        $this->assertInstanceOf(Character::class, $character);
        $this->assertEquals('Max', $character->getName());
        $this->assertEquals(80, $character->getHealth());
    }

    /**
     * @covers ::update
     * @expectedException \Which1ispink\API\Core\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid action description
     * @expectedExceptionCode 400
     */
    public function test_update_throws_InvalidArgumentException_if_either_parameter_is_empty()
    {
        $characterMapper = $this->prophesize(CharacterMapperInterface::class)->reveal();
        $characterService = new CharacterService($characterMapper);
        $characterService->update([
            'value' => 20
        ], 10);
    }

    /**
     * @covers ::update
     * @expectedException \Which1ispink\API\Core\Exception\InvalidOperationException
     * @expectedExceptionMessage Unsupported action
     * @expectedExceptionCode 400
     */
    public function test_update_throws_InvalidOperationException_if_given_action_unsupported()
    {
        $character = new Character('Max');

        $characterMapperProphecy = $this->prophesize(CharacterMapperInterface::class);
        $characterMapperProphecy
            ->find(10)
            ->shouldBeCalled()
            ->willReturn($character);

        $characterMapper = $characterMapperProphecy->reveal();

        $characterService = new CharacterService($characterMapper);
        $characterService->update([
            'action' => 'fly',
            'value' => 20
        ], 10);
    }

    /**
     * @covers ::kill
     */
    public function test_kill_kills_character_and_calls_mapper_to_update_the_character()
    {
        $character = new Character('Max');

        $characterMapperProphecy = $this->prophesize(CharacterMapperInterface::class);
        $characterMapperProphecy
            ->find(10)
            ->shouldBeCalled()
            ->willReturn($character);

        $characterMapperProphecy
            ->update($character, 10)
            ->shouldBeCalled();

        $characterMapper = $characterMapperProphecy->reveal();

        $characterService = new CharacterService($characterMapper);
        $characterService->kill(10);

        $this->assertFalse($character->isAlive());
    }
}
