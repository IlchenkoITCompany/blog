<?php require_once \Helpers\getFragmentPath('__header') ?>
<?php \Form\EverythingToTest::getRenderedForm($formData, 'insert data', 'insert-file-form', ['user' => $users]); 
?>
<?php require_once \Helpers\getFragmentPath('__footer') ?>