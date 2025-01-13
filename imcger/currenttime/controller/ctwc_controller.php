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

class ctwc_controller
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \imcger\currenttime\controller\cp_controller */
	protected $cp_controller;

	/** @var \phpbb\extension\manager */
	protected $ext_manager;

	/** @var string Table name */
	protected $ctwc_data_table;

	/** @var string phpBB root path	*/
	protected $phpbb_root_path;

	/** @var string PHP extension */
	protected $phpEx;

	/** @var string Custom form action */
	protected $u_action;

	/** @var string source modul */
	protected $modul;

	/**
	 * Constructor
	 */
	public function __construct
	(
		\phpbb\db\driver\driver_interface $db,
		\phpbb\request\request $request,
		\phpbb\user $user,
		\phpbb\template\template $template,
		\phpbb\language\language $language,
		\imcger\currenttime\controller\ctwc_helper $ctwc_helper,
		\phpbb\extension\manager $ext_manager,
		$ctwc_data_table,
		$phpbb_root_path,
		$phpEx
	)
	{
		$this->db			= $db;
		$this->request		= $request;
		$this->user			= $user;
		$this->template		= $template;
		$this->language		= $language;
		$this->ctwc_helper	= $ctwc_helper;
		$this->ext_manager	= $ext_manager;
		$this->ctwc_data_table = $ctwc_data_table;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->phpEx		   = $phpEx;
		$this->modul		   = '';
	}

	/**
	 * Display the options a user can configure for this extension
	 *
	 * @return null
	 * @access public
	 */
	public function display_options($modul)
	{
		$this->modul = $modul;

		$this->language->add_lang(['ucp', ]);

		add_form_key('imcger\currenttime');

		$user_setting = null;

		// Is the form being submitted to us?
		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('imcger\currenttime'))
			{
				trigger_error($this->language->lang('FORM_INVALID') . '<br><br><a href="' . $this->u_action . '">&laquo; ' . $this->user->lang['BACK_TO_PREV'] . '</a>', E_USER_WARNING);
			}

			$user_setting = [];

			for ($i = 0; $i < 6; $i++)
			{
				$user_setting[$i][0] = $this->request->variable('ctwc_wclock_disp_' . $i, 0);
				$user_setting[$i][1] = $this->request->variable('ctwc_tz_' . $i, '');
				$user_setting[$i][2] = $this->request->variable('ctwc_tz_city_' . $i, '', true);

				$user_setting[$i][0] = !$user_setting[$i][1] ? 0 : $user_setting[$i][0];
			}
			$user_setting[6] = $this->request->variable('ctwc_wclock_format', 'H:i:s');
			$user_setting[7] = $this->request->variable('ctwc_wclock_lines', 0);

			$user_data_str = json_encode($user_setting, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
			$user_data_str = $this->db->sql_escape($user_data_str);

			if ($this->modul == 'acp')
			{
				// save default data
				$sql = 'SELECT data_content FROM ' . $this->ctwc_data_table . ' WHERE data_id = "default"';
				$result = $this->db->sql_query_limit($sql, 1);

				if ($this->db->sql_fetchrow($result))
				{
					$sql = 'UPDATE ' . $this->ctwc_data_table . '
							SET data_content=\'' . $user_data_str . '\'
							WHERE data_id = "default"';
				}
				else
				{
					$sql = 'INSERT INTO ' . $this->ctwc_data_table . ' ' .
							$this->db->sql_build_array('INSERT', ['data_id' => 'default', 'data_content' => $user_data_str]);
				}
				$this->db->sql_freeresult($result);

				$this->db->sql_query($sql);

				// overwrite settings for all user
				if ($this->request->variable('ctwc_reset_default', 0))
				{
					$sql = 'UPDATE ' . USERS_TABLE . '
							SET user_imcger_ct_data=\'' . $user_data_str . '\'';

					$this->db->sql_query($sql);
				}
			}
			else if ($this->modul == 'ucp')
			{
				$sql = 'UPDATE ' . USERS_TABLE . '
						SET user_imcger_ct_data=\'' . $user_data_str . '\'
						WHERE user_id = ' . (int) $this->user->data['user_id'];

				$this->db->sql_query($sql);

				// Important hack to display the changes of the world clocks
				redirect(append_sid($this->u_action));
			}
		}

		$this->set_template_vars($user_setting);
	}

	/**
	 * Set template variables
	 */
	protected function set_template_vars(?array $user_setting = null)
	{
		if (!isset($user_setting) && $this->modul == 'acp')
		{
			$sql = 'SELECT data_content FROM ' . $this->ctwc_data_table . ' WHERE data_id = "default"';
			$result = $this->db->sql_query_limit($sql, 1);

			$user_setting = json_decode((string) $this->db->sql_fetchfield('data_content'),  true);
			$this->db->sql_freeresult($result);
		}
		else if (!isset($user_setting) && $this->modul == 'ucp')
		{
			$user_setting = json_decode($this->user->data['user_imcger_ct_data'], true);
		}

		$this->ctwc_helper->timezone_select('ctwc0', $user_setting[0][1] ?? '');
		$this->ctwc_helper->timezone_select('ctwc1', $user_setting[1][1] ?? '');
		$this->ctwc_helper->timezone_select('ctwc2', $user_setting[2][1] ?? '');
		$this->ctwc_helper->timezone_select('ctwc3', $user_setting[3][1] ?? '');
		$this->ctwc_helper->timezone_select('ctwc4', $user_setting[4][1] ?? '');
		$this->ctwc_helper->timezone_select('ctwc5', $user_setting[5][1] ?? '');

		$metadata_manager = $this->ext_manager->create_extension_metadata_manager('imcger/currenttime');
		$u_ctwc_user_set  = append_sid($this->phpbb_root_path . 'adm/index.' . $this->phpEx, 'i=users&amp;u=1&amp;mode=prefs');

		$this->template->assign_vars([
			'TOGGLECTRL_CT'		 => $this->modul == 'ucp' ? 'radio' : null,
			'U_ACTION'			 => $this->u_action,
			'ACP_CTWC_DESC'		 => $this->language->lang('ACP_CTWC_DESC', $u_ctwc_user_set),
			'CTWC_NAME'			 => $metadata_manager->get_metadata('display-name'),
			'CTWC_EXT_VER'		 => $metadata_manager->get_metadata('version'),
			'CTWC_WCLOCK_DISP_0' => $user_setting[0][0] ?? 0,
			'CTWC_WCLOCK_DISP_1' => $user_setting[1][0] ?? 0,
			'CTWC_WCLOCK_DISP_2' => $user_setting[2][0] ?? 0,
			'CTWC_WCLOCK_DISP_3' => $user_setting[3][0] ?? 0,
			'CTWC_WCLOCK_DISP_4' => $user_setting[4][0] ?? 0,
			'CTWC_WCLOCK_DISP_5' => $user_setting[5][0] ?? 0,
			'CTWC_TZ_CITY_0'	 => $user_setting[0][2] ?? '',
			'CTWC_TZ_CITY_1'	 => $user_setting[1][2] ?? '',
			'CTWC_TZ_CITY_2'	 => $user_setting[2][2] ?? '',
			'CTWC_TZ_CITY_3'	 => $user_setting[3][2] ?? '',
			'CTWC_TZ_CITY_4'	 => $user_setting[4][2] ?? '',
			'CTWC_TZ_CITY_5'	 => $user_setting[5][2] ?? '',
			'CTWC_WCLOCK_FORMAT' => $user_setting[6] ?? 'H:i:s',
			'CTWC_WCLOCK_LINES'		=> $this->ctwc_helper->select_struct($user_setting[7] ?? 0, [
				'CTWC_SINGLELINE'	=> 0,
				'CTWC_TWOLINES'		=> 1,
			]),
		]);
	}

	/**
	 * @param  string $action The selected action (ACP & UCP section).
	 * @return mixed
	 */
	public function set_page_url($action)
	{
		$this->u_action = $action;
	}
}
