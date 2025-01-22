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
	// User preferences
	'CTWC_CURRTIME_FORMAT'			=> 'Date format for the “Current time”',
	'CTWC_CURRTIME_FORMAT_EXPLAIN'	=> 'Click here for an explanation of the formatting options.',
	'UCP_CT_MODULE_WORLDCLOCK'		=> 'Edit World Clock',

	// Radio button label
	'ON'	=> 'On',
	'OFF'	=> 'Off',

	// Description of the format string
	'CTWC_DATEFORMAT_TITLE'	=> 'Date format for Current Time and World Clock',
	'CTWC_DATEFORMAT_DECR1'	=> 'The format of the date/time string output can be freely defined using the formatting options described in the table. To prevent a known character from being interpreted in the format string, it can be escaped with a preceding backslash. If the character with a backslash is already a special sequence, you may need to also escape the backslash.',
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
