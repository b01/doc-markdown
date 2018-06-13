<?php
/**
 * @copyright ©2016 Quicken Loans Inc. All rights reserved. Trade
 * Secret, Confidential and Proprietary. Any dissemination outside
 * of Quicken Loans is strictly prohibited.
 */

namespace QL\DocMarkdown\Tests;

use QL\DocMarkdown\Config;

/**
 * Class ConfigTest
 *
 * @package \QL\DocMarkdown\Tests
 * @coversDefaultClass \QL\DocMarkdown\Config
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testInitialization()
    {
        $this->assertInstanceOf(Config::class, new COnfig());
    }

    /**
     * @covers ::__construct
     * @uses \QL\DocMarkdown\Config::loadFromFile
     */
    public function testInitializationWithFile()
    {
        $settings = new COnfig(
            FIXTURES_DIR . DIRECTORY_SEPARATOR
            . 'config-1.json'
        );
        $this->assertInstanceOf(Config::class, $settings);
    }

    /**
     * @covers ::loadFromFile
     * @covers ::get
     */
    public function testLoadingConfigurationFileLoadsATestValueSuccesfully()
    {
        $settings = new Config();
        $settings->loadFromFile(
            FIXTURES_DIR . DIRECTORY_SEPARATOR
            . 'config-1.json'
        );
        $this->assertEquals('1234', $settings->get('test'));
    }

    /**
     * @covers ::with
     * @covers ::get
     */
    public function testSetsATestValueSuccesfully()
    {
        $settings = new Config();
        $settings->with('test', 1234);
        $this->assertEquals('1234', $settings->get('test'));
    }
}
