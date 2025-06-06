<?php
/**
 * Current Time
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace imcger\currenttime\controller;

class ctwc_helper
{
	protected object $user;
	protected object $template;
	protected object $language;

	/**
	 * Constructor
	 */
	public function __construct
	(
		\phpbb\user $user,
		\phpbb\template\template $template,
		\phpbb\language\language $language
	)
	{
		$this->user		= $user;
		$this->template	= $template;
		$this->language = $language;
	}

	/**
	 * Options to pick a timezone and date/time
	 */
	public function timezone_select(string $prefix = '', string $default = ''): void
	{
		$default_offset = '';

		if (!isset($timezones))
		{
			$unsorted_timezones = \DateTimeZone::listIdentifiers();

			$timezones = [];
			foreach ($unsorted_timezones as $timezone)
			{
				$tz				= new \DateTimeZone($timezone);
				$dt				= $this->user->create_datetime('now', $tz);
				$offset			= $dt->getOffset();
				$current_time	= $dt->format($this->language->lang('DATETIME_FORMAT'), true);
				$offset_string	= phpbb_format_timezone_offset($offset, true);

				$timezones['UTC' . $offset_string . ' - ' . $timezone] = [
					'tz'		=> $timezone,
					'offset'	=> $offset_string,
					'current'	=> $current_time,
				];

				if ($timezone === $default)
				{
					$default_offset = 'UTC' . $offset_string;
				}
			}
			unset($unsorted_timezones);

			uksort($timezones, 'phpbb_tz_select_compare');
		}

		$opt_group = '';

		foreach ($timezones as $key => $timezone)
		{
			if ($opt_group != $timezone['offset'])
			{
				// Generate tz_select for backwards compatibility
				$opt_group = $timezone['offset'];
				$this->template->assign_block_vars($prefix . '_timezone_select', [
					'LABEL'		=> $this->language->lang(['timezones', 'UTC_OFFSET_CURRENT'], $timezone['offset'], $timezone['current']),
					'VALUE'		=> $key . ' - ' . $timezone['current'],
				]);

				$selected = (!empty($default_offset) && strpos($key, $default_offset) !== false) ? ' selected' : '';
				$this->template->assign_block_vars($prefix . '_timezone_date', [
					'VALUE'		=> $key . ' - ' . $timezone['current'],
					'SELECTED'	=> !empty($selected),
					'TITLE'		=> $this->language->lang(['timezones', 'UTC_OFFSET_CURRENT'], $timezone['offset'], $timezone['current']),
				]);
			}

			$label = $this->language->lang(['timezones', $timezone['tz']]) ?? $timezone['tz'];

			$title = $this->language->lang(['timezones', 'UTC_OFFSET_CURRENT'], $timezone['offset'], $label);

			// Also generate timezone_select for backwards compatibility
			$selected = ($timezone['tz'] === $default) ? ' selected' : '';
			$this->template->assign_block_vars($prefix . '_timezone_select.timezone_options', [
				'TITLE'			=> $title,
				'VALUE'			=> $timezone['tz'],
				'SELECTED'		=> !empty($selected),
				'LABEL'			=> $label,
			]);
		}
	}

	/*
	 * Creates an array of variables for the SelectBox macro
	 *
	 * The variable $cfg_value is a union type array|string
	 * Not specified for reasons of compatibility with php 7
	 */
	public function select_struct($cfg_value, array $options): array
	{
		$options_tpl = [];

		foreach ($options as $opt_key => $opt_value)
		{
			if (!is_array($opt_value))
			{
				$opt_value = [$opt_value];
			}
			$options_tpl[] = [
				'label'		=> $opt_key,
				'value'		=> $opt_value[0],
				'bold'		=> $opt_value[1] ?? false,
				'selected'	=> is_array($cfg_value) ? in_array($opt_value[0], $cfg_value) : $opt_value[0] == $cfg_value,
			];
		}

		return $options_tpl;
	}

	/*
	 * Creates an array of dateformat options for the SelectBox macro
	 */
	public function set_select_template_vars(string $date_format, string $template_var): void
	{
		$this->language->add_lang(['info_acp_ctwc', ], 'imcger/currenttime');

		$dateformat = preg_replace('/[0-9]/', '', $date_format);
		$dateformat_options = [$this->user->format_date(time(), $dateformat, false) => $date_format];
		foreach ($this->language->lang_raw('ctwc_dateformats') as $format => $null)
		{
			if ($date_format != $format)
			{
				$dateformat = preg_replace('/[0-9]/', '', $format);
				$dateformat_options += [$this->user->format_date(time(), $dateformat, false) => $format];
			}
		}

		$this->template->assign_vars([
			$template_var => $this->select_struct($date_format, $dateformat_options),
		]);
	}

	/*
	 * Changes the placeholder in the date/time string for the JavaScript clock class
	 */
	public function generate_datetime_str(string $replacement, string $format): string
	{
		$pattern = [
			'/(?<!\\\\)g/',   // hour in 12-hour format; without leading zero				 1 to 12
			'/(?<!\\\\)G/',   // hour in 24-hour format; without leading zero				 0 to 23
			'/(?<!\\\\)h/',   // hour in 12-hour format; with leading zero					01 to 12
			'/(?<!\\\\)H/',   // hour in 24-hour format; with leading zero					00 to 23
			'/(?<!\\\\)i/',   // minutes; with leading zero									00 to 59
			'/(?<!\\\\)s/',   // seconds; with leading zero									00 to 59
			'/(?<!\\\\)a/',   // Lowercase Ante meridiem and Post meridiem					am or pm
			'/(?<!\\\\)A/',   // Uppercase Ante meridiem and Post meridiem					AM or PM
			'/(?<!\\\\)y/',   // A two digit representation of a year
			'/(?<!\\\\)Y/',   // A full numeric representation of a year
			'/(?<!\\\\)n/',   // Numeric representation of a month, without leading zeros	 1 through 12
			'/(?<!\\\\)m/',   // Numeric representation of a month, with leading zeros		01 through 12
			'/(?<!\\\\)M/',   // A short textual representation of a month, three letters	Jan through Dec
			'/(?<!\\\\)jS/',  // Day of the month with suffix and without leading zeros 	1st to 31st
			'/(?<!\\\\)j/',   // Day of the month without leading zeros 					1 to 31
			'/(?<!\\\\)d/',   // Day of the month, 2 digits with leading zeros 				01 to 31
			'/(?<!\\\\)D/',   // A textual representation of a day, three letters			Mon through Sun
			'/(?<!\\\\)z1/',  // The day of the year (starting from 1) 						1 through 366
			'/(?<!\\\\)z/',   // The day of the year (starting from 0) 						0 through 365
			'/(?<!\\\\)W0S/', // Week number of year, weeks starting on Sunday				1st to 53rd
			'/(?<!\\\\)W7S/', // Weeks starting on Sunday, weeks count from 1st Januar		1st to 54rd
							  // with suffix
			'/(?<!\\\\)WS/',  // ISO 8601 week number of year, weeks starting on Monday		1st to 53rd
			'/(?<!\\\\)W0/',  // Week number of year, weeks starting on Sunday				1 to 53
			'/(?<!\\\\)W7/',  // Weeks starting on Sunday, weeks count from 1st Januar		1 to 54
			'/(?<!\\\\)W/',   // ISO 8601 week number of year, weeks starting on Monday		1 to 53
			'/(?<!\\\\)l/',   // A full textual representation of the day of the week 		Sunday through Saturday
			'/(?<!\\\\)F/',   // A full textual representation of a month					January through December
			'/(?<!\\\\)O/',   // Difference to Greenwich time (GMT) without colon 			Example: +0200
			'/(?<!\\\\)P/',   // Difference to Greenwich time (GMT) with colon 				Example: +02:00
		];

		$ct_replacement = [
			'{\g}', '{\G}', '{\h}', '{\H}', '{\i}', '{\s}', '{\a}', '{\A}', '{\y}', '{\Y}', '{\n}',
			'{\m}', '{\M}', '{\j\S}', '{\j}', '{\d}', '{\D}', '{\z1}', '{\z}', '{\W0\S}', '{\W7\S}', '{\W\S}',
			'{\W0}', '{\W7}', '{\W}', '{\l}', '{\F}', '{\O}', '{\P}',
		];

		$wc_replacement = [
			'{g}', '{G}', '{h}', '{H}', '{i}', '{s}', '{a}', '{A}', '{y}', '{Y}', '{n}',
			'{m}', '{M}', '{jS}', '{j}', '{d}', '{D}', '{z1}', '{z}', '{W0S}', '{W7S}', '{WS}',
			'{W0}', '{W7}', '{W}', '{l}', '{F}', '{O}', '{P}',
		];

		$replacement = $replacement == 'ct' ? $ct_replacement : $wc_replacement;

		return preg_replace($pattern, $replacement, $format);
	}
}
