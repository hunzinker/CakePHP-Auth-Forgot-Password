<?php

/**
 * User Model
 */
class User extends AppModel {
    var $name = 'User';
    var $belongsTo = array('Group');
    var $actsAs = array('Acl' => 'requester');

    var $validate = array(
        'first_name' => array(
        	'rule' => array('minlength', 3),
        	'required' => true,
        	'message' => "Please enter your First Name."
        	),
        'middle_name' => array(
        	'rule' => array('minlength', 1),
            'allowEmpty' => true,
            'message' => "Please enter your Middle Name."
            ),
        'last_name' => array(
        	'rule' => array('minlength', 3),
        	'required' => true,
        	'message' => "Please enter your Last Name."
        	),
        'username' => array(
        	'rule' => array('alphanumeric'),
        	'required' => true,
        	'message' => 'Please make sure you use only letters and numbers.'
        	),
        'email' => array(
        	'rule' => array('email'),
        	'required' => true,
        	'message' => 'Please make sure you have entered a valid email address.'
        	),
        'group_id' => array(
        	'rule' => array('notEmpty'),
        	'required' => true,
        	'message' => 'Please select a User Group.'
        	),
        'new_passwd' => array(
        	'equalTo' => array(
        		'rule' => array('equalTo', 'confirm_passwd'),
        		'message' => 'Password values do not match.'
        		),
        	'between' => array(
        		'rule' => array('between', 7, 20),
        		'allowEmpty' => true,
        		'message' => 'Your password must be between 7 and 20 characters long.'
        	)
        )
    );

	/**
	 * Checks to see if the username is already taken.
	 * @return boolean
	 */
	function beforeValidate() {
	    if (!$this->id) {
	        $num = $this->find('count', array('conditions' => array('User.username' => $this->data['User']['username'])));
	        if ($num > 0) {
	            $this->invalidate('username_unique');
	            return false;
	        }
	    }
	    return true;
	}

}
?>
