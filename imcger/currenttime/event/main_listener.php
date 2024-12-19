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
			'/g/', // hour in 12-hour format; without leading zero	 1 to 12
			'/G/', // hour in 24-hour format; without leading zero	 0 to 23
			'/h/', // hour in 12-hour format; with leading zero		01 to 12
			'/H/', // hour in 24-hour format; with leading zero		00 to 23
			'/i/', // minutes; with leading zero					00 to 59
			'/s/', // seconds; with leading zero					00 to 59
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
