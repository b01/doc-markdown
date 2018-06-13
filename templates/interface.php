<?php include 'header.php'; ?>

<?php foreach ($data[\QL\DocMarkdown\PhpParser::TYPE_INTERFACE] as $class): ?>
## <?= $class['statement']; ?>

<?= displayConstants($class); ?>

<?= displayMethods($class); ?>

<?php endforeach; ?>

