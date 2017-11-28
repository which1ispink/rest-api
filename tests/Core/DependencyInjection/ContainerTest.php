<?php
namespace Which1ispink\API\Core\DependencyInjection;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Which1ispink\API\Core\DependencyInjection\Container
 */
class ContainerTest extends TestCase
{
    /**
     * @var Container
     */
    private $container;

    public function setUp()
    {
        $this->container = new Container();

        $this->container->set('test_service1', function ($c) {
            return new \stdClass();
        });

        $this->container->set('test_service2', function ($c) {
            return new \stdClass();
        });
    }

    /**
     * @covers ::set
     */
    public function test_set()
    {
        $this->container->set('test_service3', function ($c) {
            return new \stdClass();
        });

        $this->assertTrue($this->container->has('test_service3'));
    }

    /**
     * @covers ::get
     */
    public function test_get()
    {
        $this->assertInstanceOf(
            \stdClass::class,
            $this->container->get('test_service1')
        );

        $this->assertInstanceOf(
            \stdClass::class,
            $this->container->get('test_service2')
        );
    }

    /**
     * @covers ::get
     * @expectedException \InvalidArgumentException
     */
    public function test_get_throws_InvalidArgumentException_on_undefined_services()
    {
        $this->container->get('imaginary_service');
    }

    /**
     * @covers ::has
     */
    public function test_has()
    {
        $this->assertTrue($this->container->has('test_service1'));
        $this->assertTrue($this->container->has('test_service2'));
        $this->assertFalse($this->container->has('imaginary_service'));
    }
}
