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
	protected object $auth;
	protected object $user;
	protected object $config;
	protected object $template;
	protected object $language;
	protected object $ctwc_helper;


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
		\imcger\currenttime\controller\ctwc_helper $ctwc_helper
	)
	{
		$this->auth				= $auth;
		$this->user				= $user;
		$this->config			= $config;
		$this->template			= $template;
		$this->language 		= $language;
		$this->ctwc_helper		= $ctwc_helper;
	}

	/**
	 * Allows to specify additional permission categories, types and permissions
	 */
	public static function getSubscribedEvents(): array
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
	public function add_languages(): void
	{
		$this->language->add_lang(['ctwc_common', ], 'imcger/currenttime');
	}

	/**
	 * Execute code and/or overwrite _common_ template variables after they have been assigned.
	 */
	public function add_permissions(object $event): void
	{
		$event->update_subarray( 'permissions', 'u_ctwc_access', [ 'lang' => 'ACL_U_CTWC_ACCESS', 'cat' => 'misc' ] );
		$event->update_subarray( 'permissions', 'u_ctwc_cansee_localtime', [ 'lang' => 'ACL_U_CTWC_CANSEE_LOCALTIME', 'cat' => 'misc' ] );
	}

	/**
	 * Execute code and/or overwrite _common_ template variables after they have been assigned.
	 */
	public function page_header_after(): void
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
					try
					{
						$dateTimeZone = new \DateTimeZone($clock_data[1]);
						$fall_back_tz = null;
					}
					catch (\Exception $e)
					{
						// If the timezone is invalid, fall back to UTC.
						$dateTimeZone = new \DateTimeZone('UTC');
						$fall_back_tz = 'UTC';
					}

					$dateTime	  = new \DateTime('now', $dateTimeZone);

					$timeOffset = $dateTimeZone->getOffset($dateTime);
					$tz_local_ary = explode('/', $this->language->lang(['timezones', $clock_data[1]]));
					$city		= !!$clock_data[2] ? $clock_data[2] : end($tz_local_ary);

					$this->template->assign_block_vars('ctwc_clock', [
						'CITY'		 => $fall_back_tz ?? $city,
						'TIMEOFFSET' => $timeOffset,
					]);
				}
			}

			$format = isset($wc_date_ary[6]) ? $wc_date_ary[6] : $this->user->date_format;
			$date_str = $this->ctwc_helper->generate_datetime_str('wc', $format);
			$date_str = preg_replace('/(?<!\\\\)\\\\/', '', $date_str);

			$this->template->assign_vars([
				'CTWC_DATESTRING'	=> $date_str,
				'CTWC_LINES'		=> $wc_date_ary[7] ?? 0,
			]);
		}

		$datetime  = $this->user->create_datetime();
		$tz_offset = $datetime->getOffset();

		$dateformat = !!$this->user->data['user_ctwc_currtime_format'] ? $this->user->data['user_ctwc_currtime_format'] : $this->user->data['user_dateformat'];
		$dateformat = $this->ctwc_helper->generate_datetime_str('ct', $dateformat);

		if ($this->config['ctwc_show_currenttime'])
		{
			$currenttime = $this->language->lang('CURRENT_TIME', $this->user->format_date(time(), $dateformat, false));

			$this->template->assign_vars([
				'CURRENT_TIME' => $currenttime,
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
	public function memberlist_prepare_profile_data(object $event): void
	{
		if ($event['data']['user_ctwc_disp_localtime'] && $this->config['ctwc_show_localtime_profil'] &&  $this->auth->acl_get('u_ctwc_cansee_localtime'))
		{
			try
			{
				$dateTimeZone = new \DateTimeZone($event['data']['user_timezone']);
			}
			catch (\Exception $e)
			{
				// If the timezone the user has selected is invalid,
				// do not display the incorrect time in the user profile.
				return;
			}

			$dateTime	  = new \DateTime('now', $dateTimeZone);

			$dateformat = $this->user->data['user_dateformat'];
			$dateformat = $this->ctwc_helper->generate_datetime_str('ct', $dateformat);

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
	public function viewtopic_cache_user_data(object $event): void
	{
		if ($event['row']['user_ctwc_disp_localtime'] && $this->config['ctwc_show_localtime_post'] && $this->auth->acl_get('u_ctwc_cansee_localtime'))
		{
			// Store user timezone in cache data
			$user_cache_data = $event['user_cache_data'];

			try
			{
				$dateTimeZone = new \DateTimeZone($event['row']['user_timezone']);
			}
			catch (\Exception $e)
			{
				// If the timezone the user has selected is invalid,
				// do not display the incorrect time in the post profile.
				return;
			}

			$dateTime	  = new \DateTime('now', $dateTimeZone);

			$dateformat = $this->user->data['user_dateformat'];
			$dateformat = $this->ctwc_helper->generate_datetime_str('ct', $dateformat);

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
	public function viewtopic_modify_post_row(object $event): void
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
}
