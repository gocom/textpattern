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

error_reporting(E_ALL | E_STRICT);
@ini_set('display_errors', 1);

if (@ini_get('register_globals'))
{
	die('Register_globals needs to be turned off.');
}

/**
 * Indicates the interface being accessed.
 *
 * This is either 'public', 'admin', 'setup', 'css', 'xmlrpc'
 */

define('txpinterface', 'public');

if (!defined('txpath'))
{
	/**
	 * @ignore
	 */

	define('txpath', dirname(__FILE__).'/textpattern');
}

if (!isset($here))
{
	/**
	 * Server path to the site root.
	 *
	 * @global  string $here
	 * @package File
	 */

	$here = dirname(__FILE__);
}

// pull in config unless configuration data has already been provided (multi-headed use).

if (!isset($txpcfg['table_prefix']))
{
	// Use buffering to ensure bogus whitespace in config.php is ignored
	ob_start(NULL, 2048);
	include txpath.'/config.php';
	ob_end_clean();
}

include txpath.'/lib/constants.php';
include txpath.'/lib/txplib_misc.php';

if (!isset($txpcfg['table_prefix']))
{
	txp_status_header('503 Service Unavailable');
	exit('config.php is missing or corrupt.  To install Textpattern, visit <a href="./textpattern/setup/">textpattern/setup/</a>');
}

// custom caches et cetera?
if (isset($txpcfg['pre_publish_script']))
{
	require $txpcfg['pre_publish_script'];
}

include txpath.'/publish.php';
textpattern();