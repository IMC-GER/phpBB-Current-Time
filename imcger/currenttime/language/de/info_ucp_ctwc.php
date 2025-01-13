<?php
/**
 * Current Time
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (! defined( 'IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [
	'CTWC_CURRTIME_FORMAT'			=> 'Datums-Format für die „Aktuelle Zeit“',
	'CTWC_CURRTIME_FORMAT_EXPLAIN'	=> 'Diese Platzhalter „g G h H i s a A y Y n m M j d D z W“ werden derzeit unterstützt.',
	'UCP_CT_MODULE_WORLDCLOCK'		=> 'Weltzeituhr konfiguieren',

	'ON'	=> 'An',
	'OFF'	=> 'Aus',
]);
