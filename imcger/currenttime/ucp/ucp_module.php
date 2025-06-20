<?php
/**
 * Current Time
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace imcger\currenttime\ucp;

class ucp_module
{
	public string $u_action;
	public string $tpl_name;
	public string $page_title;

	public function main(string $id, string $mode): void
	{
		global $phpbb_container;

		$language = $phpbb_container->get('language');

		switch ($mode)
		{
			case 'worldclock':
				// Load a template from adm/style for our ACP page
				$this->tpl_name = 'ucp_worldclock';

				// Set the page title for our ACP page
				$this->page_title = $language->lang('UCP_CT_MODULE_WORLDCLOCK');

				$controller = $phpbb_container->get('imcger.currenttime.acp_ucp.controller');

				// Make the $u_action url available in the admin controller
				$controller->set_page_url($this->u_action);

				// Load the display options handle in the ucp controller
				$controller->display_options('ucp');
			break;
		}
	}
}
