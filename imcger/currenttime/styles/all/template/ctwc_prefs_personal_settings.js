/**
 * Current Time
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

(function($) { // Avoid conflicts with other libraries

	$('[name=ctwc_currtime_dateformats]').change(function() {
		$('[name=user_ctwc_currtime_format]').val($(this).val());
	});

})(jQuery); // Avoid conflicts with other libraries
