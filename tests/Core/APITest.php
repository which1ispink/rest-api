<?php
namespace Which1ispink\API\Core;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Which1ispink\API\Core\Api
 */
class APITest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function test_gets_properly_instantiated_with_config()
    {
        $config = [
            'testConfig' => 'value',
            'testConfig2' => 'value2'
        ];
        $api = new Api($config);

        $this->assertInstanceOf(Api::class, $api);
        $this->assertEquals($config, $api->getAppConfig());
    }
}
