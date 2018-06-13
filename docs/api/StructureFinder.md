# File: ../src/StructureFinder.php

## namespace QL\DocMarkdown
### class StructureFinder
#### /** Declaration array key that identifies the children of a structure. */
#### const DEC_KEY_KIDS = 'children'

#### /** Declaration array key that identifies the line where the it ends in the PHP file. */
#### const DEC_KEY_DOC = 'doc_comment'

#### /** Declaration array key that identifies the line where the it ends in the PHP file. */
#### const DEC_KEY_LINE = 'line'

#### /** Declaration array key that identifies the statement. */
#### const DEC_KEY_STMT = 'statement'

#### /** Declaration array key that identifies the type. */
#### const DEC_KEY_TYPE = 'type'

#### /** Declaration class type. */
#### const TYPE_ABSTRACT = 'abstracts'

#### /** Declaration class type. */
#### const TYPE_CLASS = 'classes'

#### /** Declaration class type. */
#### const TYPE_CONST = 'constants'

#### /** Declaration class type. */
#### const TYPE_DOC_COMMENT = 'doc_comment'

#### /** string File documentation. */
#### const TYPE_FILE_COMMENT = 'file_comment'

#### /** Declaration interface type. */
#### const TYPE_INTERFACE = 'interfaces'

#### const TYPE_METHOD = 'methods'

#### /** Declaration namespace type. */
#### const TYPE_NAMESPACE = 'namespace'

#### /** Declaration class property type. */
#### const TYPE_PROPERTY = 'properties'

#### /** Declaration class property type. */
#### const TYPE_TRAITS = 'traits'

#### const TYPE_FUNCTIONS = 'functions'

#### public function __construct($pPath, $pFilter = NULL)



