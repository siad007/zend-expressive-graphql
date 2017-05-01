<?php

namespace Siad007\ZendExpressive\GraphQLTest;

use PHPUnit\Framework\TestCase;
use Siad007\ZendExpressive\GraphQL\ConfigProvider;

/**
 * Class ConfigProviderTest
 * @package Siad007\ZendExpressive\GraphQLTest
 */
class ConfigProviderTest extends TestCase
{
    private $configProvider;

    public function setUp()
    {
        $this->configProvider = new ConfigProvider();
    }

    public function tearDown()
    {
        $this->configProvider = null;
    }

    /**
     * @test
     */
    public function invokation()
    {
        $currConfigPro = $this->configProvider;
        $this->assertSame([
            'zend-expressive-graphql' => [
                'some-setting' => 'default value',
            ]
        ], $currConfigPro());
    }
}
