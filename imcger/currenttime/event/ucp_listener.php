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
 * Collapse Quote listener
 */
class ucp_listener implements EventSubscriberInterface
{
	/** @var config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \imcger\currenttime\controller\ctwc_helper */
	protected $ctwc_helper;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config				$config
	 * @param \phpbb\template\template			$template		Template object
	 * @param \phpbb\user						$user			User object
	 * @param \phpbb\language\language			$language		language object
	 * @param \phpbb\request\request			$request		Request objectt
	 * @param \phpbb\db\driver\driver_interface $db
	 *
	 * @return null
	 */
	public function __construct
	(
		\phpbb\config\config $config,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\language\language $language,
		\phpbb\request\request $request,
		\phpbb\db\driver\driver_interface $db,
		\imcger\currenttime\controller\ctwc_helper $ctwc_helper
	)
	{
		$this->config		= $config;
		$this->template 	= $template;
		$this->user 		= $user;
		$this->language 	= $language;
		$this->request		= $request;
		$this->db			= $db;
		$this->ctwc_helper	= $ctwc_helper;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @return array
	 * @static
	 * @access public
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.ucp_display_module_before'		=> 'ucp_display_module_before',
			'core.ucp_prefs_personal_data'			=> 'ucp_prefs_personal_data',
			'core.ucp_prefs_personal_update_data'	=> 'ucp_prefs_personal_update_data',
			'core.ucp_register_register_after'		=> 'ucp_register_set_data',
		];
	}

	/**
	 * Add collapsequote language file
	 *
	 * @param	object		$event	The event object
	 * @return	null
	 * @access	public
	 */
	public function ucp_display_module_before()
	{
		// Add language file in UCP);
		$this->language->add_lang(['info_acp_ctwc', 'info_ucp_ctwc', ], 'imcger/currenttime');
	}

	/**
	 * Add UCP edit display options data before they are assigned to the template or submitted
	 *
	 * @param	object		$event	The event object
	 * @return	null
	 * @access	public
	 */
	public function ucp_prefs_personal_data($event)
	{
		$format = !!$this->user->data['user_ctwc_currtime_format'] ? $this->user->data['user_ctwc_currtime_format'] : $this->user->date_format;

		$event['data'] = array_merge($event['data'], [
			'user_ctwc_currtime_format' => $this->request->variable('user_ctwc_currtime_format', $format),
		]);

		if (!$event['submit'])
		{
			$this->ctwc_helper->set_select_template_vars($event['data']['user_ctwc_currtime_format'], 'CTWC_CURRTIME_DATEFORMATS');

			$this->template->assign_vars([
				'TOGGLECTRL_CT'				=> 'radio',
				'USER_CTWC_CURRTIME_FORMAT'	=> $event['data']['user_ctwc_currtime_format'],
			]);
		}
	}

	/**
	 * Update UCP edit display options data on form submit
	 *
	 * @param	object		$event	The event object
	 * @return	null
	 * @access	public
	 */
	public function ucp_prefs_personal_update_data($event)
	{
		$event['sql_ary'] = array_merge($event['sql_ary'], [
			'user_ctwc_currtime_format' => $event['data']['user_ctwc_currtime_format'],
		]);
	}

	/**
	 * After new user registration, set user parameters to default;
	 *
	 * @param	$event
	 * @return	null
	 * @access	public
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
