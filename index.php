<?php

// Make sure we display all errors that occur during initialization

error_reporting(E_ALL | E_STRICT);
@ini_set('display_errors', 1);

if (@ini_get('register_globals'))
{
	die('Register_globals needs to be turned off.');
}

define('txpinterface', 'public');

if (!defined('txpath'))
{
	define('txpath', dirname(__FILE__).'/textpattern');
}

// save server path to site root

if (!isset($here))
{
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