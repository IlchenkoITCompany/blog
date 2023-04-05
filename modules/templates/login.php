<?php require_once \Helpers\getFragmentPath('__header') ?>
<form method="post">
    <p>Введите имя пользователя:</p>
    <input type="text" name="name" value="<?php echo $form['name'] ?>">
    <?php 
        \Helpers\showErrors('name', $form);
    ?>
    <p>Введите пароль:</p>
    <input type="password" name="password">
    <?php 
        \Helpers\showErrors('password', $form); 
    ?>
    <input type="submit" value="log in">
</form>
<?php require_once \Helpers\getFragmentPath('__footer');
\Form\EverythingToTest::getRenderedForm($form); ?>