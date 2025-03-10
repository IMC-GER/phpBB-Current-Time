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

class ctwc_general_settings
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\extension\manager */
	protected $ext_manager;

	/** @var string Custom form action */
	protected $u_action;

	/**
	 * Constructor
	 */
	public function __construct
	(
		\phpbb\config\config $config,
		\phpbb\request\request $request,
		\phpbb\user $user,
		\phpbb\template\template $template,
		\phpbb\language\language $language,
		\phpbb\extension\manager $ext_manager
	)
	{
		$this->config		= $config;
		$this->request		= $request;
		$this->user			= $user;
		$this->template		= $template;
		$this->language		= $language;
		$this->ext_manager	= $ext_manager;
	}

	/**
	 * Display the options a user can configure for this extension
	 */
	public function general_settings()
	{
		$this->language->add_lang(['ucp', ]);

		add_form_key('imcger\currenttime');

		// Is the form being submitted to us?
		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('imcger\currenttime'))
			{
				trigger_error($this->language->lang('FORM_INVALID') . '<br><br><a href="' . $this->u_action . '">&laquo; ' . $this->language->lang('BACK_TO_PREV') . '</a>', E_USER_WARNING);
			}

			$this->config->set('ctwc_show_currenttime', $this->request->variable('ctwc_show_currenttime', 0));
			$this->config->set('ctwc_show_worldclock', $this->request->variable('ctwc_show_worldclock', 0));
			$this->config->set('ctwc_show_localtime_profil', $this->request->variable('ctwc_show_localtime_profil', 0));
			$this->config->set('ctwc_show_localtime_post', $this->request->variable('ctwc_show_localtime_post', 0));

			trigger_error($this->language->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		$this->set_template_vars();
	}

	/**
	 * Set template variables
	 */
	protected function set_template_vars()
	{
		$metadata_manager = $this->ext_manager->create_extension_metadata_manager('imcger/currenttime');

		$this->template->assign_vars([
			'U_ACTION'					 => $this->u_action,
			'CTWC_NAME'					 => $metadata_manager->get_metadata('display-name'),
			'CTWC_EXT_VER'				 => $metadata_manager->get_metadata('version'),
			'CTWC_SHOW_CURRENTTIME'		 => (int) $this->config['ctwc_show_currenttime'],
			'CTWC_SHOW_WORLDCLOCK'		 => (int) $this->config['ctwc_show_worldclock'],
			'CTWC_SHOW_LOCALTIME_PROFIL' => (int) $this->config['ctwc_show_localtime_profil'],
			'CTWC_SHOW_LOCALTIME_POST'	 => (int) $this->config['ctwc_show_localtime_post'],
		]);
	}

	/**
	 * The selected action (ACP & UCP section).
	 */
	public function set_page_url($action)
	{
		$this->u_action = $action;
	}
}
