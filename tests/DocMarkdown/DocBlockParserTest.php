<?php namespace QL\DocMarkdown\Tests;

use QL\DocMarkdown\DocBlockParser;

/**
 * Class DocBlockParserTest
 *
 * @package \QL\Tests\DocMarkdown
 * @coversDefaultClass \QL\DocMarkdown\DocBlockParser
 */
class DocBlockParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getParams
     */
    public function testExtractParamDefinitionFromDocBlock()
    {
        $parser = new DocBlockParser();

        $parsedData = $parser->process('/** @param string $testParam1 Test parameter one. */');

        $actual = $parsedData['params'];

        $this->assertEquals('$testParam1', $actual[0]['name']);
        $this->assertEquals('string', $actual[0]['type']);
        $this->assertEquals('Test parameter one.', $actual[0]['description']);
    }

    /**
     * @covers ::getSummary
     */
    public function testExtractTheSummaryFromSingleLineDocBlock()
    {
        $parser = new DocBlockParser();

        $parsedData = $parser->process('/** Test summary */');

        $actual = $parsedData['summary'];

        $this->assertEquals('Test summary', $actual);
    }

    /**
     * @covers ::getSummary
     */
    public function testExtractTheSummaryFromMultiLineDocBlock()
    {
        $parser = new DocBlockParser();

        $dockBlock = \file_get_contents(FIXTURES_DIR . 'doc-block-summary-no-period.txt');

        $parsedData = $parser->process($dockBlock);

        $actual = $parsedData['summary'];

        $this->assertEquals('Single line summary text with no period', $actual);
    }

    /**
     * @covers ::getSummary
     */
    public function testExtractTheSummaryFromSingleLineDocBlockWithPeriod()
    {
        $parser = new DocBlockParser();

        $parsedData = $parser->process('/** Test summary. */');

        $actual = $parsedData['summary'];

        $this->assertEquals('Test summary.', $actual);
    }

    /**
     * @covers ::getSummary
     */
    public function testExtractTheSummaryFromMultiLineDocBlockWithPeriod()
    {
        $parser = new DocBlockParser();

        $dockBlock = \file_get_contents(FIXTURES_DIR . 'doc-block-summary-with-period.txt');

        $parsedData = $parser->process($dockBlock);

        $actual = $parsedData['summary'];

        $this->assertEquals('Single line summary text with a period.', $actual);
    }

    /**
     * @covers ::getSummary
     */
    public function testExtractTheSummaryWhenSpreadAcrossMultiLines()
    {
        $parser = new DocBlockParser();

        $dockBlock = \file_get_contents(FIXTURES_DIR . 'doc-block-summary-on-two-lines.txt');

        $parsedData = $parser->process($dockBlock);

        $actual = $parsedData['summary'];

        $this->assertEquals('Single line summary text with a period.', $actual);
    }

    /**
     * @covers ::process
     * @covers ::getReturn
     * @covers ::getParams
     * @covers ::getSummary
     */
    public function testExtractMixedDataFromAMultiLineDocBlock()
    {
        $parser = new DocBlockParser();

        $dockBlock = \file_get_contents(FIXTURES_DIR . 'doc-block-1.txt');

        $actual = $parser->process($dockBlock);

        $this->assertEquals('Summary test.', $actual['summary']);
        $this->assertEquals('$test', $actual['params'][0]['name']);
        $this->assertEquals('null', $actual['return']['type']);
    }

    /**
     * @covers ::process
     */
    public function testExtractAParamFromAMultiLineDocBlockWithNoTypeOrDescription()
    {
        $parser = new DocBlockParser();

        $dockBlock = \file_get_contents(FIXTURES_DIR . 'doc-block-2.txt');

        $actual = $parser->process($dockBlock);

        // Names
        $this->assertEquals('$test', $actual['params'][0]['name']);

        return $actual;
    }

    /**
     * @covers ::process
     * @depends testExtractAParamFromAMultiLineDocBlockWithNoTypeOrDescription
     */
    public function testExtractAParamFromAMultiLineDocBlockWithTypeButNoDescription($actual)
    {
        $this->assertEquals('$test2', $actual['params'][1]['name']);
        $this->assertEquals('string', $actual['params'][1]['type']);
    }

    /**
     * @covers ::process
     * @depends testExtractAParamFromAMultiLineDocBlockWithNoTypeOrDescription
     */
    public function testExtractAParamFromAMultiLineDocBlockWithTypeAndDescription($actual)
    {
        $this->assertEquals('$test3', $actual['params'][2]['name']);
        $this->assertEquals('array', $actual['params'][2]['type']);
        $this->assertEquals('Description for test-3 variable.', $actual['params'][2]['description']);
    }

    /**
     * @covers ::process
     * @depends testExtractAParamFromAMultiLineDocBlockWithNoTypeOrDescription
     */
    public function testExtractParamFromAMultiLineDocBlockWithTypeAndDescription($actual)
    {
        $this->assertEquals('$test4', $actual['params'][3]['name']);
        $this->assertEquals('int', $actual['params'][3]['type']);
        $this->assertEquals(
            'Description for test-4 variable descriptions extends beyond one line',
            $actual['params'][3]['description']
        );
    }

    /**
     * @covers ::process
     * @depends testExtractAParamFromAMultiLineDocBlockWithNoTypeOrDescription
     */
    public function testExtractAParamFromAMultiLineDocBlockWithNoTypeAndDescription($actual)
    {
        $this->assertEquals('$test5', $actual['params'][4]['name']);
        $this->assertEquals(
            'Test-5 variable has no type and the descriptions extends beyond one line.',
            $actual['params'][4]['description']
        );
    }
}
?>