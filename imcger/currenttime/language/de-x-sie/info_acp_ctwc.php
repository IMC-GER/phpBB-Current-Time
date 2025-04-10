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
	'CTWC_LANG_DESC'			=> 'Deutsch',
	'CTWC_LANG_EXT_VER' 		=> '0.11.0',
	'CTWC_LANG_AUTHOR' 			=> 'IMC-GER',

	'ACP_CT_MODULE_WORLDCLOCK'	=> 'Current time / World clock',
	'ACP_CTWC_WORLDCL_SETTINGS'	=> 'Weltzeituhr Einstellungen',
	'ACP_CT_SETTINGS'			=> 'Allg. Einstellungen',
	'ACP_CTWC_GSET_EXP'			=> 'Hier können sie die Module dieser Extension aktiveren oder deaktivieren. Sollen diese nur für einzelne Benutzer oder Benutzergruppen deaktiviert werden müssen sie diese mit dem Rechtesystem des Forums tun.',
	'ACP_CTWC_TITLE'			=> 'Weltzeituhr',
	'ACP_CTWC_DESC'				=> 'Um die Weltzeituhr angezeigt zu bekommen, muss dem Benutzer in der Rechteverwaltung das Benutzerrecht zugewiesen werden. Auf dieser Seite werden die Grundeinstellungen für neue Benutzer vorgenommen. Diese Einstellungen können für alle Benutzer übernommen werden.',
	'ACP_CTWC_ANONYMOUS_DESC'	=> 'Die Einstellungen für Gäste müssen <a href="%1s#ctwc_prefs">auf dieser Seite</a> vorgenommen werden.',

	// Permission
	'ACL_U_CTWC_ACCESS'			  => 'Kann die Weltzeituhr sehen',
	'ACL_U_CTWC_CANSEE_LOCALTIME' => 'Kann die Ortszeit der Benutzer sehen',

	// General settings
	'CTWC_SHOW_CURRENTTIME'			=> 'Anzeige der aktuellen Uhrzeit auf allen Forumsseiten',
	'CTWC_SHOW_WORLDCLOCK'			=> 'Benutzer kann die Weltzeituhr anzeigen',
	'CTWC_SHOW_LOCALTIME_PROFIL'	=> 'Anzeige der Ortszeit des Benutzers in seinem Profil',
	'CTWC_SHOW_LOCALTIME_POST'		=> 'Anzeige der Ortszeit des Beitragsautors im Beitragsprofil',

	// Settings world clock
	'CTWC_SETTINGS'			=> 'Einstellungen',
	'CTWC_WORLDCLOCK_1'		=> 'Weltzeituhr 1',
	'CTWC_WORLDCLOCK_2'		=> 'Weltzeituhr 2',
	'CTWC_WORLDCLOCK_3'		=> 'Weltzeituhr 3',
	'CTWC_WORLDCLOCK_4'		=> 'Weltzeituhr 4',
	'CTWC_WORLDCLOCK_5'		=> 'Weltzeituhr 5',
	'CTWC_WORLDCLOCK_6'		=> 'Weltzeituhr 6',
	'CTWC_WORLDCLOCK_EXP'	=> 'Du kannst die Weltzeituhr aktivieren und die Zeitzone und die Stadt innerhalb der Zeitzone einstellen. Der Name der Stadt wird vor der Zeitangabe angezeigt.',
	'CTWC_SELECT_TZ_TIME'	=> 'Auswahl der Zeit in der Zeitzone',

	'CTWC_TZ_CITY'			=> 'Alternative Bezeichnung der Zeitzone',
	'CTWC_TZ_CITY_EXP'		=> 'Wenn dieses Feld leer bleibt, wird der Stadtname der Zeitzone angezeigt.',
	'CTWC_WCLOCK_FORMAT'	=> 'Format der Weltzeituhr',

	'CTWC_WCLOCK_LINES'		=> 'Anzeige der Weltzeituhren in',
	'CTWC_SINGLELINE'		=> 'einer Zeile',
	'CTWC_TWOLINES'			=> 'zwei Zeilen',

	'CTWC_RESET_DEFAULT'		=> 'Benutzereinstellungen überschreiben',
	'CTWC_RESET_DEFAULT_EXP'	=> 'Wenn diese Option aktiviert ist, werden die Einstellungen aller Benutzer überschrieben. Ohne die Aktivierung werden nur die Standardwerte für neue Benutzer gesetzt.',
	'CTWC_RESET_ASK_BEFORE_EXP'	=> 'Diese Einstellung überschreibt die Einstellungen alle Benutzer mit den Standardwerten.<br><strong>Dieser Prozess kann nicht rückgängig gemacht werden!</strong>',

	'ctwc_dateformats'	=> [
		'D j. M Y, H:i:s'		=> 'So 1. Jan 2025, 18:33:28',
		'j. M Y, H:i'			=> '1. Jan 2025, 13:57',
		'D j. M Y, H:i:s'		=> 'Mo 1. Jan 2025, 13:57:28',
		'j. F Y, H:i'			=> '1. Januar 2025, 13:57',
		'l j. F Y, H:i'			=> 'Montag 1. Januar 2025, 13:57',
		'd.m.Y, H:i:s'			=> '01.01.2025, 13:57:28',
		'D j. M, H:i:s \G\M\TP'	=> 'Mo 1. Jan, 01:08:35 GMT+01:00',
	],

	// User preferences
	'CTWC_CANSEE_LOCALTIME'			=> 'Benutzer können meine Ortszeit sehen',
	'CTWC_CURRTIME_FORMAT'			=> 'Datums-Format für die „Aktuelle Zeit“',
	'CTWC_CURRTIME_FORMAT_EXPLAIN'	=> 'Klicke hier um eine Erklärung der unterstüzten Formatierungsoptionen zu erhalten.',

	// Radio button label
	'ON'	=> 'An',
	'OFF'	=> 'Aus',

	// Description of the format string
	'CTWC_DATEFORMAT_TITLE'		=> 'Datumsformat für aktuelle Zeit und Weltzeituhr',
	'CTWC_DATEFORMAT_DECR1'		=> 'Das Format der ausgegebenen Datum/Zeit Zeichenkette kann mit den in der Tabelle beschriebenen Formatierungsoptionen frei definiert werden. Um zu verhindern, dass ein bekanntes Zeichen in der Formatzeichenkette interpretiert wird, kann es mit einem vorangestellten Backslash maskiert werden.',
	'CTWC_DATEFORMAT_DECR2'		=> 'Die Formatzeichenkette <code>\'D, d M Y H:i:s \G\M\TP\'</code> entspicht dieser Ausgabe <code>\'Mo, 20 Jan 2025 23:18:29 GMT+01:00\'</code>',
	'CTWC_DATEFORMAT_BACKLINK'	=> 'Zurück zu den Einstellungen',

	// Table head
	'CTWC_DATEFORMAT_OPTION'	=> 'Opt.',
	'CTWC_DATEFORMAT_DECR'		=> 'Beschreibung',
	'CTWC_DATEFORMAT_DISPLAY'	=> 'Anzeige',

	// Table body
	'CTWC_DATEFORMAT_TIME'	=> 'Zeit',
	'CTWC_DATEFORMAT_LOW_G'	=> 'Stunde im 12-Stunden-Format; ohne vorangestellte Null',
	'CTWC_DATEFORMAT_G'		=> 'Stunde im 24-Stunden-Format; ohne vorangestellte Null',
	'CTWC_DATEFORMAT_LOW_H'	=> 'Stunde im 12-Stunden-Format; mit vorangestellter Null',
	'CTWC_DATEFORMAT_H'		=> 'Stunde im 24-Stunden-Format; mit vorangestellter Null',
	'CTWC_DATEFORMAT_LOW_I'	=> 'Minuten; mit vorangestellter Null',
	'CTWC_DATEFORMAT_LOW_S'	=> 'Sekunden; mit vorangestellter Null',
	'CTWC_DATEFORMAT_LOW_A'	=> 'Ante meridiem und Post meridiem in Kleinbuchstaben',
	'CTWC_DATEFORMAT_A' 	=> 'Ante meridiem und Post meridiem in Großbuchstaben',

	'CTWC_DATEFORMAT_DATE'	=> 'Datum',
	'CTWC_DATEFORMAT_LOW_Y'	=> 'Darstellung einer Jahreszahl; zwei Ziffern',
	'CTWC_DATEFORMAT_Y'		=> 'Vollständige numerische Darstellung einer Jahreszahl',
	'CTWC_DATEFORMAT_LOW_N'	=> 'Numerische Darstellung eines Monats; ohne vorangestellte Null',
	'CTWC_DATEFORMAT_LOW_M'	=> 'Numerische Darstellung eines Monats; mit vorangestellter Null',
	'CTWC_DATEFORMAT_M'		=> 'Kurze textuelle Darstellung eines Monats; drei Buchstaben',
	'CTWC_DATEFORMAT_F'		=> 'Vollständige textuelle Darstellung eines Monats, z. B. Januar oder März',
	'CTWC_DATEFORMAT_LOW_J'	=> 'Tag des Monats; zwei Ziffern ohne vorangestellte Null',
	'CTWC_DATEFORMAT_JS'	=> 'Tag des Monats mit Suffix; zwei Ziffern ohne vorangestellte Null',
	'CTWC_DATEFORMAT_LOW_D'	=> 'Tag des Monats; zwei Ziffern mit vorangestellter Null',
	'CTWC_DATEFORMAT_D' 	=> 'Textuelle Darstellung eines Tages; zwei Buchstaben',
	'CTWC_DATEFORMAT_LOW_L'	=> 'Vollständige textuelle Darstellung eines Tages (kleines \'L\')',
	'CTWC_DATEFORMAT_Z1'	=> 'Tag des Jahres (beginnend bei 1)',
	'CTWC_DATEFORMAT_LOW_Z'	=> 'Tag des Jahres (beginnend bei 0)',

	'CTWC_DATEFORMAT_WEEK'	=> 'Woche',
	'CTWC_DATEFORMAT_W'		=> 'Wochennummer eines Jahres gemäß ISO-8601; die Wochen beginnen am Montag',
	'CTWC_DATEFORMAT_WS'	=> 'Wochennummer eines Jahres mit Suffix gemäß ISO-8601; die Wochen beginnen am Montag',
	'CTWC_DATEFORMAT_W0'	=> 'Wochennummer eines Jahres ähnlich ISO-8601; die Wochen beginnen am Sonntag',
	'CTWC_DATEFORMAT_W0S'	=> 'Wochennummer eines Jahres mit Suffix ähnlich ISO-8601; die Wochen beginnen am Sonntag',
	'CTWC_DATEFORMAT_W7'	=> 'Die Woche begint am Sonntag, die Zählung der ersten Woche begint am ersten Januar',
	'CTWC_DATEFORMAT_W7S'	=> 'Die Woche begint am Sonntag, die Zählung begint am ersten Januar, mit Suffix',

	'CTWC_DATEFORMAT_TZ'	=> 'Zeitzone',
	'CTWC_DATEFORMAT_O'		=> 'Differenz zur Greenwich-Zeit (GMT); ohne Doppelpunkt zwischen Stunden und Minuten',
	'CTWC_DATEFORMAT_P'		=> 'Differenz zur Greenwich-Zeit (GMT); mit Doppelpunkt zwischen Stunden und Minuten',
]);
