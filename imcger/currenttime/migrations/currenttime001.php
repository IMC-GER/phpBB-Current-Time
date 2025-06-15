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

class currenttime001 extends \phpbb\db\migration\migration
{
	public function effectively_installed(): bool
	{
		return $this->db_tools->sql_column_exists(USERS_TABLE, 'user_imcger_ct_data');
	}

	public static function depends_on(): array
	{
		return ['\phpbb\db\migration\data\v330\v330',];
	}

	public function update_schema(): array
	{
		return [
			'add_columns' => [
				USERS_TABLE => [
					'user_imcger_ct_data' => ['VCHAR:400', ''],
				],
			],
			'add_tables'	=> [
				$this->table_prefix . 'ctwc_data'	=> [
					'COLUMNS'	=> [
						'data_id'		=> ['VCHAR:30', ''],
						'data_content'	=> ['VCHAR:400', ''],
					],
					'PRIMARY_KEY' => 'data_id',
				],
			],
		];
	}

	public function revert_schema(): array
	{
		return [
			'drop_tables'	=> [
				$this->table_prefix . 'ctwc_data',
			],
			'drop_columns'	=> [
				USERS_TABLE => [
					'user_imcger_ct_data',
				],
			],
		];
	}

	public function update_data(): array
	{

		return [
			['module.add', [
					'acp',
					'ACP_CAT_DOT_MODS',
					'ACP_CT_MODULE_WORLDCLOCK'
				]
			],
			['module.add', [
					'acp',
					'ACP_CT_MODULE_WORLDCLOCK',
					[
						'module_basename'	=> '\imcger\currenttime\acp\acp_module',
						'modes'				=> ['settings'],
					],
				]
			],

			['module.add', [
					'ucp',
					'UCP_PREFS',
					[
						'module_auth'		=> 'ext_imcger/currenttime && acl_u_ctwc_access',
						'module_basename'	=> '\imcger\currenttime\ucp\ucp_module',
						'module_langname'	=> 'UCP_CT_MODULE_WORLDCLOCK',
						'module_mode'		=> 'worldclock',
					],
				]
			],

			['permission.add', ['u_ctwc_access']],
			['permission.permission_set', ['GUESTS', 'u_ctwc_access', 'group']],
			['permission.permission_set', ['REGISTERED', 'u_ctwc_access', 'group']],
		];
	}
}
