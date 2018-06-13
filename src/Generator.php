<?php namespace QL\DocMarkdown;
/**
 * Traverse a folder, finding all PHP structure (abstracts/classes/function/interfaces/traits, etc) within. Then build
 * API documentation from their doc-blocks
 */

/**
 * Class Generator Orchestrate recursively traversing a folder to return all classes
 *
 * @package \QL\DocMarkdown
 */
class Generator
{
    /** @var \QL\DocMarkdown\Config */
    private $config;

    /** @var \QL\DocMarkdown\PhpParser Tool used to find PHP tokens */
    private $phpParser;

    /**
     * Generator constructor. Traverse a folder and find all PHP structure elements along with their corresponding
     * doc-comments. Then build API documentation from doc-blocks within.
     *
     * @param \QL\DocMarkdown\PhpParser $finder PHP file structure element parser.
     * @param \QL\DocMarkdown\Config Configuration settings.
     * @throws ExceptionDocMarkdown
     */
    public function __construct(PhpParser $finder, Config $config = NULL)
    {
        $this->phpParser = $finder;
        $this->config = $config;
    }

    /**
     * Output documentation from doc-blocks found, processing through the template engine.
     *
     * @param array $templates Array of templates.
     * @param string $outDir Output directory for the generated documentation.
     * @param callable $progress A callable that will receive the documentation file generated.
     * @return array List of files for which documentation was generated and saved.
     */
    public function output(array $templates, $outDir, callable $progress = NULL)
    {
        $parsedPhpFiles = $this->phpParser->getFileDeclarations();
        $successList = [];
        $outDir = realpath($outDir) . DIRECTORY_SEPARATOR;
        $sourceDir = $this->phpParser->getSourceDir();

        if (is_array($parsedPhpFiles)) {
            foreach ($parsedPhpFiles as $file) {
                $output = '';
                $phpFileType = $file[PhpParser::TYPE];

                // When no template exists for a type of PHP file parsed, then skip it.
                if (!array_key_exists($phpFileType, $templates)) {
                    continue;
                }

                $this->setCodebasePaths($file, $sourceDir);

                $template = $templates[$phpFileType];
                $output .= $template->render($file) . \PHP_EOL;

                $outfile = str_replace(
                    [$sourceDir, '.php'],
                    [$outDir, '.md'],
                    $file['file']
                );

                file_put_contents($outfile, $output . \PHP_EOL);

                $successList[] = $outfile;

                if ($progress !== null) {
                    $progress($file['file'], $outfile);
                }
            }
        }

        return $successList;
    }


    /**
     * @param \QL\DocMarkdown\PhpTemplateEngine $indexTemplate
     * @param string $outDir
     * @return boolean
     */
    public function buildIndex(PhpTemplateEngine $indexTemplate, $outDir)
    {
        $parsedPhpFiles = $this->phpParser->getFileDeclarations();
        $parsedPhpFiles['codebase'] = $this->getConfigVal('codebase');

        $output = $indexTemplate->render($parsedPhpFiles);

        $outfile = $outDir . DIRECTORY_SEPARATOR . 'index.md';

        return file_put_contents($outfile, $output) > 0;
    }

    /**
     * @param $key
     * @return NULL|string
     */
    private function getConfigVal($key)
    {
        return $this->config instanceof Config ? $this->config->get($key) : '';
    }

    /**
     * @param array $file
     * @param string $sourceDir
     */
    private function setCodebasePaths(array & $file, $sourceDir)
    {
        if ($this->getConfigVal('doCodebaseLink') === true) {
            $file['codebaseLink'] = str_replace(
                $sourceDir,
                $this->getConfigVal('codebase'),
                $file['file']
            );

            $file['codebase_path'] = str_replace(
                $sourceDir,
                '',
                $file['file']
            );
        }
    }
}
?>