<?php

/**
 * Display constants
 *
 * @param array & $class
 * @return string
 */
function displayConstants(array & $data)
{
    $return = '';

    if (array_key_exists('constants', $data)) {
        $return = \PHP_EOL . '### Constants' . \PHP_EOL;

        foreach ($data['constants'] as $constant) {
            $return .= '```php' . \PHP_EOL . '// ' . renderDocBlock($constant);
            $return .= $constant['statement'] . \PHP_EOL . '```' . \PHP_EOL;
        }
    }

    return $return;
}

/**
 * @param array $class
 * @return string
 */
function displayMethods(& $class)
{
    $return = '';

    if (array_key_exists('methods', $class)) {
        $return = '### Methods'. \PHP_EOL;

        foreach ($class['methods'] as $method) {
            $return .= \PHP_EOL . '```php' . \PHP_EOL . $method['statement'] . \PHP_EOL . '```' . \PHP_EOL;
            $return .= renderDocBlock($method);
        }
    }

    return $return;
}

/**
 * @param array $file
 * @return string
 */
function formatNamespace($file)
{
    $return = '';
    if (array_key_exists('namespace', $file)) {
        $return = str_replace(
            'namespace ',
            '**Namespace:** \\',
            trim($file['namespace'])
        );
    }

    return $return;
}

/**
 * @param $structure
 * @return string
 */
function renderDocBlock($structure)
{
    if (!is_array($structure) || !array_key_exists('doc_comment', $structure)) {
        return '';
    }

    $docComment = $structure['doc_comment'];
    $return = '';

    if (is_string($docComment)) {
        $return .= $docComment;
    } else {
        if (array_key_exists('summary', $docComment)) {
            $return .= $docComment['summary'] . \PHP_EOL;
        }

        if (array_key_exists('params', $docComment)) {
            foreach ($docComment['params'] as $param) {
                $return .= $param['type'] . $param['name'] . \PHP_EOL;
            }
        }
    }

    return $return;
}
?>