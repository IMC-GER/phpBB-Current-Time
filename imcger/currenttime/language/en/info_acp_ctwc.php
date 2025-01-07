<?php
/**
 * Current Time
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (! defined( 'IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [
	// Language pack author
	'CTWC_LANG_DESC'				=> 'English',
	'CTWC_LANG_EXT_VER' 			=> '0.7.0',
	'CTWC_LANG_AUTHOR' 				=> 'IMC-GER',

	'ACP_CT_MODULE_WORLDCLOCK'		 => 'Current Time / World Clock',
	'ACP_CT_SETTINGS'				 => 'Settings',
	'ACP_CTWC_TITLE'				 => 'World Clock',
	'ACP_CTWC_DESC'					 => 'To display the “World Clock”, the user must be assigned user permissions in the permission management. The basic settings for new users are made <a href="%1s">on this page</a>. These settings can be applied to all users. The settings for anonymous user must be made on this page.',

	'UCP_CT_MODULE_WORLDCLOCK'		 => 'Edit World Clock',
	'UCP_CT_MODULE_WORLDCLOCK_TITLE' => 'World Clock',

	// Permission
	'ACL_U_CTWC_ACCESS'		=> 'Can view world clock',

	// Settings
	'CTWC_SETTINGS'			=> 'Settings',
	'CTWC_WORLDCLOCK_1'		=> 'World Clock 1',
	'CTWC_WORLDCLOCK_2'		=> 'World Clock 2',
	'CTWC_WORLDCLOCK_3'		=> 'World Clock 3',
	'CTWC_WORLDCLOCK_4'		=> 'World Clock 4',
	'CTWC_WORLDCLOCK_5'		=> 'World Clock 5',
	'CTWC_WORLDCLOCK_6'		=> 'World Clock 6',
	'CTWC_WORLDCLOCK_EXP'	=> 'You can activate the world clock and set the time zone and city of the time zone. The name of the city is displayed before the time string.',

	'CTWC_TZ_CITY'			 => 'Alternative designation of the time zone',
	'CTWC_TZ_CITY_EXP'		 => 'If you leave this field blank, the city name of the time zone will be displayed.',
	'CTWC_WCLOCK_FORMAT'	 => 'World clock time format',
	'CTWC_WCLOCK_FORMAT_EXP' => 'These placeholders “g G h H i s a A y Y n m M j d D” are currently supported.',

	'CTWC_WCLOCK_LINES'		=> 'Display world clocks in',
	'CTWC_SINGLELINE'		=> 'single-line',
	'CTWC_TWOLINES'			=> 'two lines',

	'CTWC_RESET_DEFAULT'		=> 'Overwrite user settings',
	'CTWC_RESET_DEFAULT_EXP'	=> 'If this option is activated, the settings of all users are overwritten. If it is not activated, only the default values for new users are set.',
	'CTWC_RESET_ASK_BEFORE_EXP'	=> 'This setting overwrites the settings of all users with the default values.<br><strong>This process cannot be reversed!</strong>',
]);
