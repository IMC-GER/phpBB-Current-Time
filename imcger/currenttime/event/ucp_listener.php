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
 * UCP L<istener
 */
class ucp_listener implements EventSubscriberInterface
{
	protected object $auth;
	protected object $config;
	protected object $template;
	protected object $user;
	protected object $language;
	protected object $request;
	protected object $db;
	protected object $ctwc_helper;
	protected string $ctwc_data_table;

	/**
	 * Constructor
	 */
	public function __construct
	(
		\phpbb\auth\auth $auth,
		\phpbb\config\config $config,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\language\language $language,
		\phpbb\request\request $request,
		\phpbb\db\driver\driver_interface $db,
		\imcger\currenttime\controller\ctwc_helper $ctwc_helper,
		string $ctwc_data_table
	)
	{
		$this->auth				= $auth;
		$this->config			= $config;
		$this->template 		= $template;
		$this->user 			= $user;
		$this->language 		= $language;
		$this->request			= $request;
		$this->db				= $db;
		$this->ctwc_helper		= $ctwc_helper;
		$this->ctwc_data_table	= $ctwc_data_table;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'core.ucp_display_module_before'		=> 'ucp_display_module_before',
			'core.ucp_prefs_personal_data'			=> 'ucp_prefs_personal_data',
			'core.ucp_prefs_personal_update_data'	=> 'ucp_prefs_personal_update_data',
			'core.ucp_register_register_after'		=> 'ucp_register_set_data',
			'core.modify_module_row'				=> 'modify_module_row',
			'core.ucp_pm_view_message'				=> 'ucp_pm_view_message',
		];
	}

	/**
	 * Add language file
	 */
	public function ucp_display_module_before(): void
	{
		// Add language file in UCP);
		$this->language->add_lang(['info_acp_ctwc', ], 'imcger/currenttime');
	}

	/**
	 * Add UCP edit display options data before they are assigned to the template or submitted
	 */
	public function ucp_prefs_personal_data(object $event): void
	{
		$format = !!$this->user->data['user_ctwc_currtime_format'] ? $this->user->data['user_ctwc_currtime_format'] : $this->user->date_format;

		$event['data'] = array_merge($event['data'], [
			'user_ctwc_currtime_format'	=> $this->request->variable('user_ctwc_currtime_format', $format),
			'user_ctwc_disp_localtime'	=> $this->request->variable('user_ctwc_disp_localtime', $this->user->data['user_ctwc_disp_localtime']),
		]);

		if (!$event['submit'])
		{
			if ($this->config['ctwc_show_localtime_profil'] || $this->config['ctwc_show_localtime_post'])
			{
				$this->template->assign_vars([
					'TOGGLECTRL_CT'			   => 'radio',
					'USER_CTWC_DISP_LOCALTIME' => $event['data']['user_ctwc_disp_localtime'],
				]);
			}

			$this->ctwc_helper->set_select_template_vars($event['data']['user_ctwc_currtime_format'], 'CTWC_CURRTIME_DATEFORMATS');

			if ($this->config['ctwc_show_currenttime'])
			{
				$this->template->assign_vars([
					'USER_CTWC_CURRTIME_FORMAT'	=> $event['data']['user_ctwc_currtime_format'],
				]);
			}
		}
	}

	/**
	 * Update UCP edit display options data on form submit
	 */
	public function ucp_prefs_personal_update_data(object $event): void
	{
		$event['sql_ary'] = array_merge($event['sql_ary'], [
			'user_ctwc_currtime_format' => $event['data']['user_ctwc_currtime_format'],
			'user_ctwc_disp_localtime'	=> $event['data']['user_ctwc_disp_localtime'],
		]);
	}

	/**
	 * This event allows to modify parameters for building modules list
	 */
	public function ucp_register_set_data(object $event): void
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

	/**
	 * After new user registration, set user parameters to default;
	 */
	public function modify_module_row(object $event): void
	{
		if (!$this->config['ctwc_show_worldclock'] && $event['module_row']['langname'] == 'UCP_CT_MODULE_WORLDCLOCK')
		{
			$display = $event['module_row'];
			$display['display'] = (int) 0;
			$event['module_row'] = $display;
		}
	}

	/**
	 * Modify pm and sender data before it is assigned to the template
	 */
	public function ucp_pm_view_message(object $event): void
	{
		if ($event['user_info']['user_ctwc_disp_localtime'] && $this->config['ctwc_show_localtime_post'] && $this->auth->acl_get('u_ctwc_cansee_localtime'))
		{
			// Store user timezone in cache data
			$msg_data = $event['msg_data'];

			try
			{
				$dateTimeZone = new \DateTimeZone($event['user_info']['user_timezone']);
			}
			catch (\Exception $e)
			{
				// If the timezone the user has selected is invalid,
				// do not display the incorrect time in the autor profile.
				return;
			}

			$dateTime	  = new \DateTime('now', $dateTimeZone);

			$dateformat = $this->user->data['user_dateformat'];
			$dateformat = $this->ctwc_helper->generate_datetime_str('ct', $dateformat);

			$author_local_time = $this->user->format_date(time(), $dateformat, true) . '{{' . $dateTimeZone->getOffset($dateTime) . '}}';

			$msg_data = array_merge($msg_data, [
						'CTWC_AUTHOR_LOCAL_TIME' => $author_local_time,
					]);

			$event['msg_data'] = $msg_data;
		}
	}
}
