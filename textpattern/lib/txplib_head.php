<?php

/**
 * Used to generated admin-side headers.
 *
 * @package HTML
 */

/**
 * Creates and outputs an admin-side header.
 *
 * The output will HTML head section, the main navigation. The results will
 * be echoed instead of returned.
 *
 * This function offers a way to invoke modal activity messages
 * and set the page's title.
 *
 * This function will automatically become silent on asynchronous
 * script requests and responses that do need want HTML headers.
 *
 * @param  string       $pagetitle Sets the page's title
 * @param  string|array $message   A message show to the user
 * @example
 * pagetop('Title', array('My error message', E_ERROR));
 * echo 'My page contents.';
 */

	function pagetop($pagetitle, $message = '')
	{
		global $siteurl, $sitename, $txp_user, $event, $step, $app_mode, $theme;

		if ($app_mode == 'async')
		{
			return;
		}

		$area = gps('area');
		$event = (!$event) ? 'article' : $event;
		$bm = gps('bm');

		$privs = safe_field("privs", "txp_users", "name = '".doSlash($txp_user)."'");

		$GLOBALS['privs'] = $privs;

		$areas = areas();
		$area = false;

		foreach ($areas as $k => $v)
		{
			if (in_array($event, $v))
			{
				$area = $k;
				break;
			}
		}

		if (gps('logout'))
		{
			$body_id = 'page-logout';
		}

		elseif (!$txp_user)
		{
			$body_id = 'page-login';
		}

		else
		{
			$body_id = 'page-'.txpspecialchars($event);
		}

		header('X-Frame-Options: '.X_FRAME_OPTIONS);

	?><!doctype html>
	<html lang="<?php echo LANG; ?>" dir="<?php echo txpspecialchars(gTxt('lang_dir')); ?>">
	<head>
	<meta charset="utf-8">
	<meta name="robots" content="noindex, nofollow">
	<title><?php echo escape_title($pagetitle) ?> - <?php echo txpspecialchars($sitename) ?> &#124; Textpattern CMS</title>
	<script src="jquery.js?v=<?php echo txp_version; ?>"></script>
	<?php
	echo script_js(
		'var textpattern = {
		event: "'.txpspecialchars($event).'",
		step: "'.txpspecialchars($step).'",
		_txp_token: "'.txpspecialchars(form_token()).'",
		ajax_timeout: '.txpspecialchars(AJAX_TIMEOUT).',
		textarray: {},
		do_spellcheck: "'.
			txpspecialchars(get_pref('do_spellcheck', 
				'#page-article #body, #page-article #title, '.
				'#page-image #alt-text, #page-image #caption, '.
				'#page-file #description, #page-link #link-title, '.
				'#page-link #link-description'
			)).'"};'
	);
	gTxtScript(array('form_submission_error', 'are_you_sure', 'cookies_must_be_enabled'));
	?>
	<script src="textpattern.js?v=<?php echo txp_version; ?>"></script>
	<?php // Mandatory un-themable Textpattern core styles ?>
	<style>
		.not-ready .doc-ready, .not-ready form.async input[type="submit"], .not-ready a.async {
			visibility: hidden;
		}
	</style>
	<?php
	echo $theme->html_head();
	callback_event('admin_side', 'head_end');
	?>
	</head>
	<body id="<?php echo $body_id; ?>" class="not-ready <?php echo $area; ?>">
	<p class="txp-accessibility"><a href="#txp-main"><?php echo gTxt('skip_to_main_content'); ?></a></p>
	<header role="banner" class="txp-header">
	<?php callback_event('admin_side', 'pagetop');
		$theme->set_state($area, $event, $bm, $message);
		echo pluggable_ui('admin_side', 'header', $theme->header());
		callback_event('admin_side', 'pagetop_end');
		echo '</header><!-- /txp-header --><div role="main" id="txp-main" class="txp-body">';
	}

