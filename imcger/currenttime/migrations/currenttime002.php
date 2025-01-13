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

class currenttime002 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists(USERS_TABLE, 'user_ctwc_currtime_format');
	}

	public static function depends_on()
	{
		return ['\imcger\currenttime\migrations\currenttime001',];
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				USERS_TABLE => [
					'user_ctwc_currtime_format' => ['VCHAR:64', ''],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns' => [
				USERS_TABLE => [
					'user_ctwc_currtime_format',
				],
			],
		];
	}
}
