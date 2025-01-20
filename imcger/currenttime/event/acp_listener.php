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
 * Event listener
 */
class acp_listener implements EventSubscriberInterface
{
	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \imcger\currenttime\controller\ctwc_helper */
	protected $ctwc_helper;


	/**
	 * Constructor
	 */
	public function __construct
	(
		\phpbb\user $user,
		\phpbb\template\template $template,
		\phpbb\request\request $request,
		\imcger\currenttime\controller\ctwc_helper $ctwc_helper
	)
	{
		$this->user			= $user;
		$this->template 	= $template;
		$this->request		= $request;
		$this->ctwc_helper	= $ctwc_helper;
	}

	/**
	 * Get subscribed events
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.acp_users_prefs_modify_data'			=> 'acp_users_prefs_modify_data',
			'core.acp_users_prefs_modify_sql'			=> 'acp_users_prefs_modify_sql',
			'core.acp_users_prefs_modify_template_data' => 'acp_users_prefs_modify_template_data',
		];
	}

	/**
	 * Modify users preferences data
	 */
	public function acp_users_prefs_modify_data($event)
	{
		$user_setting = json_decode($event['user_row']['user_imcger_ct_data'], true);
		$currtime_format = !!$event['user_row']['user_ctwc_currtime_format'] ? $event['user_row']['user_ctwc_currtime_format'] : $this->user->date_format;

		$user_row = [];

		for ($i = 0; $i < 6; $i++)
		{
			$user_row += ['ctwc_wclock_disp_' . $i => $this->request->variable('ctwc_wclock_disp_' . $i, $user_setting[$i][0] ?? 0)];
			$user_row += ['ctwc_tz_' . $i => $this->request->variable('ctwc_tz_' . $i, $user_setting[$i][1] ?? '')];
			$user_row += ['ctwc_tz_city_' . $i => trim($this->request->variable('ctwc_tz_city_' . $i, $user_setting[$i][2] ?? '', true))];
		}

		$user_row += ['ctwc_wclock_format' => trim($this->request->variable('ctwc_wclock_format', $user_setting[6] ?? $this->user->date_format))];
		$user_row += ['ctwc_wclock_lines' => $this->request->variable('ctwc_wclock_lines', $user_setting[7] ?? 0)];
		$user_row += ['user_ctwc_currtime_format' => $this->request->variable('user_ctwc_currtime_format',  $currtime_format)];

		$event['user_row'] = array_merge($event['user_row'], $user_row);
	}

	/**
	 * Modify SQL query before users preferences are updated
	 */
	public function acp_users_prefs_modify_sql($event)
	{
		$user_setting = [];

		for ($i = 0; $i < 6; $i++)
		{
			$user_setting[$i][0] = $event['user_row']['ctwc_wclock_disp_' . $i];
			$user_setting[$i][1] = $event['user_row']['ctwc_tz_' . $i];
			$user_setting[$i][2] = $event['user_row']['ctwc_tz_city_' . $i];

			$user_setting[$i][0] = !$user_setting[$i][1] ? 0 : $user_setting[$i][0];
		}
		$user_setting[6] = $event['user_row']['ctwc_wclock_format'];
		$user_setting[7] = $event['user_row']['ctwc_wclock_lines'];

		$user_data_str = json_encode($user_setting, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

		$event['sql_ary'] = array_merge($event['sql_ary'], [
			'user_imcger_ct_data'		=> $user_data_str,
			'user_ctwc_currtime_format' => $event['user_row']['user_ctwc_currtime_format'],
		]);
	}

	/**
	 * Modify users preferences data before assigning it to the template
	 */
	public function acp_users_prefs_modify_template_data($event)
	{
		$user_auth	= new \phpbb\auth\auth();
		$userdata	= $user_auth->obtain_user_data($event['user_row']['user_id']);
		$user_auth->acl($userdata);

		$this->ctwc_helper->set_select_template_vars($event['user_row']['user_ctwc_currtime_format'], 'CTWC_CURRTIME_DATEFORMATS');
		$this->ctwc_helper->set_select_template_vars($event['user_row']['ctwc_wclock_format'], 'CTWC_WCLOCK_DATEFORMATS');

		$this->ctwc_helper->timezone_select('ctwc0', $event['user_row']['ctwc_tz_0']);
		$this->ctwc_helper->timezone_select('ctwc1', $event['user_row']['ctwc_tz_1']);
		$this->ctwc_helper->timezone_select('ctwc2', $event['user_row']['ctwc_tz_2']);
		$this->ctwc_helper->timezone_select('ctwc3', $event['user_row']['ctwc_tz_3']);
		$this->ctwc_helper->timezone_select('ctwc4', $event['user_row']['ctwc_tz_4']);
		$this->ctwc_helper->timezone_select('ctwc5', $event['user_row']['ctwc_tz_5']);

		$this->template->assign_vars([
			'CTWC_WCLOCK_DISP_0'	=> $event['user_row']['ctwc_wclock_disp_0'],
			'CTWC_WCLOCK_DISP_1'	=> $event['user_row']['ctwc_wclock_disp_1'],
			'CTWC_WCLOCK_DISP_2'	=> $event['user_row']['ctwc_wclock_disp_2'],
			'CTWC_WCLOCK_DISP_3'	=> $event['user_row']['ctwc_wclock_disp_3'],
			'CTWC_WCLOCK_DISP_4'	=> $event['user_row']['ctwc_wclock_disp_4'],
			'CTWC_WCLOCK_DISP_5'	=> $event['user_row']['ctwc_wclock_disp_5'],
			'CTWC_TZ_CITY_0'	 	=> $event['user_row']['ctwc_tz_city_0'],
			'CTWC_TZ_CITY_1'	 	=> $event['user_row']['ctwc_tz_city_1'],
			'CTWC_TZ_CITY_2'	 	=> $event['user_row']['ctwc_tz_city_2'],
			'CTWC_TZ_CITY_3'	 	=> $event['user_row']['ctwc_tz_city_3'],
			'CTWC_TZ_CITY_4'	 	=> $event['user_row']['ctwc_tz_city_4'],
			'CTWC_TZ_CITY_5'	 	=> $event['user_row']['ctwc_tz_city_5'],
			'CTWC_WCLOCK_FORMAT'	=> $event['user_row']['ctwc_wclock_format'],
			'CTWC_WCLOCK_LINES'		=> $this->ctwc_helper->select_struct((int) $event['user_row']['ctwc_wclock_lines'], [
				'CTWC_SINGLELINE'	=> 0,
				'CTWC_TWOLINES'		=> 1,
			]),
			'TOGGLECTRL_CT'				=> 'radio',
			'S_CTWC_ACP_USER_PREFS'		=> true,
			'S_CTWC_ACCESS'				=> $user_auth->acl_get('u_ctwc_access'),
			'USER_CTWC_CURRTIME_FORMAT'	=> $event['user_row']['user_ctwc_currtime_format'],
		]);
	}
}
