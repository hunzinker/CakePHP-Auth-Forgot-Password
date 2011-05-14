<h1>Change Your Password</h1>
<?php echo $form->create(null, array('action' => 'reset_password_token', 'id' => 'web-form')); ?>
    <?php echo $form->hidden('User.reset_password_token'); ?>
	<?php echo $form->input('User.new_passwd',  array('type' => 'password', 'label' => 'Password', 'between' => '<br />', 'type' => 'password') ); ?>
	<?php echo $form->input('User.confirm_passwd',  array('type' => 'password', 'label' => 'Confirm Password', 'between' => '<br />', 'type' => 'password') ); ?>
	<?php echo $form->submit('Change Password', array('class' => 'submit', 'id' => 'submit')); ?>
<?php echo $form->end(); ?>