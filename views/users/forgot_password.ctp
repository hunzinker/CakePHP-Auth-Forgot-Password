<h1>Enter Your Username</h1>
<?php echo $form->create(null, array('action' => 'forgot_password', 'id' => 'web-form')); ?>
	<?php echo $form->input('User.username', array('label' => 'Username', 'between'=>'<br />', 'type'=>'text')); ?>
	<?php echo $form->submit('Send Password Reset Instructions', array('class' => 'submit', 'id' => 'submit')); ?>
<?php echo $form->end(); ?>