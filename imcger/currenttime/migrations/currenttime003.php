<?php
/**
 * Current Time
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace imcger\currenttime\migrations;

class currenttime003 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists(USERS_TABLE, 'user_ctwc_disp_localtime');
	}

	public static function depends_on()
	{
		return ['\imcger\currenttime\migrations\currenttime002',];
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				USERS_TABLE => [
					'user_ctwc_disp_localtime' => ['BOOL', 1],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns'	=> [
				USERS_TABLE => [
					'user_ctwc_disp_localtime',
				],
			],
		];
	}

	public function update_data()
	{
		return [
			['config.add', ['ctwc_show_currenttime'		 , 1]],
			['config.add', ['ctwc_show_worldclock'		 , 1]],
			['config.add', ['ctwc_show_localtime_profil' , 1]],
			['config.add', ['ctwc_show_localtime_post'	 , 0]],

			['module.add', [
					'acp',
					'ACP_CT_MODULE_WORLDCLOCK',
					[
						'module_basename'	=> '\imcger\currenttime\acp\acp_module',
						'modes'				=> ['worldclock_settings'],
					],
				]
			],

			['permission.add', ['u_ctwc_cansee_localtime']],
			['permission.permission_set', ['REGISTERED', 'u_ctwc_cansee_localtime', 'group']],
		];
	}
}
