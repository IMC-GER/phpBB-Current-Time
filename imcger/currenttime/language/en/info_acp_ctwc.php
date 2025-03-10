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
	'CTWC_LANG_DESC'			=> 'English',
	'CTWC_LANG_EXT_VER' 		=> '0.11.0',
	'CTWC_LANG_AUTHOR' 			=> 'IMC-GER',

	'ACP_CT_MODULE_WORLDCLOCK'	=> 'Current Time / World Clock',
	'ACP_CTWC_WORLDCL_SETTINGS'	=> 'Worldclock Settings',
	'ACP_CT_SETTINGS'			=> 'General Settings',
	'ACP_CTWC_GSET_EXP'			=> 'Here you can activate or deactivate the modules of this extension. If you only want to deactivate them for individual users or user groups, you must do this using the forum\'s permissions system.',
	'ACP_CTWC_TITLE'			=> 'World Clock',
	'ACP_CTWC_DESC'				=> 'To display the “World Clock”, the user must be assigned user permissions in the permission management. The basic settings for new users are made on this page. These settings can be applied to all users.',
	'ACP_CTWC_ANONYMOUS_DESC'	=> 'The settings for anonymous user must be made <a href="%1s#ctwc_prefs">on this page</a>.',

	// Permission
	'ACL_U_CTWC_ACCESS'			  => 'Can view world clock',
	'ACL_U_CTWC_CANSEE_LOCALTIME' => 'Can see the user\'s local time',

	// General settings
	'CTWC_SHOW_CURRENTTIME'			=> 'Display the current time on all forum pages',
	'CTWC_SHOW_WORLDCLOCK'			=> 'User can display the World Clock',
	'CTWC_SHOW_LOCALTIME_PROFIL'	=> 'Display of the user\'s local time in their profile',
	'CTWC_SHOW_LOCALTIME_POST'		=> 'Display the local time of the post author in the post profile',

	// Settings world clock
	'CTWC_SETTINGS'			=> 'Settings',
	'CTWC_WORLDCLOCK_1'		=> 'World Clock 1',
	'CTWC_WORLDCLOCK_2'		=> 'World Clock 2',
	'CTWC_WORLDCLOCK_3'		=> 'World Clock 3',
	'CTWC_WORLDCLOCK_4'		=> 'World Clock 4',
	'CTWC_WORLDCLOCK_5'		=> 'World Clock 5',
	'CTWC_WORLDCLOCK_6'		=> 'World Clock 6',
	'CTWC_WORLDCLOCK_EXP'	=> 'You can activate the world clock and set the time zone and city of the time zone. The name of the city is displayed before the time string.',
	'CTWC_SELECT_TZ_TIME'	=> 'Select timezone time',

	'CTWC_TZ_CITY'			 => 'Alternative designation of the time zone',
	'CTWC_TZ_CITY_EXP'		 => 'If you leave this field blank, the city name of the time zone will be displayed.',
	'CTWC_WCLOCK_FORMAT'	 => 'World clock time format',

	'CTWC_WCLOCK_LINES'		=> 'Display world clocks in',
	'CTWC_SINGLELINE'		=> 'single-line',
	'CTWC_TWOLINES'			=> 'two lines',

	'CTWC_RESET_DEFAULT'		=> 'Overwrite user settings',
	'CTWC_RESET_DEFAULT_EXP'	=> 'If this option is activated, the settings of all users are overwritten. If it is not activated, only the default values for new users are set.',
	'CTWC_RESET_ASK_BEFORE_EXP'	=> 'This setting overwrites the settings of all users with the default values.<br><strong>This process cannot be reversed!</strong>',

	'ctwc_dateformats'	=> [
		'D j. M Y, H:i:s'		=> 'Sun 1. Jan 2025, 18:33:28',
		'j. M Y, H:i'			=> '1. Jan 2025, 13:57',
		'D j. M Y, H:i:s'		=> 'Mon 1. Jan 2025, 13:57:28',
		'j. F Y, H:i'			=> '1. Januar 2025, 13:57',
		'l j. F Y, H:i'			=> 'Monday 1. Januar 2025, 13:57',
		'd.m.Y, H:i:s'			=> '01.01.2025, 13:57:28',
		'D j. M, H:i:s \G\M\TP'	=> 'Mon 1. Jan, 01:08:35 GMT+01:00',
	],

	// User preferences
	'CTWC_CANSEE_LOCALTIME'			=> 'Users can see my local time',
	'CTWC_CURRTIME_FORMAT'			=> 'Date format for the “Current time”',
	'CTWC_CURRTIME_FORMAT_EXPLAIN'	=> 'Click here for an explanation of the supported formatting options.',

	// Radio button label
	'ON'	=> 'On',
	'OFF'	=> 'Off',

	// Description of the format string
	'CTWC_DATEFORMAT_TITLE'	=> 'Date format for Current Time and World Clock',
	'CTWC_DATEFORMAT_DECR1'	=> 'The format of the date/time string output can be freely defined using the formatting options described in the table. To prevent a known character from being interpreted in the format string, it can be escaped with a preceding backslash.',
	'CTWC_DATEFORMAT_DECR2'	=> 'The format string <code>\'D, d M Y H:i:s \G\M\TP\'</code> corresponds to this output <code>\'Mon, 20 Jan 2025 23:18:29 GMT+01:00\'</code>',
	'CTWC_DATEFORMAT_BACKLINK'	=> 'Back to the settings',

	// Table head
	'CTWC_DATEFORMAT_OPTION'	=> 'Opt.',
	'CTWC_DATEFORMAT_DECR'		=> 'Description',
	'CTWC_DATEFORMAT_DISPLAY'	=> 'Displays',

	// Table body
	'CTWC_DATEFORMAT_TIME'	=> 'Time',
	'CTWC_DATEFORMAT_LOW_G'	=> '12-hour format of an hour without leading zeros',
	'CTWC_DATEFORMAT_G'		=> '24-hour format of an hour without leading zeros',
	'CTWC_DATEFORMAT_LOW_H'	=> '12-hour format of an hour with leading zeros',
	'CTWC_DATEFORMAT_H'		=> '24-hour format of an hour with leading zeros',
	'CTWC_DATEFORMAT_LOW_I'	=> 'Minutes with leading zeros',
	'CTWC_DATEFORMAT_LOW_S'	=> 'Seconds with leading zeros',
	'CTWC_DATEFORMAT_LOW_A'	=> 'Lowercase Ante meridiem and Post meridiem',
	'CTWC_DATEFORMAT_A' 	=> 'Uppercase Ante meridiem and Post meridiem',

	'CTWC_DATEFORMAT_DATE'	=> 'Date',
	'CTWC_DATEFORMAT_LOW_Y'	=> 'A two digit representation of a year',
	'CTWC_DATEFORMAT_Y'		=> 'A full numeric representation of a year',
	'CTWC_DATEFORMAT_LOW_N'	=> 'Numeric representation of a month, without leading zeros',
	'CTWC_DATEFORMAT_LOW_M'	=> 'Numeric representation of a month, with leading zeros',
	'CTWC_DATEFORMAT_M'		=> 'A short textual representation of a month, three letters',
	'CTWC_DATEFORMAT_F'		=> 'A full textual representation of a month',
	'CTWC_DATEFORMAT_LOW_J'	=> 'Day of the month without leading zeros',
	'CTWC_DATEFORMAT_JS'	=> 'Day of the month with suffix and without leading zeros',
	'CTWC_DATEFORMAT_LOW_D'	=> 'Day of the month, 2 digits with leading zeros',
	'CTWC_DATEFORMAT_D' 	=> 'A textual representation of a day, three letters',
	'CTWC_DATEFORMAT_LOW_L'	=> 'A full textual representation of the day of the week (lower case \'L\')',
	'CTWC_DATEFORMAT_Z1'	=> 'The day of the year (starting from 1)',
	'CTWC_DATEFORMAT_LOW_Z'	=> 'The day of the year (starting from 0)',

	'CTWC_DATEFORMAT_WEEK'	=> 'Week',
	'CTWC_DATEFORMAT_W'		=> 'ISO 8601 week number of year, weeks starting on Monday',
	'CTWC_DATEFORMAT_WS'	=> 'Week number of year, weeks starting on Monday with suffix',
	'CTWC_DATEFORMAT_W0'	=> 'Start of the week on Sunday counting similar–ISO',
	'CTWC_DATEFORMAT_W0S'	=> 'Start of the week on Sunday counting similar–ISO with suffix',
	'CTWC_DATEFORMAT_W7'	=> 'Weeks starting on Sunday, first week starts on 1st Januar',
	'CTWC_DATEFORMAT_W7S'	=> 'Weeks starting on Sunday, first week starts on 1st Januar with suffix',

	'CTWC_DATEFORMAT_TZ'	=> 'Timezone',
	'CTWC_DATEFORMAT_O'		=> 'Difference–Greenwich time (GMT) without colon between hours and minutes',
	'CTWC_DATEFORMAT_P'		=> 'Difference–Greenwich time (GMT) with colon between hours and minutes',
]);
