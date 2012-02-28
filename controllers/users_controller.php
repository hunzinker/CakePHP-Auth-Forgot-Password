<?php

/**
 * User Controller
 */
class UsersController extends AppController {
    var $name = 'Users';
    var $helpers = array('Html', 'Form', 'Time');
    var $uses = array('User');
    var $allowCookie = true;
    var $cookieTerm = '0';

    /**
     * Parent beforeFilter()
     */
    function beforeFilter() {
        parent::beforeFilter();
    }

    /**
     * Custom login function to allow for cookies.
     * @return
     */
    function login() {
        if ($this->Auth->user()) {
            $user = $this->Auth->user();
            if (!empty($this->data)) {
                if ($this->allowCookie) {
                    $cookie = array();
                    $cookie['username'] = $this->data['User']['username'];
                    $cookie['password'] = $this->data['User']['password'];
                    $this->Cookie->write('User', $cookie, true, $this->cookieTerm);
                }
            }
            $this->redirect($this->Auth->redirect('/users/view/' . $user['User']['id']));
        } else {
            $this->Cookie->del('User');
        }
    }

    /**
     * Allow a user to request a password reset.
     * @return
     */
    function forgot_password() {
        if (!empty($this->data)) {
            $user = $this->User->findByUsername($this->data['User']['username']);
            if (empty($user)) {
                $this->Session->setflash('Sorry, the username entered was not found.');
                $this->redirect('/users/forgot_password');
            } else {
                $user = $this->__generatePasswordToken($user);
                if ($this->User->save($user) && $this->__sendForgotPasswordEmail($user['User']['id'])) {
                    $this->Session->setflash('Password reset instructions have been sent to your email address.
						You have 24 hours to complete the request.');
                    $this->redirect('/users/login');
                }
            }
        }
    }

    /**
     * Allow user to reset password if $token is valid.
     * @return
     */
    function reset_password_token($reset_password_token = null) {
        if (empty($this->data)) {
            $this->data = $this->User->findByResetPasswordToken($reset_password_token);
            if (!empty($this->data['User']['reset_password_token']) && !empty($this->data['User']['token_created_at']) &&
            $this->__validToken($this->data['User']['token_created_at'])) {
                $this->data['User']['id'] = null;
                $_SESSION['token'] = $reset_password_token;
            } else {
                $this->Session->setflash('The password reset request has either expired or is invalid.');
                $this->redirect('/users/login');
            }
        } else {
            if ($this->data['User']['reset_password_token'] != $_SESSION['token']) {
                $this->Session->setflash('The password reset request has either expired or is invalid.');
                $this->redirect('/users/login');
            }

            $user = $this->User->findByResetPasswordToken($this->data['User']['reset_password_token']);
            $this->User->id = $user['User']['id'];

            if ($this->User->save($this->data, array('validate' => 'only'))) {
                $this->data['User']['reset_password_token'] = $this->data['User']['token_created_at'] = null;
                if ($this->User->save($this->data) && $this->__sendPasswordChangedEmail($user['User']['id'])) {
                    unset($_SESSION['token']);
                    $this->Session->setflash('Your password was changed successfully. Please login to continue.');
                    $this->redirect('/users/login');
                }
            }
        }
    }

    /**
     * Generate a unique hash / token.
     * @param Object User
     * @return Object User
     */
    function __generatePasswordToken($user) {
        if (empty($user)) {
            return null;
        }

        // Generate a random string 100 chars in length.
        $token = "";
        for ($i = 0; $i < 100; $i++) {
            $d = rand(1, 100000) % 2;
            $d ? $token .= chr(rand(33,79)) : $token .= chr(rand(80,126));
        }

        (rand(1, 100000) % 2) ? $token = strrev($token) : $token = $token;

        // Generate hash of random string
        $hash = Security::hash($token, 'sha256', true);;
        for ($i = 0; $i < 20; $i++) {
            $hash = Security::hash($hash, 'sha256', true);
        }

        $user['User']['reset_password_token'] = $hash;
        $user['User']['token_created_at']     = date('Y-m-d H:i:s');

        return $user;
    }

    /**
     * Validate token created at time.
     * @param String $token_created_at
     * @return Boolean
     */
    function __validToken($token_created_at) {
        $expired = strtotime($token_created_at) + 86400;
        $time = strtotime("now");
        if ($time < $expired) {
            return true;
        }
        return false;
    }

    /**
     * Sends password reset email to user's email address.
     * @param $id
     * @return
     */
    function __sendForgotPasswordEmail($id = null) {
        if (!empty($id)) {
            $this->User->id = $id;
            $User = $this->User->read();

            $this->Email->to 		= $User['User']['email'];
            $this->Email->subject 	= 'Password Reset Request - DO NOT REPLY';
            $this->Email->replyTo 	= 'do-not-reply@example.com';
            $this->Email->from 		= 'Do Not Reply <do-not-reply@example.com>';
            $this->Email->template 	= 'reset_password_request';
            $this->Email->sendAs 	= 'both';
            $this->set('User', $User);
            $this->Email->send();

            return true;
        }
        return false;
    }

    /**
     * Notifies user their password has changed.
     * @param $id
     * @return
     */
    function __sendPasswordChangedEmail($id = null) {
        if (!empty($id)) {
            $this->User->id = $id;
            $User = $this->User->read();

            $this->Email->to 		= $User['User']['email'];
            $this->Email->subject 	= 'Password Changed - DO NOT REPLY';
            $this->Email->replyTo 	= 'do-not-reply@example.com';
            $this->Email->from 		= 'Do Not Reply <do-not-reply@example.com>';
            $this->Email->template 	= 'password_reset_success';
            $this->Email->sendAs 	= 'both';
            $this->set('User', $User);
            $this->Email->send();

            return true;
        }
        return false;
    }

}
?>
