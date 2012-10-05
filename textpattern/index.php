<?php

/**
 * This is Textpattern
 * 
 * Copyright 2005 by Dean Allen
 * www.textpattern.com
 * All rights reserved
 *
 * Use of this software indicates acceptance of the Textpattern license agreement
 */

if (@ini_get('register_globals'))
{
	die('Register_globals needs to be turned off.');
}

if (!defined('txpath'))
{
	/**
	 * @ignore
	 */

	define("txpath", dirname(__FILE__));
}

/**
 * @ignore
 */

define('txpinterface', 'admin');

/**
 * Textpattern version number.
 *
 * @global string $thisversion
 * @see    txp_version
 */

$thisversion = '4.5.1';

/**
 * Running SVN version.
 *
 * @global bool $txp_using_svn
 */

$txp_using_svn = true; // set false for releases

ob_start(NULL, 2048);
if (!isset($txpcfg['table_prefix']) && !@include './config.php')
{
	ob_end_clean();
	header('HTTP/1.1 503 Service Unavailable');
	exit('config.php is missing or corrupt.  To install Textpattern, visit <a href="./setup/">setup</a>.');
}
else
{
	ob_end_clean();
}

header('Content-type: text/html; charset=utf-8');

error_reporting(E_ALL | E_STRICT);
@ini_set('display_errors', 1);

include_once txpath.'/lib/constants.php';
include txpath.'/lib/txplib_misc.php';
include txpath.'/lib/txplib_db.php';
include txpath.'/lib/txplib_forms.php';
include txpath.'/lib/txplib_html.php';
include txpath.'/lib/txplib_theme.php';
include txpath.'/lib/txplib_validator.php';
include txpath.'/lib/txplib_textfilter.php';
include txpath.'/lib/admin_config.php';

set_error_handler('adminErrorHandler', error_reporting());
$microstart = getmicrotime();

if ($connected && safe_query("describe `".PFX."textpattern`"))
{
	/**
	 * Database structure version.
	 *
	 * @global  string $dbversion
	 * @package Pref
	 * @see     txp_version
	 */

	$dbversion = safe_field('val', 'txp_prefs', "name = 'version'");
	// global site prefs
	$prefs = get_prefs();
	extract($prefs);

	if (empty($siteurl))
	{
		$httphost = preg_replace('/[^-_a-zA-Z0-9.:]/', '', $_SERVER['HTTP_HOST']);
		$prefs['siteurl'] = $siteurl = $httphost . rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');
	}

	if (empty($path_to_site))
	{
		updateSitePath(dirname(dirname(__FILE__)));
	}

	/**
	 * @ignore
	 */

	define('LANG', $language);
	
	/**
	 * Textpattern version number.
	 */

	define('txp_version', $thisversion);

	if (!defined('PROTOCOL'))
	{
		switch (serverSet('HTTPS'))
		{
			case '':
			case 'off': // ISAPI with IIS
				/**
				 * @ignore
				 */
				define('PROTOCOL', 'http://');
				break;
			default:
				/**
				 * @ignore
				 */
				define('PROTOCOL', 'https://');
				break;
		}
	}

	/**
	 * @ignore
	 */

	define('hu', PROTOCOL.$siteurl.'/');
	/**
	 * relative url global
	 * @ignore
	 */
	define('rhu', preg_replace('|^https?://[^/]+|', '', hu));
	if (!defined('ihu'))
	{
		/**
		 * http address of the site serving images
		 * @ignore
		 */
		define('ihu', hu);
	}

	if (!empty($locale))
	{
		setlocale(LC_ALL, $locale);
	}

	$textarray = load_lang(LANG);

	/**
	 * Instance of admin-side theme.
	 *
	 * @global theme $theme
	 */

	$theme = theme::init();

	include txpath.'/include/txp_auth.php';
	doAuth();

	// once more for global plus private prefs
	$prefs = get_prefs();
	extract($prefs);

	$event = (gps('event') ? trim(gps('event')) : (!empty($default_event) && has_privs($default_event) ? $default_event : 'article'));
	$step = trim(gps('step'));
	$app_mode = trim(gps('app_mode'));

	if (!$dbversion or ($dbversion != $thisversion) or $txp_using_svn)
	{
		/**
		 * If TRUE, updating.
		 */

		define('TXP_UPDATE', 1);
		include txpath.'/update/_update.php';
	}

	janitor();

	// article or form preview

	if (isset($_POST['form_preview']) || isset($_GET['txpreview']))
	{
		include txpath.'/publish.php';
		textpattern();
		exit;
	}

	if (!empty($admin_side_plugins) and gps('event') != 'plugin')
	{
		load_plugins(true);
	}

	// plugins may have altered privilege settings

	if (!defined('TXP_UPDATE_DONE') && !gps('event') && !empty($default_event) && has_privs($default_event))
	{
		 $event = $default_event;
	}

	// init private theme

	$theme = theme::init();
	include txpath.'/lib/txplib_head.php';

	require_privs($event);
	callback_event($event, $step, true);
	$inc = txpath . '/include/txp_'.$event.'.php';

	if (is_readable($inc))
	{
		include($inc);
	}

	callback_event($event, $step, false);
	end_page();

	$microdiff = substr(getmicrotime() - $microstart,0,6);
	$memory_peak = is_callable('memory_get_peak_usage') ? ceil(memory_get_peak_usage(true)/1024) : '-';

	if ($app_mode != 'async')
	{
		echo n.comment(gTxt('runtime').': '.$microdiff);
		echo n.comment(sprintf('Memory: %sKb', $memory_peak));
	}
	else
	{
		header('X-Textpattern-Runtime: '.$microdiff);
		header('X-Textpattern-Memory: '.$memory_peak);
	}
}
else
{
	txp_die('DB-Connect was successful, but the textpattern-table was not found.', '503 Service Unavailable');
}