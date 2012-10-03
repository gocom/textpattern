<?php

/**
 * Textpatten permissions and groups.
 */

/**
 * Sets privileges.
 */

$txp_permissions = array(
	'admin'                       => '1,2,3,4,5,6',
	'admin.edit'                  => '1',
	'admin.list'                  => '1,2,3',
	'article.delete.own'          => '1,2,3,4',
	'article.delete'              => '1,2',
	'article.edit'                => '1,2,3',
	'article.edit.published'      => '1,2,3',
	'article.edit.own'            => '1,2,3,4,5,6',
	'article.edit.own.published'  => '1,2,3,4',
	'article.preview'             => '1,2,3,4',
	'article.publish'             => '1,2,3,4',
	'article.php'                 => '1,2',
	'article'                     => '1,2,3,4,5,6',
	'list'                        => '1,2,3,4,5,6',
	'category'                    => '1,2,3',
	'css'                         => '1,2,      6',
	'debug.verbose'               => '1,2',
	'debug.backtrace'             => '1',
	'diag'                        => '1,2',
	'discuss'                     => '1,2,3',
	'file'                        => '1,2,3,4,  6',
	'file.edit'                   => '1,2,      6',
	'file.edit.own'               => '1,2,3,4,  6',
	'file.delete'                 => '1,2',
	'file.delete.own'             => '1,2,3,4,  6',
	'file.publish'                => '1,2,3,4,  6',
	'form'                        => '1,2,3,    6',
	'image'                       => '1,2,3,4,  6',
	'image.create.trusted'        => '1,2,3,	6',
	'image.edit'                  => '1,2,3,    6',
	'image.edit.own'              => '1,2,3,4,  6',
	'image.delete'                => '1,2',
	'image.delete.own'            => '1,2,3,4,  6',
	'import'                      => '1,2',
	'lang'                        => '1,2',
	'link'                        => '1,2,3',
	'link.edit'                   => '1,2,3',
	'link.edit.own'               => '1,2,3',
	'link.delete'                 => '1,2',
	'link.delete.own'             => '1,2,3',
	'log'                         => '1,2,3',
	'page'                        => '1,2,3,    6',
	'plugin'                      => '1,2',
	'prefs'                       => '1,2',
	'section'                     => '1,2,3,    6',
	'section.edit'                => '1,2,3,    6',
	'tab.admin'                   => '1,2,3,4,5,6',
	'tab.content'                 => '1,2,3,4,5,6',
	'tab.extensions'              => '1,2',
	'tab.presentation'            => '1,2,3,    6',
	'tag'                         => '1,2,3,4,5,6',
);

/**
 * List of user-groups.
 */

$txp_groups = array(
	1 => 'publisher',
	2 => 'managing_editor',
	3 => 'copy_editor',
	4 => 'staff_writer',
	5 => 'freelancer',
	6 => 'designer',
	0 => 'privs_none'
);