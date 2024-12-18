/**
 * Current Time
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

	class imcgerClockAnimate {

		// Initialize the form
		constructor(timeStringObject) {
			const d = new Date();
			var thisObj = this;

			this.timeStringObject	= timeStringObject;
			this.timeString			= timeStringObject.innerHTML;
			this.currenttDay 		= d.getDate();

			if (this.timeString.search(/\{\{[ghis]\}\}/i) < 0) {
				return;
			}

			this.setTimeString();

			if (this.timeString.search(/\{\{[s]\}\}/i) >= 0) {
				setInterval(() => thisObj.setTimeString(), 1000);
			} else {
				setTimeout(() => {
					this.setTimeString();
					setInterval(() => thisObj.setTimeString(), 60000);
				}, (60 - d.getSeconds()) * 1000);

			}
		}

		setTimeString() {
			const d = new Date();

			if (this.currenttDay != d.getDate()) {
				window.location.reload();
			}

			this.timeStringObject.innerHTML = this.getTimeString(this.timeString);
		}

		getTimeString(newTimeString) {
			const d = new Date();
			let hours12 = (d.getHours() + 24) % 12 || 12;

			newTimeString = newTimeString.replaceAll('\{\{g\}\}', hours12).replaceAll('\{\{G\}\}', this.formatNumber(hours12)).replaceAll('\{\{h\}\}', d.getHours()).replaceAll('\{\{H\}\}', this.formatNumber(d.getHours())).replaceAll('\{\{i\}\}', this.formatNumber(d.getMinutes())).replaceAll('\{\{s\}\}', this.formatNumber(d.getSeconds()));

			return newTimeString;
		}

		// Make 2-digit with leading zero
		formatNumber(num) {
			return num < 10 ? '0' + num : num.toString();
		}
	}

	// Initialize Clocks
	var imcgerClocks = document.getElementsByClassName('time'),
		imcgerClock  = [];

	for (i = 0; i < imcgerClocks.length; i++) {
		imcgerClock[i] = new imcgerClockAnimate(imcgerClocks[i]);
	}
