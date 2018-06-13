<?php
require_once 'helpers.php';
include 'header.php';
?>

<?php foreach ($data[\QL\DocMarkdown\PhpParser::TYPE_CLASS] as $class): ?>
## <?= $class['statement']; ?>

<?= displayConstants($class); ?>

<?= displayMethods($class); ?>

<?php endforeach; ?>

