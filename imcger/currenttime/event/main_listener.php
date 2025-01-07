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
			'core.ucp_register_register_after'	=> 'ucp_register_set_data',
		];
	}

	public function add_languages()
	{

		$this->language->add_lang(['info_acp_ctwc', ], 'imcger/currenttime');
		$this->language->add_lang(['common', ]);
	}

	public function add_permissions($event)
	{
		$event->update_subarray( 'permissions', 'u_ctwc_access', [ 'lang' => 'ACL_U_CTWC_ACCESS', 'cat' => 'misc' ] );
	}

	public function page_header_after()
	{
		$weekday_short = ['dummy', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', ];
		$month_short = ['Jan', 'Feb', 'Mar', 'Apr', 'May_short', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', ];

		$js_weekday_short = '';
		foreach ($weekday_short as $weekday)
		{
			$js_weekday_short .= '\'' . $this->language->lang(['datetime', $weekday]) . '\', ';
		}

		$js_months_short = '';
		foreach ($month_short as $months)
		{
			$js_months_short .= '\'' . $this->language->lang(['datetime', $months]) . '\', ';
		}

		$datetime  = $this->user->create_datetime();
		$tz_offset = $datetime->getOffset();

		$dateformat	 = $this->user->data['user_dateformat'];
		$wc_date_ary = json_decode($this->user->data['user_imcger_ct_data'],  true);

		$pattern = [
			'/g/', // hour in 12-hour format; without leading zero	 1 to 12
			'/G/', // hour in 24-hour format; without leading zero	 0 to 23
			'/h/', // hour in 12-hour format; with leading zero		01 to 12
			'/H/', // hour in 24-hour format; with leading zero		00 to 23
			'/i/', // minutes; with leading zero					00 to 59
			'/s/', // seconds; with leading zero					00 to 59
			'/a/', // Lowercase Ante meridiem and Post meridiem
			'/A/', // Uppercase Ante meridiem and Post meridiem
			'/y/', // A two digit representation of a year
			'/Y/', // A full numeric representation of a year
			'/n/', // Numeric representation of a month, without leading zeros 	1 through 12
			'/m/', // Numeric representation of a month, with leading zeros 	01 through 12
			'/M/', // A short textual representation of a month, three letters 	Jan through Dec
			'/j/', // Day of the month without leading zeros 					1 to 31
			'/d/', // Day of the month, 2 digits with leading zeros 			01 to 31
			'/D/', // A textual representation of a day, three letters 			Mon through Sun
		];

		$ct_replacement = [
			'{\g}', '{\G}', '{\h}', '{\H}', '{\i}', '{\s}', '{\a}', '{\A}',
			'{\y}', '{\Y}', '{\n}', '{\m}', '{\M}', '{\j}', '{\d}', '{\D}',
		];

		$wc_replacement = [
			'{g}', '{G}', '{h}', '{H}', '{i}', '{s}', '{a}', '{A}',
			'{y}', '{Y}', '{n}', '{m}', '{M}', '{j}', '{d}', '{D}',
		];

		$dateformat = preg_replace($pattern, $ct_replacement, $dateformat);

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

			$date_str	= preg_replace($pattern, $wc_replacement, $wc_date_ary[6] ?? '{H}:{i}:{s}');

			$this->template->assign_vars([
				'CTWC_DATESTRING'	=> $date_str,
				'CTWC_LINES'		=> $wc_date_ary[7] ?? 0,
			]);
		}

		$this->template->assign_vars([
			'CURRENT_TIME'				=> $this->language->lang('CURRENT_TIME', $this->user->format_date(time(), $dateformat, false)),
			'CTWC_TZOFFSET'				=> $tz_offset,
			'CTWC_WEEKDAY_SHORT_ARY'	=> $js_weekday_short,
			'CTWC_MONTHS_SHORT_ARY'		=> $js_months_short,
		]);
	}

	/**
	 * After registering a new user, transfer the default values to their settings.
	 */
	public function ucp_register_set_data($event)
	{
		$sql = 'SELECT data_content FROM ' . $this->ctwc_data_table . ' WHERE data_id = "default"';
		$result = $this->db->sql_query_limit($sql, 1);

		$user_data_str = (string) $this->db->sql_fetchfield('data_content');
		$this->db->sql_freeresult($result);

		$sql = 'UPDATE ' . USERS_TABLE . '
				SET user_imcger_ct_data=\'' . $user_data_str . '\'
				WHERE user_id = ' . (int) $event['user_id'];

		$this->db->sql_query($sql);
	}
}
