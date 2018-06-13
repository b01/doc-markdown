
### class DocBlockParser

# File: ../src/DocBlockParser.php


## namespace QL\DocMarkdown
#### const REGEX_FOR_PARAMS = '#'
        . '\*\s+\@param'         . '(?:\s+([^\$]+))?'         . '\s+([^\s]+)'         . '((?:(?!\.|\s+\*\s+\@|\s+\*\s+\*\s+|\s+\*\/).\.?)*)'     . '#s'

#### const REGEX_FOR_RETURN = '#\*\s+\@return\s+([^\s]+)(?=(.*?)(?:\@|\s+\*\s+|\s+\*\/))?#s'

#### const REGEX_FOR_SUMMARY = '#^/\*\*\s+(\*\s+)?(?:(.+?)((\.)|\@|\r?\n\*\r?\n|\*\/))#s'

#### public function __construct()



