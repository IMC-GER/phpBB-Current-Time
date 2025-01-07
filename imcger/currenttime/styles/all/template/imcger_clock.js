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

	constructor(timeStringObject = null, tzOffset = null) {
		var thisObj = this;

		let date = new Date();
		this.#jsTzOffset = date.getTimezoneOffset() * -60;
		this.#tzOffset	 = tzOffset ?? this.#jsTzOffset;
		this.#timeString = '';

		if (!Number.isInteger(this.#tzOffset)) {
			throw new TypeError("tzOffset must be an integer");
		} else if ( this.#tzOffset < -43200 || this.#tzOffset > 50400) {
			throw new RangeError("The timezone offset must be between -43200 and 50400.");
		}

		if (timeStringObject != null && typeof timeStringObject === 'object') {
			this.#timeStringObject	= timeStringObject;
			this.#timeString		= timeStringObject.innerHTML;
		} else if (timeStringObject != null) {
			throw new TypeError("timeStringObject must be a object");
		}

		if (this.#timeString.search(/\{[gGhHisaA]\}/) >= 0) {
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

		if (this.#timeStringObject != null) {
			this.#timeStringObject.innerHTML = this.#getTimeString(date);
		}
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
											.replaceAll("{A}", d.getHours() < 12 ? 'AM' : 'PM')
											.replaceAll("{y}", d.getFullYear().toString().slice(-2))
											.replaceAll("{Y}", d.getFullYear())
											.replaceAll("{n}", d.getMonth() + 1)
											.replaceAll("{m}", this.#formatNumber(d.getMonth() + 1))
											.replaceAll("{M}", imcger.currentTime.monthShort[d.getMonth()])
											.replaceAll("{j}", d.getDate())
											.replaceAll("{d}", this.#formatNumber(d.getDate()))
											.replaceAll("{D}", imcger.currentTime.weekdayShort[d.getDay() || 7]);

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
		if (typeof value === 'string') {
			this.#timeString = value;
			this.#setTimeString();
		} else {
			throw new TypeError("timeString must be a string");
		}
	}

	get timeString() {
		return this.#timeString;
	}

	set tzOffset(value) {
		if (Number.isInteger(value)) {
			if ( value >= -43200 && value <= 50400) {
				this.#tzOffset = value;

				this.#setTimeString();
			} else {
				throw new RangeError("The timezone offset must be between -43200 and 50400.");
			}
		} else {
			throw new TypeError("tzOffset must be an integer");
		}
	}

	get tzOffset() {
		return this.#tzOffset;
	}
}
