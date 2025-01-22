<?php
/**
 * Current Time
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace imcger\currenttime\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Style listener
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var string Table name */
	protected $ctwc_data_table;


	public function __construct
	(
		\phpbb\auth\auth $auth,
		\phpbb\user $user,
		\phpbb\template\template $template,
		\phpbb\language\language $language,
		$ctwc_data_table
	)
	{
		$this->auth		= $auth;
		$this->user		= $user;
		$this->template	= $template;
		$this->language = $language;
		$this->ctwc_data_table = $ctwc_data_table;
	}

	public static function getSubscribedEvents()
	{
		return [
			'core.user_setup_after'				=> 'add_languages',
			'core.permissions'					=> 'add_permissions',
			'core.page_header_after'			=> 'page_header_after',
		];
	}

	public function add_languages()
	{
		$this->language->add_lang(['info_ucp_ctwc', ], 'imcger/currenttime');
	}

	public function add_permissions($event)
	{
		$event->update_subarray( 'permissions', 'u_ctwc_access', [ 'lang' => 'ACL_U_CTWC_ACCESS', 'cat' => 'misc' ] );
	}

	public function page_header_after()
	{
		$weekday_month = [
				['dummy', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', ],
				['dummy', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', ],
				['Jan', 'Feb', 'Mar', 'Apr', 'May_short', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', ],
				['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', ],
			];

		$i = 0;
		$js_weekday_month = [];

		foreach ($weekday_month as $weekday_month_ary)
		{
			$js_weekday_month[$i] = '';

			foreach ($weekday_month_ary as $name)
			{
				$js_weekday_month[$i] .= '\'' . $this->language->lang(['datetime', $name]) . '\', ';
			}
			$i++;
		}

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
			'/(?<!\\\\)W0S/', // Week number of year, weeks starting on Sunday				1 to 53
			'/(?<!\\\\)W7S/', // Weeks starting on Sunday, weeks count from 1st Januar		1 to 54
							  // with suffix
			'/(?<!\\\\)WS/',  // ISO 8601 week number of year, weeks starting on Monday		1 to 53
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

		$wc_date_ary = json_decode($this->user->data['user_imcger_ct_data'],  true);

		if (is_array($wc_date_ary) && $this->auth->acl_get('u_ctwc_access'))
		{
			foreach ($wc_date_ary as $clock_data)
			{
				if (isset($clock_data[0]) && $clock_data[0] == 1 && !!$clock_data[1])
				{
					$dateTimeZone = new \DateTimeZone($clock_data[1]);
					$dateTime	  = new \DateTime("now", $dateTimeZone);

					$timeOffset = $dateTimeZone->getOffset($dateTime);
					$city		= !!$clock_data[2] ? $clock_data[2] : end(explode('/', $this->language->lang(['timezones', $clock_data[1]])));

					// Generate tz_select for backwards compatibility
					$this->template->assign_block_vars('ctwc_clock', [
						'CITY'		 => $city,
						'TIMEOFFSET' => $timeOffset,
					]);
				}
			}

			$format = isset($wc_date_ary[6]) ? $wc_date_ary[6] : $this->user->date_format;
			$date_str = preg_replace($pattern, $wc_replacement, $format);
			$date_str = preg_replace('/(?<!\\\\)\\\\/', '', $date_str);

			$this->template->assign_vars([
				'CTWC_DATESTRING'	=> $date_str,
				'CTWC_LINES'		=> $wc_date_ary[7] ?? 0,
			]);
		}

		$datetime  = $this->user->create_datetime();
		$tz_offset = $datetime->getOffset();

		$dateformat = !!$this->user->data['user_ctwc_currtime_format'] ? $this->user->data['user_ctwc_currtime_format'] : $this->user->data['user_dateformat'];
		$dateformat = preg_replace($pattern, $ct_replacement, $dateformat);

		$this->template->assign_vars([
			'CURRENT_TIME'				=> $this->language->lang('CURRENT_TIME', $this->user->format_date(time(), $dateformat, false)),
			'CTWC_TZOFFSET'				=> $tz_offset,
			'CTWC_WEEKDAY_SHORT_ARY'	=> $js_weekday_month[0],
			'CTWC_WEEKDAY_ARY'			=> $js_weekday_month[1],
			'CTWC_MONTHS_SHORT_ARY'		=> $js_weekday_month[2],
			'CTWC_MONTHS_ARY'			=> $js_weekday_month[3],
		]);
	}
}
