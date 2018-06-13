<?php namespace QL\DocMarkdown;

/**
 * Class DocBlockParser
 *
 * @package \QL\DocMarkdown
 * @todo Implement the remaining annotation tags.
 */
class DocBlockParser
{
    /**
     *
     */
    const REGEX_FOR_PARAMS = '#'
        . '\*\s+\@param' // look for param doc-blocks.
        . '(?:\s+([^\$]+))?' // capture the variable type.
        . '\s+([^\s]+)' // Capture the variable name.
        . '((?:(?!\.|\s+\*\s+\@|\s+\*\s+\*\s+|\s+\*\/).\.?)*)' // Capture the description
        . '#s'; // . matches newlines

    /**
     *
     */
    const REGEX_FOR_RETURN = '#\*\s+\@return\s+([^\s]+)(?=(.*?)(?:\@|\s+\*\s+|\s+\*\/))?#s';

    /**
     *
     */
    const REGEX_FOR_SUMMARY = '#^/\*\*\s+(\*\s+)?(?:(.+?)((\.)|\@|\r?\n\*\r?\n|\*\/))#s';

    /**
     * DocBlockParser constructor.
     */
    public function __construct()
    {
    }

    /**
     * Parse parameters in a doc-block, adding any found to the array referenced.
     *
     * @param string $docBlock
     * @param array $output
     * @return mixed
     */
    private function getParams($docBlock, array & $output)
    {
        $key = 'params';
        $output[$key] = [];

        return \preg_replace_callback(self::REGEX_FOR_PARAMS,
            /**
             * Extract @param field in a doc-block.
             * @param array $groups
             * @return string
             */
            function (array $groups) use (& $output, & $key) {
                // Let's format the array to be a bit more friendly.
                $output[$key][] = [
                    'name' => $groups[2],
                    'type' => $groups[1],
                    'description' => \trim(str_replace(["\n * ", "\r\n * ", "\r * "], ' ', $groups[3]))
                ];

                // Remove the parsed content from the string.
                return '';
            },
            $docBlock
        );
    }

    /**
     * Parse summary in a doc-block.
     *
     * @param $docBlock
     * @param array $output
     * @return mixed
     */
    private function getReturn($docBlock, array & $output)
    {
        $key = 'return';
        $output[$key] = [];

        return \preg_replace_callback(self::REGEX_FOR_RETURN,
            /**
             * Perform return extraction.
             *
             * This will remove the return entry from the doc-block and store it in an array.
             *
             * @param array $groups
             * @return string
             */
            function (array $groups) use (& $output, & $key) {
                // Get the punctuation back.
                $output[$key] = [
                    'type' => $groups[1]
                ];

                // Remove the parsed content from the string.
                return '';
            },
            $docBlock
        );
    }

    /**
     * Parse summary in a doc-block.
     *
     * @param $docBlock
     * @param array $output
     * @return mixed
     */
    private function getSummary($docBlock, array & $output)
    {
        $key = 'summary';
        $output[$key] = [];

        return \preg_replace_callback(self::REGEX_FOR_SUMMARY,
            /**
             * Perform summary extraction.
             *
             * This will remove the summary from the doc-block and store it in an array.
             *
             * @param array $groups
             * @return string
             */
            function (array $groups) use (& $output, & $key) {
                // Get the punctuation back.
                $fullStop = \count($groups) > 4 ? $groups[4] : '';
                // TODO: Maybe replace with regular expression.
                $summary = str_replace(["\n * ", "\r\n * ", "\r * "], ' ', $groups[2]);
                // Store the summary.
                $output[$key] = \trim($summary, " \t\r\n*") . $fullStop;

                // Remove the parsed content from the string.
                return '';
            },
            $docBlock
        );
    }

    /**
     * Parse a doc-block.
     *
     * @param string $dockBlock
     * @return array
     */
    public function process($dockBlock)
    {
        $output = [];

        // When a match is found, that part is extracted, thus making the string shorter afterward, and faster for the
        // following function to find it's match, since there is less to parse.

        // Parse parameters.
        $dockBlock = $this->getParams($dockBlock, $output);
        // Parse summary.
        $dockBlock = $this->getSummary($dockBlock, $output);
        // Parse return.
        $dockBlock = $this->getReturn($dockBlock, $output);

        return $output;
    }
}
?>