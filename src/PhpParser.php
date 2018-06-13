<?php namespace QL\DocMarkdown;
/**
 * Traverse a folder, and find all PHP abstract/class/function/interface/trait tokens.
 */

/**
 * Class PhpParser Parse a directory with PHP files and returns a list of structural elements found in the files.
 *
 * Structural Elements are a collection of Programming constructs with each being preceded by a DocBlock.
 * @see http://phpdoc.org/docs/latest/glossary.html#term-structural-element
 *
 * @package QL\DocMarkdown
 */
class PhpParser
{
    /** Declaration array key that identifies the children of a structure. */
    const DEC_KEY_KIDS = 'children';

    /** Declaration array key that identifies the line where the it ends in the PHP file. */
    const DEC_KEY_DOC = 'doc_comment';

    /** Declaration array key that identifies the line where the it ends in the PHP file. */
    const DEC_KEY_LINE = 'line';

    /** Declaration array key that identifies the statement. */
    const DEC_KEY_STMT = 'statement';

    /** Declaration array key that identifies the type. */
    const DEC_KEY_TYPE = 'type';

    const TYPE = 'file_type';

    /** Declaration class type. */
    const TYPE_ABSTRACT = 'abstracts';

    /** Declaration class type. */
    const TYPE_CLASS = 'classes';

    /** Declaration class type. */
    const TYPE_CONST = 'constants';

    /** Declaration class type. */
    const TYPE_DOC_COMMENT = 'doc_comment';

    /** string File documentation. */
    const TYPE_FILE_COMMENT = 'file_comment';

    /** Declaration function type. */
    const TYPE_FUNCTIONS = 'functions';

    /** Declaration interface type. */
    const TYPE_INTERFACE = 'interfaces';

    const TYPE_METHOD = 'methods';

    /** Declaration namespace type. */
    const TYPE_NAMESPACE = 'namespace';

    /** Declaration class property type. */
    const TYPE_PROPERTY = 'properties';

    /** Declaration class property type. */
    const TYPE_TRAITS = 'traits';

    /** @var \QL\DocMarkdown\DocBlockParser */
    private $docBlockParser;

    /** @var array PHP files in a directory obtained from * glob() *. */
    private $files;

    /** @var array language tokens obtained from * token_get_all() * */
    private $fileDeclarations;

    /** @var string Path to source files. */
    private $sourceDir;

    /**
     * Construct
     *
     * @param string $pPath Directory or file to PHP source files.
     * @param DocBlockParser $docBlockParser For parsing doc-block strings into an array.
     * @param string $pFilter Provide a glob expression to use in place of the default "{$pPath}*.php")
     * @throws \QL\DocMarkdown\ExceptionDocMarkdown
     */
    public function __construct($pPath, DocBlockParser $docBlockParser, $pFilter = NULL)
    {
        // Throw an error when neither a valid file or directory is passed in.
        if (!\is_dir($pPath) && !file_exists($pPath)) {
            throw new ExceptionDocMarkdown(ExceptionDocMarkdown::BAD_PATH, [$pPath]);
        }

        // Process a directory (filtered by glob), or a sing file.
        if (is_dir($pPath)) {
            // Append trailing slash when not present.
            $lastChar = substr($pPath, -1);
            $this->sourceDir = $lastChar === '/' || $lastChar === '\\'
                ? $pPath : $pPath . DIRECTORY_SEPARATOR;

            // Code coverage is easy to see when this is not a ternary.
            $filter = $this->sourceDir . '*.php';
            if ($pFilter !== NULL) {
                $filter = $pFilter;
            }
            try {
                $this->files = \glob($filter);
            } catch (\Exception $error) {
                throw new ExceptionDocMarkdown(ExceptionDocMarkdown::BAD_GLOB_EXP, [var_export($pFilter, TRUE)]);
            }
        } else {
            $this->files = [$pPath];
            $this->sourceDir = dirname($pPath) . DIRECTORY_SEPARATOR;
        }

        $this->docBlockParser = $docBlockParser;
    }

    /**
     * Get tokens found in PHP files in the directory passed in at construction time.
     *
     * @return array|bool
     */
    public function getFileDeclarations()
    {
        if (!isset($this->fileDeclarations)) {
            $this->fileDeclarations = $this->process($this->files);
        }

        return $this->fileDeclarations;
    }

    /**
     * Get the source directory.
     *
     * @return string
     */
    public function getSourceDir()
    {
        return $this->sourceDir;
    }

