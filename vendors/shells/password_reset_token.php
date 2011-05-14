<?php
/**
 * PasswordResetToken Cron Job
 */
class PasswordResetTokenShell extends Shell {
	var $uses = array('User');
	function main() {
		$conditions = array(
			'conditions' => array('not' => array('User.reset_password_token ' => null))
		);
		$tokens = $this->User->find('all', $conditions);
		foreach ($tokens as $t) {
			$expired = strtotime($t['User']['token_created_at']) + 86400;
			$time = strtotime('now');
			if ($time > $expired) {
				$sql = "UPDATE users SET reset_password_token = NULL, token_created_at = NULL WHERE id = " . $t['User']['id'] . "";
				$this->User->query($sql);
			}
		}
	}
}

?>