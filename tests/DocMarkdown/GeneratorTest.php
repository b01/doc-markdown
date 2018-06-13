<?php namespace QL\DocMarkdown\Tests;

use QL\DocMarkdown\Config;
use QL\DocMarkdown\DocBlockParser;
use QL\DocMarkdown\PhpTemplateEngine;
use QL\DocMarkdown\Generator;
use QL\DocMarkdown\PhpParser;

/**
 * Class ProcessorTest
 *
 * @package \QL\DocMarkdown\Tests
 * @coversDefaultClass \QL\DocMarkdown\Generator
 */
class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    /** @var string Directory for storing temporary files. */
    static private $tmpDir;

    /** @var \QL\DocMarkdown\Config|\PHPUnit_Framework_MockObject_MockObject */
    private $mockConfig;

    /** @var \QL\DocMarkdown\PhpTemplateEngine|\PHPUnit_Framework_MockObject_MockObject */
    private $mockPhpTplEng;

    /** @var string Path to PHP source files. */
    private $sourceFiles;

    /** @var \QL\DocMarkdown\PhpParser|\PHPUnit_Framework_MockObject_MockObject */
    private $mockPhpParser;

    /** @var string */
    private $templatesDir;

    static public function setupBeforeClass()
    {
        self::$tmpDir = TESTS_DIR . 'tmp' . DIRECTORY_SEPARATOR;

        if (!\is_dir(self::$tmpDir)) {
            \exec('mkdir -p ' . self::$tmpDir);
        }
    }

    static public function tearDownAfterClass()
    {
        \exec('rm -rf ' . self::$tmpDir);
    }

    public function setUp()
    {
        $this->sourceFiles = TEST_DUMMIES__DIR . DIRECTORY_SEPARATOR;

        $this->mockConfig = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockPhpParser = $this->getMockBuilder(PhpParser::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockPhpTplEng = $this->getMockBuilder(PhpTemplateEngine::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->templatesDir = TESTS_DIR . '..'
            . DIRECTORY_SEPARATOR . 'templates'
            . DIRECTORY_SEPARATOR;
    }

    /**
     * @covers ::__construct
     */
    public function testCanBeInitialized()
    {
        $processor = new Generator(
            $this->mockPhpParser,
            $this->mockConfig
        );

        $this->assertInstanceOf(Generator::class, $processor);
    }

    /**
     * @covers ::output
     * @uses \QL\DocMarkdown\Generator::__construct
     */
    public function testCanFailSilentlyWhenNoFilesToParse()
    {
        $this->mockPhpParser->expects($this->once())
            ->method('getFileDeclarations')
            ->willReturn(false);

        $processor = new Generator(
            $this->mockPhpParser,
            $this->mockConfig
        );

        $template = 'file';
        $outDir = self::$tmpDir . $template . DIRECTORY_SEPARATOR;

        $processor->output([
                $template => null,
            ],
            $outDir
        );
    }

    /**
     * @covers ::output
     * @uses \QL\DocMarkdown\Generator::__construct
     */
    public function testCanNotGenerateMarkdownFileWhenTemplateIsNotSet()
    {
        include FIXTURES_DIR . 'parsed-php-1.php';

        $this->mockPhpParser->expects($this->once())
            ->method('getFileDeclarations')
            ->willReturn($parsePhpFiles);

        $processor = new Generator(
            $this->mockPhpParser,
            $this->mockConfig
        );

        $template = 'file';
        $outDir = self::$tmpDir . $template . DIRECTORY_SEPARATOR;
        $templates = [];

        $actual = $processor->output($templates, $outDir);

        $this->assertTrue(count($actual) === 0);
    }

    /**
     * @covers ::output
     * @uses \QL\DocMarkdown\Generator::__construct
     */
    public function testCanPassParsedPhpDataToTemplateFile()
    {
        include FIXTURES_DIR . 'parsed-abstract-a.php';

        $this->mockPhpParser->expects($this->once())
            ->method('getFileDeclarations')
            ->willReturn($parsePhpFiles);

        $this->mockPhpParser->expects($this->once())
            ->method('getSourceDir')
            ->willReturn($this->sourceFiles);

        $processor = new Generator(
            $this->mockPhpParser,
            $this->mockConfig
        );

        $template = 'abstract-class';
        $outDir = self::$tmpDir . 'generator-tests' . DIRECTORY_SEPARATOR;
        $templates = [
            $template => (new PhpTemplateEngine())->setTemplate($this->templatesDir . $template),
        ];

        exec('mkdir -p ' . $outDir);

        $actual = $processor->output($templates, $outDir);

        $outFile = $outDir . 'AbstractDummyA.md';
        $contents = file_get_contents($outFile);
        $this->assertEquals($outFile, $actual[0]);
        $this->assertContains('# AbstractDummyA', $contents);
    }

    /**
     * @covers ::__construct
     */
    public function testCanProcessFileComments()
    {
        $structureFixture =  'AbstractDummyB';
        $parsedFixture =  'parsed-abstract-b.php';

        include FIXTURES_DIR . $parsedFixture;

        $this->mockPhpParser->expects($this->once())
            ->method('getFileDeclarations')
            ->willReturn($parsePhpFiles);

        $this->mockPhpParser->expects($this->once())
            ->method('getSourceDir')
            ->willReturn($this->sourceFiles);

        $processor = new Generator(
            $this->mockPhpParser,
            $this->mockConfig
        );

        $template = 'abstract-class';
        $outDir = self::$tmpDir . 'generator-tests' . DIRECTORY_SEPARATOR;
        $templates = [
            $template => (new PhpTemplateEngine())->setTemplate($this->templatesDir . $template),
        ];

        exec('mkdir -p ' . $outDir);

        $actual = $processor->output($templates, $outDir);

        $this->assertEquals($outDir . $structureFixture . '.md', $actual[0]);
    }

    /**
     * @covers ::output
     * @uses \QL\DocMarkdown\Generator::__construct
     */
    public function testCanProcessNamespaces()
    {
        include FIXTURES_DIR . 'parsed-abstract-a.php';

        $this->mockPhpParser->expects($this->once())
            ->method('getFileDeclarations')
            ->willReturn($parsePhpFiles);

        $this->mockPhpParser->expects($this->once())
            ->method('getSourceDir')
            ->willReturn($this->sourceFiles);

        $processor = new Generator(
            $this->mockPhpParser,
            $this->mockConfig
        );

        $template = 'abstract-class';
        $outDir = self::$tmpDir . $template . DIRECTORY_SEPARATOR;
        \exec('mkdir -p ' . $outDir);

        $actual = $processor->output([
                $template => (new PhpTemplateEngine())->setTemplate($this->templatesDir . $template),
            ],
            $outDir
        );

        $outFile = $outDir . 'AbstractDummyA.md';
        $contents = file_get_contents($outFile);
        $this->assertEquals($outFile, $actual[0]);
        $this->assertContains('namespace QL\\DocMarkdown\\Tests\\Dummies', $contents);
    }

    /**
     * @covers ::output
     * @uses \QL\DocMarkdown\Generator::__construct
     */
    public function testCanProcessClasses()
    {
        $this->generatePhpParserFixture($this->sourceFiles . 'ClassDummyA.php',
            'parsed-class-a.php');

        include FIXTURES_DIR . 'parsed-class-a.php';

        $this->mockPhpParser->expects($this->once())
            ->method('getFileDeclarations')
            ->willReturn($parsePhpFiles);

        $this->mockPhpParser->expects($this->once())
            ->method('getSourceDir')
            ->willReturn($this->sourceFiles);

        $processor = new Generator(
            $this->mockPhpParser,
            $this->mockConfig
        );

        $template = 'class';
        $outDir = self::$tmpDir . $template  . DIRECTORY_SEPARATOR;
        \exec('mkdir -p ' . $outDir);
        $actual = $processor->output([
                $template => (new PhpTemplateEngine())->setTemplate($this->templatesDir . $template),
            ],
            $outDir
        );
        $outFile = $outDir . 'ClassDummyA.md';
        $contents = file_get_contents($outFile);

        $this->assertEquals($outFile, $actual[0]);
        $this->assertContains('## class ClassDummyA', $contents);
    }

    private function generatePhpParserFixture($sourceFiles, $fileName)
    {
        $phpParser = new PhpParser($sourceFiles, new DocBlockParser());
        $parsePhpFiles = $phpParser->getFileDeclarations();
        $temp = var_export($parsePhpFiles, true);
        file_put_contents(FIXTURES_DIR . $fileName, '<?php $parsePhpFiles = ' . $temp . ';');
    }
}
?>