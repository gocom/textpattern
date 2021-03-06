<?php

/**
 * Constants.
 */

/**
 * Tab character.
 */

define('t', "\t");

/**
 * Linefeed character.
 */

define('n', "\n");

/**
 * HTML break.
 *
 * @package HTML
 */

define('br', '<br />');

/**
 * HTML non-breaking space entity.
 *
 * @package HTML
 */

define('sp', '&#160;');

/**
 * HTML ampersand entity.
 *
 * @package HTML
 */

define('a', '&#38;');

if (!defined('TXP_DEBUG'))
{
	/**
	 * If set to TRUE, dumps debug log to the temp directory.
	 *
	 * @package Debug
	 */

	define('TXP_DEBUG', false);
}

/**
 * Comment spam status.
 *
 * @package Comment
 */

define('SPAM', -1);

/**
 * Comment moderate status.
 *
 * @package Comment
 */

define('MODERATE', 0);

/**
 * Comment spam status.
 *
 * @package Comment
 */

define('VISIBLE', 1);

/**
 * Comment reload status.
 *
 * @package Comment
 */

define('RELOAD', -99);

/**
 * Sets RPC server location.
 */

define('RPC_SERVER', 'http://rpc.textpattern.com');


if (!defined('HELP_URL'))
{
	/**
	 * Sets location where help documentation is fetched.
	 */

	define('HELP_URL', 'http://rpc.textpattern.com/help/');
}

/**
 * Do not format text.
 *
 * @var     string
 * @package TextFilter
 */

define('LEAVE_TEXT_UNTOUCHED', '0');

/**
 * Format text with Textile.
 *
 * @var     string
 * @package TextFilter
 */

define('USE_TEXTILE', '1');

/**
 * Replace line breaks with HTML &lt;br /&gt; tag.
 *
 * @var     string
 * @package TextFilter
 */

define('CONVERT_LINEBREAKS', '2');

/**
 * System is Windows if TRUE
 *
 * @package System
 */

define('IS_WIN', strpos(strtoupper(PHP_OS), 'WIN') === 0);

/**
 * Directory separator character
 *
 * @package File
 */

define('DS', defined('DIRECTORY_SEPARATOR') ? DIRECTORY_SEPARATOR : (IS_WIN ? '\\' : '/'));

/**
 * Magic quotes
 *
 * @package Network
 */

define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

/**
 * TRUE IF the system supports UTF-8 regex patterns.
 *
 * @package System
 */

define('REGEXP_UTF8', @preg_match('@\pL@u', 'q'));

/**
 * NULL datetime for use in an SQL statement.
 *
 * @package DB
 */

define('NULLDATETIME', '\'0000-00-00 00:00:00\'');

/**
 * Permlink URL mode.
 *
 * @package    URL
 * @deprecated ?
 */

define('PERMLINKURL', 0);

/**
 * Pagelink URL mode.
 *
 * @package    URL
 * @deprecated ?
 */

define('PAGELINKURL', 1);

if (!defined('EXTRA_MEMORY'))
{
	/**
	 * Allocated extra memory.
	 *
	 * Used when creating thumbnails for instance.
	 */

	define('EXTRA_MEMORY', '32M');
}

/**
 * PHP is run as CGI
 *
 * @package System
 */

define('IS_CGI', strpos(PHP_SAPI, 'cgi') === 0);

/**
 * PHP is run as FCGI
 *
 * @package System
 */

define('IS_FASTCGI', IS_CGI and empty($_SERVER['FCGI_ROLE']) and empty($_ENV['FCGI_ROLE']));

/**
 * PHP is run as Apache module
 *
 * @package System
 */

define('IS_APACHE', !IS_CGI and strpos(PHP_SAPI, 'apache') === 0);

/**
 * Preference is user-private.
 *
 * @package Pref
 */

define('PREF_PRIVATE', true);

/**
 * Preference is global.
 *
 * @package Pref
 */

define('PREF_GLOBAL', false);

/**
 * Preference type is basic.
 *
 * @package Pref
 */

define('PREF_BASIC', 0);

/**
 * Preference type is advanced.
 *
 * @package Pref
 */

define('PREF_ADVANCED', 1);

/**
 * Preference type is hidden.
 *
 * @package Pref
 */

define('PREF_HIDDEN', 2);

/**
 * Plugin flag: has options page.
 */

define('PLUGIN_HAS_PREFS', 0x0001);

/**
 * Plugin flag: has options lifecycle callbacks.
 */

define('PLUGIN_LIFECYCLE_NOTIFY', 0x0002);

/**
 * Reserved bits for use by Textpattern core.
 */

