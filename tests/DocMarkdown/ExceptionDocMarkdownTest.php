<?php namespace QL\Tests\DocMarkdown;
use QL\DocMarkdown\ExceptionDocMarkdown;

/**
 * Class ExceptionDocMarkdownTest
 *
 * @package QL\DocMarkdown\Tests
 * @coversDefaultClass \QL\DocMarkdown\ExceptionDocMarkdown
 */
class ExceptionDocMarkdownTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testVerifyThatADocMarkdownExceptionCanBeConstructed()
    {
        $exception = new ExceptionDocMarkdown(1);

        $this->assertInstanceOf('\\QL\\DocMarkDown\\ExceptionDocMarkdown', $exception);
    }

    /**
     * @covers ::getErrorMap
     * @covers ::getMessageByCode
     */
    public function testVerifyThatAnErrorCodeMapsToTheCorrectMessage()
    {
        $error = new ExceptionDocMarkdown(1, ['fakeDir']);

        $this->assertEquals(
            'Invalid file or directory "fakeDir".',
            $error->getMessage()
        );

        $this->assertEquals(1, $error->getCode());
    }

    /**
     * @covers ::getMessageByCode
     */
    public function testGetTheDefaultErrorWhenInvalidCodeIsUsed()
    {
        $error = new ExceptionDocMarkdown(-1);

        $this->assertEquals(
            'An unknown error has occurred.',
            $error->getMessage()
        );

        $this->assertEquals(2, $error->getCode());
    }
}
?>