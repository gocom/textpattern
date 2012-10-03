<?php

/*
	This is Textpattern
	Copyright 2005 by Dean Allen - all rights reserved.

	Use of this software denotes acceptance of the Textpattern license agreement

*/

/**
 * Adds a row to the visitor logs.
 *
 * Doesn't add anything to the log when called from
 * 404 error page or when $nolog global is set TRUE.
 *
 * @param int $status HTTP status code
 */

	function log_hit($status)
	{
		global $nolog, $logging;
		callback_event('log_hit');
		if (!isset($nolog) && $status != 404)
		{
			if ($logging == 'refer')
			{
				logit('refer', $status);
			}
			elseif ($logging == 'all')
			{
				logit('', $status);
			}
		}
	}

/**
 * Writes a record to the visitor log from using visitor's information
 *
 * @param string $r      Type of record, e.g. refer
 * @param int    $status HTTP status code
 */

	function logit($r = '', $status = 200)
	{
		global $siteurl, $prefs, $pretext;
		$mydomain = str_replace('www.','',preg_quote($siteurl,"/"));
		$out['uri'] = @$pretext['request_uri'];
		$out['ref'] = clean_url(str_replace("http://","",serverSet('HTTP_REFERER')));
		$ip = remote_addr();
		$host = $ip;

		if (!empty($prefs['use_dns']))
		{
			// A crude rDNS cache
			if ($h = safe_field('host', 'txp_log', "ip='".doSlash($ip)."' limit 1"))
			{
				$host = $h;
			}
			else {
				// Double-check the rDNS
				$host = @gethostbyaddr($ip);
				if ($host != $ip and @gethostbyname($host) != $ip)
				{
					$host = $ip;
				}
			}
		}

		$out['ip'] = $ip;
		$out['host'] = $host;
		$out['status'] = $status;
		$out['method'] = serverSet('REQUEST_METHOD');

		if (preg_match("/^[^\.]*\.?$mydomain/i", $out['ref']))
		{
			$out['ref'] = "";
		}

		if ($r == 'refer')
		{
			if (trim($out['ref']) != "")
			{
				insert_logit($out);
			}
		}
		else
		{
			insert_logit($out);
		}
	}

/**
 * Inserts a log record to the database.
 *
 * @param array $in Input array consisting 'uri', 'ip', 'host', 'ref', 'status', 'method'
 */

	function insert_logit($in)
	{
		extract(doSlash($in));
		safe_insert(
			"txp_log",
			"`time` = now(), page='$uri', ip='$ip', host='$host', refer='$ref', status='$status', method='$method'"
		);
	}