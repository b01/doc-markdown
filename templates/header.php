# <?= str_replace('.php', '', basename($data['file'])); ?>


<?php if (array_key_exists('codebaseLink', $data)) { ?>
**File:** [<?= $data['codebase_path']; ?>](<?= $data['codebaseLink']; ?>)
<?php } else { ?>
**File:** <?= $data['file']; ?>
<?php } ?>

<?= formatNamespace($data); ?>

<?php if (array_key_exists('file_comment', $data)
    && is_array($data['file_comment'])
    && array_key_exists('summary', $data['file_comment'])) { ?>
## Description

<?= $data['file_comment']['summary']; ?>

<?php } ?>
