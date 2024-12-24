/**
 * JavaScript class imcgerClock
 *
 * @copyright (c) 2024, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

class imcgerClock {
	#timeStringObject;
	#timeString;
	#tzOffset;
	#jsTzOffset;
	#currentDay;

	constructor(timeStringObject = undefined, tzOffset = null) {
		var thisObj = this;

		let date = new Date();
		this.#jsTzOffset = date.getTimezoneOffset() * -60;
		this.#tzOffset	 = tzOffset ?? this.#jsTzOffset;
		this.#timeString = '';

		if (typeof timeStringObject !== 'undefined') {
			this.#timeStringObject	= timeStringObject;
			this.#timeString		= timeStringObject.innerHTML;
		}

		if (this.#timeString.search(/\{[gGhHisaA]\}/) >= 0) {
			this.#currentDay = this.#setTimeZone(date, this.#tzOffset).getDate();

			this.#setTimeString();

			if (this.#timeString.search("{s}") >= 0) {
				setInterval(() => thisObj.#setTimeString(), 1000);
			} else {
				setTimeout(() => {
					this.#setTimeString();
					setInterval(() => thisObj.#setTimeString(), 60000);
				}, (60 - date.getSeconds()) * 1000);
			}
		}
	}

	#setTimeString() {
		let date = new Date();

		if (this.#tzOffset != this.#jsTzOffset) {
			date = this.#setTimeZone(date, this.#tzOffset);
		}

		if (this.#currentDay != date.getDate()) {
			window.location.reload();
		}

		this.#timeStringObject.innerHTML = this.#getTimeString(date);
	}

	#getTimeString(d) {
		let hours12		  = d.getHours() % 12 || 12,
			newTimeString = this.#timeString.replaceAll("{g}", hours12)
											.replaceAll("{G}", d.getHours())
											.replaceAll("{h}", this.#formatNumber(hours12))
											.replaceAll("{H}", this.#formatNumber(d.getHours()))
											.replaceAll("{i}", this.#formatNumber(d.getMinutes()))
											.replaceAll("{s}", this.#formatNumber(d.getSeconds()))
											.replaceAll("{a}", d.getHours() < 12 ? 'am' : 'pm')
											.replaceAll("{A}", d.getHours() < 12 ? 'AM' : 'PM');

		return newTimeString;
	}

	#formatNumber(num) {
		return num < 10 ? '0' + num : num;
	}

	#setTimeZone(date, offset) {
		const utc = date.getTime() + (this.#jsTzOffset * -1000);
		return new Date(utc + (offset * 1000));
	}

	toString() {
		let date = new Date();

		if (this.#tzOffset != this.#jsTzOffset) {
			date = this.#setTimeZone(date, this.#tzOffset);
		}

		return this.#getTimeString(date);
	}

	set timeString(value) {
		if (typeof value === "string") {
			this.#timeString = value;
		} else {
			console.error('timeString is no string', value );
		}
	}

	get timeString() {
		return this.#timeString;
	}

	set tzOffset(value) {
		if (Number.isInteger(value)) {
			this.#tzOffset = value;
		} else {
			console.error('tzOffset is no integer', value);
		}

	}

	get tzOffset() {
		return this.#tzOffset;
	}
}
