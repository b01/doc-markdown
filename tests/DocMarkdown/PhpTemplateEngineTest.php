<?php namespace QL\DocMarkdown\Tests;

use QL\DocMarkdown\PhpTemplateEngine;

/**
 * @class PhpTemplateEngineTest
 *
 * @package \QL\DocMarkdown\Tests
 * @coversDefaultClass \QL\DocMarkdown\PhpTemplateEngine
 */
class PhpTemplateEngineTest extends \PHPUnit_Framework_TestCase
{
    /** @var  string Directory where the test PHP structures for DocMarkdown reside. */
    private $testTemplatesDir;

    public function setUp()
    {
        $this->testTemplatesDir = FIXTURES_DIR . 'templates' . DIRECTORY_SEPARATOR;
    }

    /**
     * @covers ::setTemplate
     * @covers ::render
     */
    public function testTakesATemplate()
    {
        $eng = new PhpTemplateEngine();

        $eng->setTemplate($this->testTemplatesDir . 'test');

        $actual = $eng->render(['test' => '1234']);

        $this->assertEquals('1234', $actual);
    }
}
?>