define('PLUGIN_RESERVED_FLAGS', 0x0fff);

if (!defined('PASSWORD_LENGTH'))
{
	/**
	 * Password default length, in characters.
	 *
	 * @package User
	 */

	define('PASSWORD_LENGTH', 10);
}

if (!defined('PASSWORD_COMPLEXITY'))
{
	/**
	 * Password iteration strenght count.
	 *
	 * @package User
	 */

	define('PASSWORD_COMPLEXITY', 8);
}

/**
 * Passwords are created portable if TRUE.
 *
 * @package User
 */

define('PASSWORD_PORTABILITY', true);

if (!defined('LOGIN_COOKIE_HTTP_ONLY'))
{
	/**
	 * Set login cookie just for HTTP.
	 *
	 * @package CSRF
	 */

	define('LOGIN_COOKIE_HTTP_ONLY', true);
}

if (!defined('X_FRAME_OPTIONS'))
{
	/**
	 * Prevent framing.
	 *
	 * @package CSRF
	 */

	define('X_FRAME_OPTIONS', 'SAMEORIGIN');
}

if (!defined('AJAX_TIMEOUT'))
{
	/**
	 * Ajax timeout.
	 *
	 * @package Ajax
	 */

	define('AJAX_TIMEOUT', max(30000, 1000 * @ini_get('max_execution_time')));
}

/**
 * Render on initial synchronous page load
 *
 * @since   4.5.0
 * @package Ajax
 */

define('PARTIAL_STATIC', 0);

/**
 * Render as HTML partial on every page load
 *
 * @since   4.5.0
 * @package Ajax
 */

define('PARTIAL_VOLATILE', 1);

/**
 * Render as an element's jQuery.val() on every page load
 *
 * @since   4.5.0
 * @package Ajax
 */

define('PARTIAL_VOLATILE_VALUE', 2);

/**
 * Draft article status ID.
 *
 * @package Article
 */

define('STATUS_DRAFT', 1);

/**
 * Hidden article status ID.
 *
 * @package Article
 */

define('STATUS_HIDDEN', 2);

/**
 * Pending article status ID.
 *
 * @package Article
 */

define('STATUS_PENDING', 3);

/**
 * Live article status ID.
 *
 * @package Article
 */

define('STATUS_LIVE', 4);

/**
 * Sticky article status ID.
 *
 * @package Article
 */

define('STATUS_STICKY', 5);

/**
 * Input size extra large
 *
 * @since   4.5.0
 * @package Form
 */

define('INPUT_XLARGE', 96);

/**
 * Input size large
 *
 * @since   4.5.0
 * @package Form
 */

define('INPUT_LARGE', 64);

/**
 * Input size regular
 *
 * @since   4.5.0
 * @package Form
 */

define('INPUT_REGULAR', 32);

/**
 * Input size medium
 *
 * @since   4.5.0
 * @package Form
 */

define('INPUT_MEDIUM', 16);

/**
 * Input size small
 *
 * @since   4.5.0
 * @package Form
 */

define('INPUT_SMALL', 8);

/**
 * Input size extra small
 *
 * @since   4.5.0
 * @package Form
 */

define('INPUT_XSMALL', 4);

/**
 * Input size tiny
 *
 * @since   4.5.0
 * @package Form
 */

define('INPUT_TINY', 2);

/**
 * Required PHP version
 *
 * @since   4.5.0
 * @package System
 */

define('REQUIRED_PHP_VERSION', '5.2');

/**
 * Colon.
 */

define('cs', ': ');

/**
 * Horizontal line, 24 characters wide.
 */

define('ln', str_repeat('-', 24).n);

/**
 * File integrity status good.
 *
 * @package Debug
 */

define('INTEGRITY_GOOD', 1);

/**
 * File integrity status modified.
 *
 * @package Debug
 */

define('INTEGRITY_MODIFIED', 2);

/**
 * File integrity not readable.
 *
 * @package Debug
 */

define('INTEGRITY_NOT_READABLE', 3);

/**
 * File integrity file missing.
 *
 * @package Debug
 */

define('INTEGRITY_MISSING', 4);

/**
 * File integrity not a file.
 *
 * @package Debug
 */

define('INTEGRITY_NOT_FILE', 5);

/**
 * Return integrity status.
 *
 * @package Debug
 */

define('INTEGRITY_STATUS', 0x1);

/**
 * Return integrity MD5 hashes.
 *
 * @package Debug
 */

define('INTEGRITY_MD5', 0x2);

/**
 * Return full paths.
 *
 * @package Debug
 */
 
 define('INTEGRITY_REALPATH', 0x4);