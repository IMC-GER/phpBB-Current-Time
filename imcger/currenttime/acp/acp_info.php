<?php
/**
 * Current Time
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace imcger\currenttime\acp;

class acp_info {

	public function module(): array
	{
		return [
			'filename'	=> '\imcger\currenttime\acp\acp_module',
			'title'		=> 'ACP_CT_MODULE_WORLDCLOCK',
			'modes'		=> [
				'settings' => [
					'title'	=> 'ACP_CT_SETTINGS',
					'auth'	=> 'ext_imcger/currenttime && acl_a_board',
					'cat'	=> ['ACP_CT_MODULE_WORLDCLOCK', ],
				],
				'worldclock_settings' => [
					'title'	=> 'ACP_CTWC_WORLDCL_SETTINGS',
					'auth'	=> 'ext_imcger/currenttime && acl_a_board',
					'cat'	=> ['ACP_CT_MODULE_WORLDCLOCK', ],
				],
			],
		];
	}
}