/**
 * Creates an area tab.
 *
 * This can be used to create table based navigation bars.
 *
 * @param      string $label
 * @param      string $event
 * @param      string $tarea
 * @param      string $area
 * @return     string HTML table column
 * @deprecated ?
 */

	function areatab($label, $event, $tarea, $area)
	{
		$tc = ($area == $event) ? 'tabup' : 'tabdown';
		$atts=' class="'.$tc.'"';
		$hatts=' href="?event='.$tarea.'"';
		return tda(tag($label,'a',$hatts),$atts);
	}

/**
 * Creates a secondary area tab.
 *
 * This can be used to create table based navigation bars.
 *
 * @param      string $label
 * @param      string $tabevent
 * @param      string $event
 * @return     string HTML table column
 * @deprecated ?
 */

	function tabber($label, $tabevent, $event)
	{
		$tc = ($event == $tabevent) ? 'tabup' : 'tabdown2';
		$out = '<td class="'.$tc.'"><a href="?event='.$tabevent.'">'.$label.'</a></td>';
		return $out;
	}

/**
 * Creates a table based navigation bar row.
 *
 * This can be used to create table based navigation bars.
 *
 * @param      string $area
 * @param      string $event
 * @return     string HTML table columns
 * @deprecated ?
 */

	function tabsort($area, $event)
	{
		if ($area)
		{
			$areas = areas();

			$out = array();

			foreach ($areas[$area] as $a => $b)
			{
				if (has_privs($b))
				{
					$out[] = tabber($a, $b, $event, 2);
				}
			}

			return ($out) ? join('', $out) : '';
		}

		return '';
	}

/**
 * Gets main menu structure as an array.
 *
 * @return array
 */

	function areas()
	{
		global $privs, $plugin_areas;

		$areas['start'] = array(
		);

		$areas['content'] = array(
			gTxt('tab_organise') => 'category',
			gTxt('tab_write')    => 'article',
			gTxt('tab_list')     => 'list',
			gTxt('tab_image')    => 'image',
			gTxt('tab_file')     => 'file',
			gTxt('tab_link')     => 'link',
			gTxt('tab_comments') => 'discuss'
		);

		$areas['presentation'] = array(
			gTxt('tab_sections') => 'section',
			gTxt('tab_pages')    => 'page',
			gTxt('tab_forms')    => 'form',
			gTxt('tab_style')    => 'css'
		);

		$areas['admin'] = array(
			gTxt('tab_diagnostics') => 'diag',
			gTxt('tab_preferences') => 'prefs',
			gTxt('tab_languages')   => 'lang',
			gTxt('tab_site_admin')  => 'admin',
			gTxt('tab_logs')        => 'log',
			gTxt('tab_plugins')     => 'plugin',
			gTxt('tab_import')      => 'import'
		);

		$areas['extensions'] = array(
		);

		if (is_array($plugin_areas))
		{
			$areas = array_merge_recursive($areas, $plugin_areas);
		}

		return $areas;
	}

/**
 * Creates an admin-side dropdown menu.
 *
 * @param  mixed  $inline Is not used.
 * @return string A HTML form with a select field
 */

	function navPop($inline = '')
	{
		$areas = areas();

		$out = array();

		foreach ($areas as $a => $b)
		{
			if (!has_privs( 'tab.'.$a))
			{
				continue;
			}

			if (count($b) > 0)
			{
				$out[] = n.t.'<optgroup label="'.gTxt('tab_'.$a).'">';

				foreach ($b as $c => $d)
				{
					if (has_privs($d))
					{
						$out[] = n.t.t.'<option value="'.$d.'">'.$c.'</option>';
					}
				}

				$out[] = n.t.'</optgroup>';
			}
		}

		if ($out)
		{
			return '<form method="get" action="index.php" class="navpop">'.
				n.'<select name="event" onchange="submit(this.form);">'.
				n.t.'<option>'.gTxt('go').'&#8230;</option>'.
				join('', $out).
				n.'</select>'.
				n.'</form>';
		}
	}

/**
 * Generates a button link
 *
 * @param      string $label
 * @param      string $link
 * @deprecated ?
 */

	function button($label, $link)
	{
		return '<span style="margin-right:2em"><a href="?event='.$link.'">'.$label.'</a></span>';
	}