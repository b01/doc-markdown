<?php namespace QL\DocMarkdown\Tests;

use QL\DocMarkdown\DocBlockParser;
use QL\DocMarkdown\PhpParser;

/**
 * Class StructureFinderTest
 *
 * @package \QL\DocMarkdown\Tests
 * @coversDefaultClass \QL\DocMarkdown\PhpParser
 */
class PhpParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var \QL\DocMarkdown\DocBlockParser|\PHPUnit_Framework_MockObject_MockObject */
    private $mockDocBlockParser;

    /** @var  string Directory where the test PHP structures for DocMarkdown reside. */
    private $testDummiesDir;

    public function setUp()
    {
        $this->testDummiesDir = TEST_DUMMIES__DIR . DIRECTORY_SEPARATOR;

        $this->mockDocBlockParser = $this->getMockBuilder(DocBlockParser::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @covers ::__construct
     */
    public function testProperlyInitializesWithAValidDirectory()
    {
        $structureFinder = new PhpParser(
            $this->testDummiesDir,
            $this->mockDocBlockParser
        );

        $this->assertInstanceOf(PhpParser::class, $structureFinder);
    }

    /**
     * @covers ::__construct
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid file or directory
     */
    public function testThrowsAnExceptionWhenInitializedWithABadDirectory()
    {
        (new PhpParser('bad-dir', $this->mockDocBlockParser));
    }

    /**
     * @covers ::__construct
     */
    public function testProperlyInitializesWithAValidFile()
    {
        $file = $this->testDummiesDir
            . DIRECTORY_SEPARATOR . 'Traits'
            . DIRECTORY_SEPARATOR . 'TraitA.php';

        $structureFinder = new PhpParser($file,
            $this->mockDocBlockParser
        );

        $this->assertInstanceOf(PhpParser::class, $structureFinder);
    }

    /**
     * @covers ::process
     */
    public function testFailsGracefullyWhenADirectoryHasNoFiles()
    {
        $emptyDir = $this->testDummiesDir . 'empty_dir';

        // in case a previous test run failed.
        if (\is_dir($emptyDir)) {
            \rmdir($emptyDir);
        }

        // Make an empty directory to test against.
        \mkdir($emptyDir);

        $structureFinder = new PhpParser($emptyDir,
            $this->mockDocBlockParser
        );
        $actual = $structureFinder->getFileDeclarations();
        $this->assertFalse($actual);

        // Cleanup after test.
        \rmdir($emptyDir);
    }

    /**
     * @covers ::getFileDeclarations
     * @covers ::process
     * @covers ::structureBuilder
     * @covers ::getType
     */
    public function testCanFindANamespaceToken()
    {
        $structureFinder = new PhpParser($this->testDummiesDir,
            $this->mockDocBlockParser
        );
        $actual = $structureFinder->getFileDeclarations();
        $this->assertEquals(
            'namespace QL\\DocMarkdown\\Tests\\Dummies',
            $actual[0]['namespace']
        );
    }

    /**
     * @covers ::getFileDeclarations
     * @covers ::process
     * @covers ::structureBuilder
     * @covers ::getType
     */
    public function testCanFindInterfaceTokens()
    {
        $file = 4;
        $i = 0;
        $structureFinder = new PhpParser($this->testDummiesDir,
            $this->mockDocBlockParser
        );
        $actual = $structureFinder->getFileDeclarations();
        $this->assertEquals(
            'interface InterfaceDummyA',
            $actual[$file]['interfaces'][$i][PhpParser::DEC_KEY_STMT]
        );
    }

    /**
     * @covers ::getFileDeclarations
     * @covers ::process
     * @covers ::structureBuilder
     * @covers ::getType
     */
    public function testCanFindClassTokens()
    {
        $file = 3;
        $i = 0;
        $structureFinder = new PhpParser($this->testDummiesDir,
            $this->mockDocBlockParser
        );
        $actual = $structureFinder->getFileDeclarations();
        $this->assertEquals(
            'class ClassDummyA',
            $actual[$file]['classes'][$i][PhpParser::DEC_KEY_STMT]
        );
    }

    /**
     * @covers ::getFileDeclarations
     * @covers ::process
     * @covers ::structureBuilder
     * @covers ::getType
     */
    public function testCanFindDocCommentBlocks()
    {
        $file =
            $this->testDummiesDir . DIRECTORY_SEPARATOR . 'AbstractDummyA.php';

        $this->mockDocBlockParser->expects($this->any())
            ->method('process')
            ->willReturn(['test']);

        $i = 0;
        $structureFinder = new PhpParser($file,
            $this->mockDocBlockParser
        );
        $actual = $structureFinder->getFileDeclarations();
        $this->assertContains('test', $actual[0]['abstracts'][$i][PhpParser::DEC_KEY_DOC]);
    }

    /**
     * @covers ::getSourceDir
     */
    public function testReturnsTheDirectoryUsedToSearchForSourceFiles()
    {
        $structureFinder = new PhpParser($this->testDummiesDir,
            $this->mockDocBlockParser
        );
        $this->assertEquals($this->testDummiesDir, $structureFinder->getSourceDir());
    }

    /**
     * @covers ::getSourceDir
     */
    public function testReturnsTheDirectoryUsedToSearchForSourceFilesWhenGivenAFilePath()
    {
        $sourceDur = $this->testDummiesDir . DIRECTORY_SEPARATOR . 'Traits';
        $file = $sourceDur
            . DIRECTORY_SEPARATOR . 'TraitA.php';

        $structureFinder = new PhpParser($file,
            $this->mockDocBlockParser
        );

        $this->assertEquals($sourceDur . DIRECTORY_SEPARATOR, $structureFinder->getSourceDir());
    }

    /**
     * @covers ::getType
     * @use \QL\DocMarkdown\PhpParser\getFileDeclarations
     * @use \QL\DocMarkdown\PhpParser\process
     * @use \QL\DocMarkdown\PhpParser\structureBuilder
     */
    public function testTraitsAreIndexedCorrectly()
    {
        $file = $this->testDummiesDir
            . DIRECTORY_SEPARATOR . 'Traits'
            . DIRECTORY_SEPARATOR . 'TraitA.php';

        $this->mockDocBlockParser->expects($this->any())
            ->method('process')
            ->willReturn(['test']);

        $i = 0;
        $structureFinder = new PhpParser(
            $file,
            $this->mockDocBlockParser
        );
        $actual = $structureFinder->getFileDeclarations();
        $this->assertContains('test', $actual[0]['traits'][$i][PhpParser::DEC_KEY_DOC]);
    }

    /**
     * @covers ::structureBuilder
     * @use \QL\DocMarkdown\PhpParser\getFileDeclarations
     * @use \QL\DocMarkdown\PhpParser\process
     */
    public function testGracefullyHandlesAPhpFileWithNoStructuresOrDocBlocks()
    {
        $structureFinder = new PhpParser(
            $this->testDummiesDir . 'OffKilters' . DIRECTORY_SEPARATOR . 'NoTokensOrDocBlocks.php',
            $this->mockDocBlockParser
        );
        $actual = $structureFinder->getFileDeclarations();
        $this->assertArrayHasKey('file', $actual[0]);
    }

    /**
     * @covers ::__construct
     * @use \QL\DocMarkdown\PhpParser\getFileDeclarations
     * @use \QL\DocMarkdown\PhpParser\process
     * @use \QL\DocMarkdown\PhpParser\structureBuilder
     */
    public function testCanUseACustomGlobExpression()
    {
        $structureFinder = new PhpParser(
            $this->testDummiesDir,
            $this->mockDocBlockParser,
            $this->testDummiesDir . 'AllKinds.php'
        );
        $actual = $structureFinder->getFileDeclarations();
        $this->assertEquals(1, count($actual));
        $this->assertEquals($this->testDummiesDir . 'AllKinds.php', $actual[0]['file']);
    }

    /**
     * @covers ::__construct
     * @expectedException \QL\DocMarkdown\ExceptionDocMarkdown
     * @expectedExceptionMessage Bad expression passed to glob "array
     * @expectedExceptionCode 3
     */
    public function testThrowsAnExceptionWhenPassedABadGlobExpression()
    {
        $structureFinder = new PhpParser(
            $this->testDummiesDir,
            $this->mockDocBlockParser,
            []
        );
    }

    /**
     * @covers ::__construct
     * @use \QL\DocMarkdown\PhpParser\getFileDeclarations
     * @use \QL\DocMarkdown\PhpParser\process
     * @use \QL\DocMarkdown\PhpParser\structureBuilder
     */
    public function testCanFindStructoresWhenPassedASingleSourceFile()
    {
        $structureFinder = new PhpParser(
            $this->testDummiesDir . 'AllKinds.php',
            $this->mockDocBlockParser
        );
        $actual = $structureFinder->getFileDeclarations();
        $this->assertEquals(1, count($actual));
        $this->assertEquals($this->testDummiesDir . 'AllKinds.php', $actual[0]['file']);
    }

    /**
     * @covers ::structureBuilder
     * @use \QL\DocMarkdown\PhpParser\_construct
     * @use \QL\DocMarkdown\PhpParser\getFileDeclarations
     * @use \QL\DocMarkdown\PhpParser\process
     * @use \QL\DocMarkdown\PhpParser\structureBuilder
     */
    public function testDoesNotReportStatementsAsPropertiesInMethods()
    {
        $file = $this->testDummiesDir
            . DIRECTORY_SEPARATOR . 'OffKilters'
            . DIRECTORY_SEPARATOR . 'TraitA.php';

        $structureFinder = new PhpParser($file,
            $this->mockDocBlockParser
        );
        $actual = $structureFinder->getFileDeclarations();
        $this->assertArrayNotHasKey('properties', $actual[0]['traits'][0]['methods'][0]);
    }

    /**
     * @covers ::structureBuilder
     * @use \QL\DocMarkdown\PhpParser\_construct
     * @use \QL\DocMarkdown\PhpParser\getFileDeclarations
     * @use \QL\DocMarkdown\PhpParser\process
     * @use \QL\DocMarkdown\PhpParser\structureBuilder
     */
    public function testDoesNotReportStatementsAsPropertiesInFunctions()
    {
        $file = $this->testDummiesDir
            . DIRECTORY_SEPARATOR . 'OffKilters'
            . DIRECTORY_SEPARATOR . 'function1.php';

        $structureFinder = new PhpParser($file,
            $this->mockDocBlockParser
        );
        $actual = $structureFinder->getFileDeclarations();
        $this->assertArrayNotHasKey('properties', $actual[0]['functions'][0]);
    }

    /**
     * @covers ::getType
     * @use \QL\DocMarkdown\PhpParser\_construct
     * @use \QL\DocMarkdown\PhpParser\getFileDeclarations
     * @use \QL\DocMarkdown\PhpParser\process
     * @use \QL\DocMarkdown\PhpParser\structureBuilder
     */
    public function testFindsPropertiesInClasses()
    {
        $file = $this->testDummiesDir
            . DIRECTORY_SEPARATOR . 'Traits'
            . DIRECTORY_SEPARATOR . 'TraitA.php';

        $structureFinder = new PhpParser($file,
            $this->mockDocBlockParser
        );
        $structures = $structureFinder->getFileDeclarations();
        $actual = $structures[0]['traits'][0];
        $this->assertArrayHasKey('properties', $actual);
        $this->assertEquals('private $property1', $actual['properties'][0]['statement']);
    }

    /**
     * @covers ::structureBuilder
     * @use \QL\DocMarkdown\PhpParser\_construct
     * @use \QL\DocMarkdown\PhpParser\getFileDeclarations
     * @use \QL\DocMarkdown\PhpParser\process
     * @use \QL\DocMarkdown\PhpParser\structureBuilder
     */
    public function testGracefullyHandlesFileWithNoTokensNotEvenPhpTags()
    {
        $structureFinder = new PhpParser(
            $this->testDummiesDir . 'OffKilters' . DIRECTORY_SEPARATOR . 'Empty.php',
            $this->mockDocBlockParser
        );
        $actual = $structureFinder->getFileDeclarations();
        $this->assertArrayHasKey('file', $actual[0]);
    }


    // TODO: Write test to ensure that multiple functions can be parsed in a single PHP file.
    // NOTE: When a function and trait are in the same file, only the first will be parsed, find out why.
}
?>