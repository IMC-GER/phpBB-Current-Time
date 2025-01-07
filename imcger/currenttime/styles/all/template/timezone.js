/**
 * Current Time
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
 * @var	imcger	object for pphpBB.
 */
if (typeof imcger != 'object') {
	var imcger = {};
}

(function($) { // Avoid conflicts with other libraries

	'use strict';

	imcger.currenttime = {};

	/**
	 * Hide the optgroups that are not the selected timezone
	 *
	 * @param {object}	selectbox object
	 * @param {bool}	keepSelection Shall we keep the value selected,
	 * 					or shall the user be forced to repick one.
	 */
	imcger.currenttime.timezoneSwitchDate = function($box, keepSelection) {
		let $timezoneCopy = $('#ctwc_timezone_copy'),
			$tzDate		  = $box,
			$timezone	  = $tzDate.parent().next().children();

		if ($timezoneCopy.length === 0) {
			// We make a backup of the original dropdown, so we can remove optgroups
			// instead of setting display to none, because IE and chrome will not
			// hide options inside of optgroups and selects via css
			$timezone.clone()
				.attr('id', 'ctwc_timezone_copy')
				.css('display', 'none')
				.attr('name', 'tz_copy')
				.insertAfter('#ctwc_timezone');
		} else {
			// Copy the content of our backup, so we can remove all unneeded options
			$timezone.html($timezoneCopy.html());
		}

		let selectVal = $timezone.val();
		console.log(selectVal);

		if ($tzDate.val() !== '') {
			$timezone.children('optgroup').remove(':not([data-tz-value="' + $tzDate.val() + '"])');
		}

		var $tzOptions = $timezone.children('optgroup[data-tz-value="' + $tzDate.val() + '"]').children('option');

		if ($tzOptions.length === 1) {
			// If there is only one timezone for the selected date, we just select that automatically.
			$tzOptions.prop('selected', true);
			keepSelection = true;
		}

		if (typeof keepSelection !== 'undefined' && !keepSelection) {
			var $timezoneOptions = $timezone.find('optgroup option');
			if ($timezoneOptions.filter(':selected').length <= 0) {
				$timezoneOptions.filter(':first').prop('selected', true);
			}
		}
	};

	$('.ctwc-tz-date').change(function() {
		imcger.currenttime.timezoneSwitchDate($(this), false);
	});

	$('.ctwc-tz-date').each(function() {
		let $timezone  = $(this).parent().next().children(),
			tzsSetting = $timezone.val();

		$(this).trigger("change");
		$timezone.val(tzsSetting);
	});

})(jQuery); // Avoid conflicts with other libraries