    /**
     * Get the declaration type of a statement.
     *
     * @param string $statement Line of code to be checked for declaration type.
     * @param bool $hasParent
     * @return string
     */
    private function getType($statement, $hasParent = FALSE)
    {
        if ($hasParent && \strpos($statement, 'function') !== FALSE) {
            return self::TYPE_METHOD;
        } else if (\strpos($statement, 'abstract ') !== FALSE) {
            return self::TYPE_ABSTRACT;
        } else if (\strpos($statement, 'class ') !== FALSE) {
            return self::TYPE_CLASS;
        } else if (\strpos($statement, 'const ') !== FALSE) {
            return self::TYPE_CONST;
        } else if (\strpos($statement, 'function') !== FALSE) {
            return self::TYPE_FUNCTIONS;
        } else if (\strpos($statement, 'interface') !== FALSE) {
            return self::TYPE_INTERFACE;
        } else if (\strpos($statement, 'namespace') !== FALSE) {
            return self::TYPE_NAMESPACE;
        } else if (\strpos($statement, 'trait') !== FALSE) {
            return self::TYPE_TRAITS;
            // We check for properties last since they do not have
        } else if (\preg_match('/private|protected|public|static\w+\$/i', $statement) === 1) {
            return self::TYPE_PROPERTY;
        }

        return '';
    }

    /**
     * Get all PHP tokens from each file in the array.
     *
     * @param $files
     * @return array|bool
     */
    private function process($files)
    {
        if (\count($files) < 1) {
            return FALSE;
        }

        $fileDeclarations = [];

        foreach ($files as $key => $file) {
            $contents = \file_get_contents($file);
            $tokens = \token_get_all($contents);
            $parsed = $this->structureBuilder($tokens);
            $parsed['file'] = $file;
            $parsed['file_type'] = $this->getFileType($parsed);
            $fileDeclarations[] = $parsed;
        }

        return $fileDeclarations;
    }

    /**
     * Get file type based on the structures found within.
     *
     * @param array $parsed
     * @return string
     */
    private function getFileType(array & $parsed)
    {
        $type = 'file';

        if (array_key_exists(self::TYPE_CLASS, $parsed)) {
            $type = 'class';
        }

        if (array_key_exists(self::TYPE_ABSTRACT, $parsed)) {
            $type = 'abstract-class';
        }

        if (array_key_exists(self::TYPE_INTERFACE, $parsed)) {
            $type = 'interface';
        }

        if (array_key_exists(self::TYPE_TRAITS, $parsed)) {
            $type = 'trait';
        }

        return $type;
    }
    /**
     * Take a PHP file, and process it's content, converting to an array the can be used to retrieve structural elements.
     *
     * Turn output from \get_all_tokens into something more code usable.
     *
     * @param array & $tokens
     * @param bool $hasParent
     * @return array
     */
    private function structureBuilder(array & $tokens = NULL, $hasParent = FALSE)
    {
        $structures = [];
        $statement = '';
        $docComment = '';

        // Do rebuilding.
        while (($token = \array_shift($tokens)) !== NULL) {

            $tokenName = \is_array($token) ? \token_name($token[0]) : $token;

            // Filter out these tokens.
            if (\strcmp($tokenName, 'T_OPEN_TAG') === 0 // < ? php
                || \strcmp(($tokenName), 'T_CLOSE_TAG') === 0  // ? >
                || \strcmp('T_COMMENT', $tokenName) === 0 // Skip single line comments, they are not part of the standard.
            ) {
                CONTINUE;
            }

            // Append to the statement.
            $statement .= \is_string($token) ? $token : $token[1];

            // A doc-block always precedes a structural element, separated by whitespace token.
            if (\strcmp('T_DOC_COMMENT', $tokenName) === 0) {
                $docComment = $token;
                $statement = '';
                CONTINUE;
            }

            // end of statement.
            if (\is_string($token)) {
                $structure = NULL;
                $type = NULL;

                if ($token === ';' || $token === '{') {
                    $parsedComment = '';
                    if (\is_array($docComment)) {
                        $parsedComment = $this->docBlockParser->process(\trim($docComment[1]));
                    }
                    $structure = [
                        self::DEC_KEY_STMT => \trim($statement, " \t\n\r\0\x0B{;"),
                        self::DEC_KEY_DOC => $parsedComment
                    ];

                    // reset
                    $docComment = '';
                    $statement = '';
                }

                $type = $this->getType($structure[self::DEC_KEY_STMT], $hasParent);

                // Do not get function children.
                if ($token === '{'
                    && !($type === self::TYPE_FUNCTIONS || $type === self::TYPE_METHOD)) {
                    // add children recursively
                    $structure = \array_merge($structure, $this->structureBuilder($tokens, TRUE));
                } else if ($token === '}') {
                    return $structures;
                }

                if (\is_array($structure)) {
                    // store only types that are defined, skips things like foreach, and regular statements.
                    if ($type === self::TYPE_NAMESPACE) {
                        // Move the file doc comment up a level.
                        $structures[self::TYPE_FILE_COMMENT] = $structure[self::TYPE_DOC_COMMENT];
                        $structures[$type] = $structure[self::DEC_KEY_STMT];
                    } else if (!empty($type)) {
                        $structures[$type][] = $structure;
                    }
                }
            }
        }

        return $structures;
    }
}
?>