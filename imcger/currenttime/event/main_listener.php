<?php
/**
 * Current Time
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace imcger\style\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Style listener
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;


	public function __construct
	(
		\phpbb\user $user,
		\phpbb\template\template $template,
		\phpbb\language\language $language
	)
	{
		$this->user		= $user;
		$this->template	= $template;
		$this->language = $language;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.page_header_after'			=>	'page_header_after',
		];
	}

	public function page_header_after()
	{
		$dateformat = $this->user->data['user_dateformat'];

		$pattern = [
			'/g/', // Stunde im 12-Stunden-Format; ohne vorangestellte Null	1 bis 12
			'/G/', // Stunde im 24-Stunden-Format; ohne vorangestellte Null	0 bis 23
			'/h/', // Stunde im 12-Stunden-Format; mit vorangestellter Null	01 bis 12
			'/H/', // Stunde im 24-Stunden-Format; mit vorangestellter Null	00 bis 23
			'/i/', // Minuten; mit vorangestellter Null						00 bis 59
			'/s/', // Sekunden; mit vorangestellter Null					00 bis 59
		];

		$replacement = [
			'{{\g}}',
			'{{\G}}',
			'{{\h}}',
			'{{\H}}',
			'{{\i}}',
			'{{\s}}',
		];

		$dateformat = preg_replace($pattern, $replacement, $dateformat);

		$this->template->assign_vars([
			'CURRENT_TIME'	=> sprintf($this->user->lang['CURRENT_TIME'], $this->user->format_date(time(), $dateformat, false)),
		]);
	}
}
