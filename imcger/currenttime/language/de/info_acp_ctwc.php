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
	// Language pack author
	'CTWC_LANG_DESC'				=> 'Deutsch',
	'CTWC_LANG_EXT_VER' 			=> '0.8.0',
	'CTWC_LANG_AUTHOR' 				=> 'IMC-GER',

	'ACP_CT_MODULE_WORLDCLOCK'		 => 'Current Time / World Clock',
	'ACP_CT_SETTINGS'				 => 'Einstellungen',
	'ACP_CTWC_TITLE'				 => 'World Clock',
	'ACP_CTWC_DESC'					 => 'Um die „World Clock“ angezeigt zu bekommen, muss dem Benutzer in der Rechteverwaltung das Benutzerrecht zugewiesen werden. Auf dieser Seite werden die Grundeinstellungen für neue Benutzer vorgenommen. Diese Einstellungen können für alle Benutzer übernommen werden. Die Einstellungen für Gäste müssen <a href="%1s#ctwc_prefs">auf dieser Seite</a> vorgenommen werden.',

	// Permission
	'ACL_U_CTWC_ACCESS'		=> 'Kann die Weltzeituhr anzeigen',

	// Settings
	'CTWC_SETTINGS'			=> 'Einstellungen',
	'CTWC_WORLDCLOCK_1'		=> 'Weltzeituhr 1',
	'CTWC_WORLDCLOCK_2'		=> 'Weltzeituhr 2',
	'CTWC_WORLDCLOCK_3'		=> 'Weltzeituhr 3',
	'CTWC_WORLDCLOCK_4'		=> 'Weltzeituhr 4',
	'CTWC_WORLDCLOCK_5'		=> 'Weltzeituhr 5',
	'CTWC_WORLDCLOCK_6'		=> 'Weltzeituhr 6',
	'CTWC_WORLDCLOCK_EXP'	=> 'Du kannst die Weltzeituhr aktivieren und die Zeitzone und die Stadt innerhalb der Zeitzone einstellen. Der Name der Stadt wird vor der Zeitangabe angezeigt.',

	'CTWC_TZ_CITY'			 => 'Alternative Bezeichnung der Zeitzone',
	'CTWC_TZ_CITY_EXP'		 => 'Wenn dieses Feld leer bleibt, wird der Stadtname der Zeitzone angezeigt.',
	'CTWC_WCLOCK_FORMAT'	 => 'Format der Weltzeituhr',
	'CTWC_WCLOCK_FORMAT_EXP' => 'Diese Platzhalter „g G h H i s a A y Y n m M j d D z W F l O P S“ werden derzeit unterstützt.',

	'CTWC_WCLOCK_LINES'		=> 'Anzeige der Weltzeituhren in',
	'CTWC_SINGLELINE'		=> 'einer Zeile',
	'CTWC_TWOLINES'			=> 'zwei Zeilen',

	'CTWC_RESET_DEFAULT'		=> 'Benutzereinstellungen überschreiben',
	'CTWC_RESET_DEFAULT_EXP'	=> 'Wenn diese Option aktiviert ist, werden die Einstellungen aller Benutzer überschrieben. Ohne die Aktivierung werden nur die Standardwerte für neue Benutzer gesetzt.',
	'CTWC_RESET_ASK_BEFORE_EXP'	=> 'Diese Einstellung überschreibt die Einstellungen alle Benutzer mit den Standardwerten.<br><strong>Dieser Prozess kann nicht rückgängig gemacht werden!</strong>',

	'CUSTOM_DATEFORMAT'			=> 'Eigenes …',
	'ctwc_dateformats'	=> [
		'D j. M Y, H:i:s'		=> 'So 1. Jan 2025, 18:33:28',
		'j. M Y, H:i'			=> '1. Jan 2025, 13:57',
		'D j. M Y, H:i:s'		=> 'Mo 1. Jan 2025, 13:57:28',
		'j. F Y, H:i'			=> '1. Januar 2025, 13:57',
		'l j. F Y, H:i'			=> 'Montag 1. Januar 2025, 13:57',
		'd.m.Y, H:i:s'			=> '01.01.2025, 13:57:28',
		'D j. M, H:i:s \G\M\TP'	=> 'Mo 1. Jan, 01:08:35 GMT+11:00',
	],
]);
