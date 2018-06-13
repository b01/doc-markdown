# <?= str_replace('.php', '', basename($data['file'])); ?>

<?php
if (array_key_exists(\QL\DocMarkdown\PhpParser::TYPE_INTERFACE, $data)) {
    include 'interface.php';
} ?>
<?php
if (array_key_exists(\QL\DocMarkdown\PhpParser::TYPE_ABSTRACT, $data)) {
    include 'abstract-class.php';
} ?>
<?php
if (array_key_exists(\QL\DocMarkdown\PhpParser::TYPE_TRAITS, $data)) {
    include 'trait.php';
} ?>
<?php
if (array_key_exists(\QL\DocMarkdown\PhpParser::TYPE_CLASS, $data)) {
    include 'class.php';
} ?>
