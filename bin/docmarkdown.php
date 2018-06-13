<?php namespace QL\DocMarkdown;

require __DIR__
    . DIRECTORY_SEPARATOR . '..'
    . DIRECTORY_SEPARATOR . 'vendor'
    . DIRECTORY_SEPARATOR . 'autoload.php';

const FLAG_CFG = 'c';
const FLAG_DEST = 'd';
const FLAG_HELP = 'h';
const FLAG_SRC = 's';

echo \PHP_EOL . 'FYI current working directory is: ' . getcwd() . \PHP_EOL . \PHP_EOL;

// Flag short help summaries.
$help = [
    FLAG_CFG => 'set a configuration file to load all values from.',
    FLAG_HELP => 'to show this flag short summary.',
    FLAG_DEST => 'set the directory to output PHPDoc commentts converted to documentation.',
    FLAG_SRC => 'set the source directory to read PHP files with PHPDoc comments.'
];

// Setup getopt flags.
$shortOpts = FLAG_CFG . ':' // optional config file, will override all values.
    . FLAG_SRC . ':' // required source path
    . FLAG_DEST . ':' // required destination path
    . FLAG_HELP; // optional help text.

// Get command line flags.
$options = getopt($shortOpts);

// Output help.
if (array_key_exists(FLAG_HELP, $options)) {
    foreach ($help as $flag => $message) {
        echo '-' . $flag . ' ' . $message . \PHP_EOL;
    }
    exit(0);
}

$config = null;
// load values from configuration file.
if (array_key_exists(FLAG_CFG, $options)) {
    $configFile = $options[FLAG_CFG];
    if (file_exists($configFile)) {
        $config = new Config($configFile);
    } else {
        echo "Could not load configuration file {$configFile}" . \PHP_EOL;
        exit(1);
    }
} else { // Look for a default config file.
    $configFile = getcwd() . DIRECTORY_SEPARATOR . 'doc-markdown.json';
    if (file_exists($configFile)) {
        $config = new Config($configFile);
    }
}

// Check that the source directory was input.
if ($config === null && !array_key_exists(FLAG_SRC, $options)) {
    echo 'You must specify a source directory which should contain source files with PHP Doc-blocks.' . PHP_EOL;
    exit(1);
}

// Check that the destination directory was input.
if ($config === null && !array_key_exists(FLAG_DEST, $options)) {
    echo 'You must specify a destination directory where the API documentation files will be saved (in Markdown'
        . 'format).' . PHP_EOL;
    exit(1);
}

$source = $config instanceof Config ? $config->get('source') : $options[FLAG_SRC];
if (!file_exists($source)) {
    echo 'The source directory ' . $source . ' does not exist or cannot be accessed by this script.' . PHP_EOL;
    exit(1);
}

$dest = $config instanceof Config ? $config->get('documentation') : $options[FLAG_DEST];
if (!file_exists($dest)) {
    echo 'The destination directory ' . $dest . ' does not exist or cannot be accessed by this script.' . PHP_EOL;
    exit(1);
}

$parser = new DocBlockParser();
$finder = new PhpParser($source, $parser);

$templatesDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates';
$templateFiles = [
    'file',
    'abstract-class',
    'interface',
    'trait',
    'class',
];
$templates = [];

// Find templates.
foreach ($templateFiles as $fileName) {
    $templateFile = $templatesDir . DIRECTORY_SEPARATOR . $fileName;
    if (file_exists($templateFile . '.php')) {
        $templates[$fileName] = (new PhpTemplateEngine())->setTemplate($templateFile);
    }
}

$documentationGenerator = new Generator($finder, $config);
$documentationGenerator->buildIndex(
    (new PhpTemplateEngine())->setTemplate($templatesDir . DIRECTORY_SEPARATOR . 'index'),
    $dest
);

echo 'Generating API Documentation...' . \PHP_EOL;

$stats = $documentationGenerator->output($templates, $dest, function ($srcFile, $docFile) {
    // Let the use know something when wrong.
    if (!file_exists($docFile)) {
        echo 'Unable to save ' . $docFile;
    } else {
        echo PHP_EOL . 'Generated ' . $docFile . ' from ' . realpath($srcFile);
    }
});

?>