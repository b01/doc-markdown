# <?= $data['class'] . '::' . $data['method']; ?>

<?= $data['class'] . '::' . $data['method'] . ' -  ' . $data['doc_comment']['summary']; ?>

```php
$data['statement']; ?>
```

## Description

<?= $data['doc_comment']['summary']; ?>
<?php if (array_key_exists('return', $data['doc_comment'])): ?>
## [Return Values](#return)

<?= $data['doc_comment']['return']; ?>
<?php endif; ?>

<?php if (array_key_exists('throws', $data['doc_comment'])): ?>
## [Errors/Exceptions](#throws) ¶

<?= $data['doc_comment']['throws']; ?>
<?php endif; ?>

<?php if (array_key_exists('example', $data['doc_comment'])): ?>
## [Example](#example) ¶

<?= $data['doc_comment']['example']; ?>
<?php endif; ?>
