<?php

/**
 * Ouputs css files.
 *
 * @ignore
 */

if (@ini_get('register_globals'))
{
	die('Register_globals needs to be turned off.');
}

header('Content-type: text/css');

if (!defined('txpath'))
{
	define('txpath', dirname(__FILE__).'/textpattern');
}

if (!isset($txpcfg['table_prefix']))
{
	ob_start(NULL, 2048);
	include txpath.'/config.php';
	ob_end_clean();
}

$nolog = true;
define('txpinterface', 'css');
include txpath.'/publish.php';
$s = gps('s');
$n = gps('n');
output_css($s, $n);