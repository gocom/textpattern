<?php

/**
 * Password handling functions.
 * @package User
 */

/**
 * Emails a new user with login details.
 *
 * This function can be only executed when the current
 * user trying to send the email is authenticated with an account
 * that has privileges to 'admin.edit' permission.
 *
 * @param  string $RealName The real name
 * @param  string $name     The login name
 * @param  string $email    The email address
 * @param  string $password The password
 * @return bool   FALSE on error.
 * @see    send_new_password()
 */

	function send_password($RealName, $name, $email, $password)
	{
		global $sitename;

		require_privs('admin.edit');

		$message = gTxt('greeting').' '.$RealName.','.

			n.n.gTxt('you_have_been_registered').' '.$sitename.

			n.n.gTxt('your_login_is').': '.$name.
			n.gTxt('your_password_is').': '.$password.

			n.n.gTxt('log_in_at').': '.hu.'textpattern/index.php';

		return txpMail($email, "[$sitename] ".gTxt('your_login_info'), $message);
	}

/**
 * Sends a new password to an existing user.
 *
 * If the $user is set FALSE, the current user is expected.
 *
 * @param  string $password The new password
 * @param  string $email    The email address
 * @param  string $name     The login name
 * @return bool   FALSE on error.
 * @see    send_password()
 */

	function send_new_password($password, $email, $name)
	{
		global $txp_user, $sitename;

		if (empty($name))
		{
			$name = $txp_user;
		}

		$message = gTxt('greeting').' '.$name.','.

			n.n.gTxt('your_password_is').': '.$password.

			n.n.gTxt('log_in_at').': '.hu.'textpattern/index.php';

		return txpMail($email, "[$sitename] ".gTxt('your_new_password'), $message);
	}

/**
 * Sends a password reset link to a user's email address.
 *
 * This function will return a success message even when the specified
 * user doesn't exist. Though an error message could be thrown when
 * user isn't found, this is done due to security. This prevents the function
 * from leaking existing account names.
 *
 * @param  string $name     The login name
 * @return string A localized message string
 * @see    send_new_password()
 */

	function send_reset_confirmation_request($name)
	{
		global $sitename;

		$rs = safe_row('email, nonce', 'txp_users', "name = '".doSlash($name)."'");

		if ($rs)
		{
			extract($rs);

			$confirm = bin2hex(pack('H*', substr(md5($nonce), 0, 10)).$name);

			$message = gTxt('greeting').' '.$name.','.

				n.n.gTxt('password_reset_confirmation').': '.
				n.hu.'textpattern/index.php?confirm='.$confirm;

			if (txpMail($email, "[$sitename] ".gTxt('password_reset_confirmation_request'), $message))
			{
				return gTxt('password_reset_confirmation_request_sent');
			}
			else
			{
				return gTxt('could_not_mail');
			}
		}

		else
		{
			return gTxt('password_reset_confirmation_request_sent');
		}
	}

/**
 * Generates a password.
 *
 * @param  string $length The length of the password
 * @return string A new, random, plain-text password
 */

	function generate_password($length = 10)
	{
		$pass = '';
		$chars = '23456789abcdefghijkmnopqrstuvwxyz';
		$length = min(strlen($chars), $length);
		$i = 0;

		while ($i < $length)
		{
			$char = substr($chars, mt_rand(0, strlen($chars)-1), 1);

			if (!strstr($pass, $char))
			{
				$pass .= $char;
				$i++;
			}
		}

		return $pass;
	}

/**
 * Resets a user's password and emails it.
 *
 * The old replaced with a new random-generated password.
 *
 * @param  string $name The login name
 * @return string A localized message string
 * @see    PASSWORD_LENGTH
 * @see    generate_password()
 */

	function reset_author_pass($name)
	{
		$email = safe_field('email', 'txp_users', "name = '".doSlash($name)."'");

		$new_pass = generate_password(PASSWORD_LENGTH);
		$hash = doSlash(txp_hash_password($new_pass));

		$rs = safe_update('txp_users', "pass = '$hash'", "name = '".doSlash($name)."'");

		if ($rs)
		{
			if (send_new_password($new_pass, $email, $name))
			{
				return(gTxt('password_sent_to').' '.$email);
			}

			else
			{
				return(gTxt('could_not_mail').' '.$email);
			}
		}

		else
		{
			return(gTxt('could_not_update_author').' '.txpspecialchars($name));
		}
	}