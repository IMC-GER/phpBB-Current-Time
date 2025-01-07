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
	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;


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
	 *
	 * @param	string			$prefix				Prefix for template loop
	 * @param	string			$default			A timezone to select
	 *
	 */
	public function timezone_select($prefix = '', $default = '')
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
}
