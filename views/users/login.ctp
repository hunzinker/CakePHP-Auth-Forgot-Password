<h1>Member Login</h1>
<?php echo $form->create(null, array('action' => 'login', 'id' => 'web-form')); ?>
<fieldset>
	<legend>Login</legend>
	<?php echo $form->input('User.username', array('label' => 'Username', 'between'=>'<br />', 'type'=>'text')); ?>
	<?php echo $form->input('User.password', array('label' => 'Password', 'between'=>'<br />', 'type'=>'password')); ?>
	<?php echo $form->submit('Submit', array('class' => 'submit', 'id' => 'submit')); ?>
</fieldset>
<?php echo $form->end(); ?>
<p><?php echo $html->link('Forgot your password?', '/users/forgot_password'); ?></p>