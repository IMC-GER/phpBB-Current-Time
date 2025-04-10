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
 * Main listener
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\user */
	protected $user;

	/** @var config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var string Table name */
	protected $ctwc_data_table;


	/**
	 * Constructor
	 */
	public function __construct
	(
		\phpbb\auth\auth $auth,
		\phpbb\user $user,
		\phpbb\config\config $config,
		\phpbb\template\template $template,
		\phpbb\language\language $language,
		$ctwc_data_table
	)
	{
		$this->auth		= $auth;
		$this->user		= $user;
		$this->config	= $config;
		$this->template	= $template;
		$this->language = $language;
		$this->ctwc_data_table = $ctwc_data_table;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.user_setup_after'					=> 'add_languages',
			'core.permissions'						=> 'add_permissions',
			'core.page_header_after'				=> 'page_header_after',
			'core.memberlist_prepare_profile_data'	=> 'memberlist_prepare_profile_data',
			'core.viewtopic_cache_user_data'		=> 'viewtopic_cache_user_data',
			'core.viewtopic_modify_post_row'		=> 'viewtopic_modify_post_row',
		];
	}

	/**
	 * Add language file
	 */
	public function add_languages()
	{
		$this->language->add_lang(['ctwc_common', ], 'imcger/currenttime');
	}

	/**
	 * Execute code and/or overwrite _common_ template variables after they have been assigned.
	 */
	public function add_permissions($event)
	{
		$event->update_subarray( 'permissions', 'u_ctwc_access', [ 'lang' => 'ACL_U_CTWC_ACCESS', 'cat' => 'misc' ] );
		$event->update_subarray( 'permissions', 'u_ctwc_cansee_localtime', [ 'lang' => 'ACL_U_CTWC_CANSEE_LOCALTIME', 'cat' => 'misc' ] );
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

		$wc_date_ary = json_decode($this->user->data['user_imcger_ct_data'],  true);

		if (is_array($wc_date_ary) && $this->auth->acl_get('u_ctwc_access') && $this->config['ctwc_show_worldclock'])
		{
			foreach ($wc_date_ary as $clock_data)
			{
				if (isset($clock_data[0]) && $clock_data[0] == 1 && !!$clock_data[1])
				{
					$dateTimeZone = new \DateTimeZone($clock_data[1]);
					$dateTime	  = new \DateTime('now', $dateTimeZone);

					$timeOffset = $dateTimeZone->getOffset($dateTime);
					$city		= !!$clock_data[2] ? $clock_data[2] : end(explode('/', $this->language->lang(['timezones', $clock_data[1]])));

					$this->template->assign_block_vars('ctwc_clock', [
						'CITY'		 => $city,
						'TIMEOFFSET' => $timeOffset,
					]);
				}
			}

			$format = isset($wc_date_ary[6]) ? $wc_date_ary[6] : $this->user->date_format;
			$date_str = $this->generate_datetime_str('wc', $format);
			$date_str = preg_replace('/(?<!\\\\)\\\\/', '', $date_str);

			$this->template->assign_vars([
				'CTWC_DATESTRING'	=> $date_str,
				'CTWC_LINES'		=> $wc_date_ary[7] ?? 0,
			]);
		}

		$datetime  = $this->user->create_datetime();
		$tz_offset = $datetime->getOffset();

		$dateformat = !!$this->user->data['user_ctwc_currtime_format'] ? $this->user->data['user_ctwc_currtime_format'] : $this->user->data['user_dateformat'];
		$dateformat = $this->generate_datetime_str('ct', $dateformat);


		if ($this->config['ctwc_show_currenttime'])
		{
			$currenttime = $this->language->lang('CURRENT_TIME', $this->user->format_date(time(), $dateformat, false));

			$this->template->assign_vars([
				'CURRENT_TIME'			=> $currenttime,
			]);
		}

		$this->template->assign_vars([
			'CTWC_SHOW_CURRENTTIME'		=> $this->config['ctwc_show_currenttime'],
			'CTWC_TZOFFSET'				=> $tz_offset,
			'CTWC_WEEKDAY_SHORT_ARY'	=> $js_weekday_month[0],
			'CTWC_WEEKDAY_ARY'			=> $js_weekday_month[1],
			'CTWC_MONTHS_SHORT_ARY'		=> $js_weekday_month[2],
			'CTWC_MONTHS_ARY'			=> $js_weekday_month[3],
		]);
	}
	/*
	 * Preparing a user's data before displaying it in profile and memberlist
	 */
	public function memberlist_prepare_profile_data($event)
	{
		if ($event['data']['user_ctwc_disp_localtime'] && $this->config['ctwc_show_localtime_profil'] &&  $this->auth->acl_get('u_ctwc_cansee_localtime'))
		{
			$dateTimeZone = new \DateTimeZone($event['data']['user_timezone']);
			$dateTime	  = new \DateTime('now', $dateTimeZone);

			$dateformat = $this->user->data['user_dateformat'];
			$dateformat = $this->generate_datetime_str('ct', $dateformat);

			$user_local_time = $this->user->format_date(time(), $dateformat, true) . '{{' .$dateTimeZone->getOffset($dateTime) . '}}';

			$template_data = $event['template_data'];
			$template_data = array_merge($template_data, [
						'CTWC_USER_LOCAL_TIME' => $user_local_time,
					]);

			$event['template_data'] = $template_data;
		}
	}

	/**
	 * Modify the users' data displayed with their posts
	 */
	public function viewtopic_cache_user_data($event)
	{
		if ($event['row']['user_ctwc_disp_localtime'] && $this->config['ctwc_show_localtime_post'] && $this->auth->acl_get('u_ctwc_cansee_localtime'))
		{
			// Store user timezone in cache data
			$user_cache_data = $event['user_cache_data'];

			$dateTimeZone = new \DateTimeZone($event['row']['user_timezone']);
			$dateTime	  = new \DateTime('now', $dateTimeZone);

			$dateformat = $this->user->data['user_dateformat'];
			$dateformat = $this->generate_datetime_str('ct', $dateformat);

			$user_local_time = $this->user->format_date(time(), $dateformat, true) . '{{' . $dateTimeZone->getOffset($dateTime) . '}}';

			$user_cache_data = array_merge($user_cache_data, [
						'user_ctwc_local_time' => $user_local_time,
					]);

			$event['user_cache_data'] = $user_cache_data;
		}
	}

	/**
	 * Modify the posts template block
	 */
	public function viewtopic_modify_post_row($event)
	{
		$post_row	= $event['post_row'];
		$poster_id	= $post_row['POSTER_ID'];

		if (isset($event['user_cache'][$poster_id]['user_ctwc_local_time']))
		{
			$post_row = array_merge($post_row, [
						'CTWC_USER_LOCAL_TIME' => $event['user_cache'][$poster_id]['user_ctwc_local_time'],
					]);

			$event['post_row'] = $post_row;
		}
	}

	/*
	 * Changes the placeholder in the date/time string for the JavaScript clock class
	 */
	private function generate_datetime_str($replacement, $format)
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
