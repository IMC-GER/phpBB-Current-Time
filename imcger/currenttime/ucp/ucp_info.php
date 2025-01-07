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

class ucp_info {

	public function module()
	{
		return [
			'filename'	=> '\imcger\currenttime\ucp\ucp_module',
			'title'		=> 'UCP_CT_MODULE_WORLDCLOCK',
			'modes'		=> [
				'worldclock' => [
					'title'	=> 'UCP_CT_MODULE_WORLDCLOCK',
					'auth'	=> 'ext_imcger/currenttime && acl_u_ctwc_access',
					'cat'	=> ['UCP_PREFS',],
				],
			],
		];
	}
}
