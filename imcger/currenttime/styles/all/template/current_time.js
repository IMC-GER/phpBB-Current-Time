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

imcger.currentTime = {};

class imcgerClockAnimate {

	// Initialize the form
	constructor(timeStringObject) {
		var thisObj = this;

		this.timeStringObject	= timeStringObject;
		this.timeString			= timeStringObject.innerHTML;

		if (this.timeString.search(/\{\{[gGhHis]\}\}/) < 0) {
			return;
		}

		let phpbbTimeOffset	 = this.timeString.match(/\{\{-?\d+\}\}/)[0];
		this.timeString		 = this.timeString.replace(phpbbTimeOffset, '');
		this.phpbbTimeOffset = parseInt(phpbbTimeOffset.substr(2, phpbbTimeOffset.length - 4));
		this.currentDay 	 = this.setTimeZone(new Date(), this.phpbbTimeOffset).getDate()

		this.setTimeString();

		if (this.timeString.search(/\{\{[s]\}\}/) >= 0) {
			setInterval(() => thisObj.setTimeString(), 1000);
		} else {
			setTimeout(() => {
				this.setTimeString();
				setInterval(() => thisObj.setTimeString(), 60000);
			}, (60 - d.getSeconds()) * 1000);
		}
	}

	setTimeString() {
		let date = new Date();

		if (this.phpbbTimeOffset != (date.getTimezoneOffset() * 60)) {
			date = this.setTimeZone(date, this.phpbbTimeOffset);
		}

		if (this.currentDay != date.getDate()) {
			window.location.reload();
		}

		this.timeStringObject.innerHTML = this.getTimeString(date);
	}

	getTimeString(d) {
		let hours12		  = d.getHours() % 12 || 12,
			newTimeString = this.timeString.replaceAll('\{\{g\}\}', hours12)
										   .replaceAll('\{\{G\}\}', this.formatNumber(hours12))
										   .replaceAll('\{\{h\}\}', d.getHours())
										   .replaceAll('\{\{H\}\}', this.formatNumber(d.getHours()))
										   .replaceAll('\{\{i\}\}', this.formatNumber(d.getMinutes()))
										   .replaceAll('\{\{s\}\}', this.formatNumber(d.getSeconds()));

		return newTimeString;
	}

	// Make 2-digit with leading zero
	formatNumber(num) {
		return num < 10 ? '0' + num : num;
	}

	setTimeZone(date, offset) {
		const utc = date.getTime() + (date.getTimezoneOffset() * 60000);
		return new Date(utc + (offset * 1000));
	}

}

// Initialize Clocks
imcger.currentTime.Clocks = document.getElementsByClassName('time');
imcger.currentTime.Clock  = [];

for (i = 0; i < imcger.currentTime.Clocks.length; i++) {
	imcger.currentTime.Clock[i] = new imcgerClockAnimate(imcger.currentTime.Clocks[i]);
}
