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
	'CTWC_CURRTIME_FORMAT_EXPLAIN'	=> 'Klicke hier um eine Erklärung der Formatierungsoptionen zu erhalten.',
	'UCP_CT_MODULE_WORLDCLOCK'		=> 'Weltzeituhr konfiguieren',

	'ON'	=> 'An',
	'OFF'	=> 'Aus',

	'CTWC_DATEFORMAT_TITLE'	=> 'Datumsformat für aktuelle Zeit und Weltzeituhr',
	'CTWC_DATEFORMAT_DECR1'	=> 'Das Format der ausgegebenen Datum/Zeit Zeichenkette kann mit den in der Tabelle beschriebenen Formatierungsoptionen frei definiert werden. Um zu verhindern, dass ein bekanntes Zeichen in der Formatzeichenkette interpretiert wird, kann es mit einem vorangestellten Backslash maskiert werden.',
	'CTWC_DATEFORMAT_DECR2'	=> 'Die Formatzeichenkette <code>\'D, d M Y H:i:s \G\M\TP\'</code> entspicht dieser Ausgabe <code>\'Mo, 20 Jan 2025 23:18:29 GMT+02:00\'</code>',

	'CTWC_DATEFORMAT_OPTION'	=> 'Opt.',
	'CTWC_DATEFORMAT_DECR'		=> 'Beschreibung',
	'CTWC_DATEFORMAT_DISPLAY'	=> 'Anzeige',

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

	'CTWC_DATEFORMAT_BACKLINK'	=> 'Zurück zu den Einstellungen',
]);
