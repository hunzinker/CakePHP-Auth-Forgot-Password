# CakePHP Auth Forgot Password

Simple CakePHP Auth based forgot password setup. This code has been tested in both CakePHP 1.2.X and 1.3.X using
CakePHP's Auth component and ACL behavior.

## Installation

Follow the example MVC setup included and modify as necesary. NOTE: this code is to be used as an example only.

### Details

Request to `forgot_password()` prompts user to enter username. If the username is found, 
`__generatePasswordToken()` sets `reset_password_token = [hash of a random string]` and 
sets `token_created_at = [current timestamp]`. An email is sent to the user containing

`https://example.com/users/reset_password_token/[random_hashed_string]`

to complete the request within 24 hours. `reset_password_token($token)` validates the token, user, etc., 
and prompts the user to reset their password. If successful, the password is reset, 
the token is destroyed and the user is notified via email.

### Cron Job

I created an hourly cron job to clean up invalid tokens. See vendors > shells > password_reset_token.php.

```bash
  crontab -e
  0 * * * * /path_to_cakeshell/cakeshell password_reset_token -cli /usr/bin -console /path_to_cake_console/cake/console -app /path_to_app/public_html/app >> /path_to_log_file/password_reset_token.log
```

### Sample SQL

```sql
  CREATE TABLE `users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL DEFAULT '',
    `password` varchar(255) NOT NULL,
    `first_name` varchar(255) NOT NULL,
    `middle_name` varchar(255) DEFAULT NULL,
    `last_name` varchar(255) NOT NULL,
    `email` varchar(100) NOT NULL DEFAULT '',
    `group_id` int(11) unsigned NOT NULL DEFAULT '0',
    `active` tinyint(1) NOT NULL DEFAULT '0',
    `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `reset_password_token` varchar(255) DEFAULT NULL,
    `token_created_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`),
    UNIQUE KEY `reset_password_token` (`reset_password_token`),
    KEY `group_id` (`group_id`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
```

### TODO

* Add forgot username.
* Add auto-login after password is reset.

### Maintained By

* Ken Seal (github.com/hunzinker)

### License

MIT
