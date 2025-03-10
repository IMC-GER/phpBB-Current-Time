/**
 * JavaScript class IMCGerDate
 * JavaScript class IMCGerClock
 *
 * @copyright (c) 2024, Thorsten Ahlers
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

class IMCGerDate extends Date {

	getDayOfYear() {
		const firstDay = new Date(this.getFullYear(), 0, 1);
		const diff = this - firstDay + ((firstDay.getTimezoneOffset() - this.getTimezoneOffset()) * 60000);

		return Math.floor(diff / 86400000);
	}

	getWeekNo(startDay = 1) {
		if (startDay == 7) {
			return this.#getWeekUSA();
		}

		const curDate = new Date(this.getTime());
		curDate.setHours(0, 0, 0, 0);
		// startDay of the week must be converted to zero
		curDate.setDate(curDate.getDate() + 3 - (curDate.getDay() + 7 - startDay) % 7);

		const firstWeekDay = new Date(curDate.getFullYear(), 0, 4);
		firstWeekDay.setDate(firstWeekDay.getDate() + 3 - (firstWeekDay.getDay() + 7 - startDay) % 7);

		return (1 + Math.round((curDate.getTime() - firstWeekDay.getTime()) / 86400000  / 7));
	}

	#getWeekUSA() {
		const janFirst = new Date(this.getFullYear(), 0, 1);

		const firstSunday = new Date(this.getFullYear(), 0, 7);
		firstSunday.setHours(0, 0, 0, 0);
		firstSunday.setDate(firstSunday.getDate() - firstSunday.getDay());

		return (1 + Math.floor((this.getTime() - firstSunday.getTime()) / 86400000  / 7) + !!janFirst.getDay());
	}
}

class IMCGerClock {
	#timeStringObject;
	#timeString;
	#tzOffset;
	#jsTzOffset;

	constructor(timeStringObject = null, tzOffset = null) {
		var thisObj = this;

		const date = new IMCGerDate();
		this.#jsTzOffset = date.getTimezoneOffset() * -60;
		this.#tzOffset	 = tzOffset ?? this.#jsTzOffset;
		this.#timeString = '';

		if (timeStringObject != null && typeof timeStringObject === 'object') {
			this.#timeStringObject	= timeStringObject;
			this.#timeString		= timeStringObject.innerHTML;

			let strTimeOffsetAry = this.#timeString.match(/\{\{[-+]?\d+\}\}/);
			if (strTimeOffsetAry) {
				this.#timeString = this.#timeString.replace(strTimeOffsetAry[0], '');
				this.#tzOffset	 = parseInt(strTimeOffsetAry[0].substr(2, strTimeOffsetAry[0].length - 4));
			}
		} else if (timeStringObject != null) {
			throw new TypeError("timeStringObject must be a object");
		}

		if (!Number.isInteger(this.#tzOffset)) {
			throw new TypeError("tzOffset must be an integer");
		} else if ( this.#tzOffset < -43200 || this.#tzOffset > 50400) {
			throw new RangeError("The timezone offset must be between -43200 and 50400.");
		}

		if (this.#timeString.search(/\{[gGhHisaAyYnmMjdDzW]\}/) >= 0) {
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
		let date = new IMCGerDate();

		if (this.#tzOffset != this.#jsTzOffset) {
			date = this.#setTimeZone(date);
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
											.replaceAll("{F}", imcger.currentTime.month[d.getMonth()])
											.replaceAll("{jS}", this.#getOrdinalSuffix(d.getDate()))
											.replaceAll("{j}", d.getDate())
											.replaceAll("{d}", this.#formatNumber(d.getDate()))
											.replaceAll("{D}", imcger.currentTime.weekdayShort[d.getDay() || 7])
											.replaceAll("{l}", imcger.currentTime.weekday[d.getDay() || 7])
											.replaceAll("{z1}", d.getDayOfYear() + 1)
											.replaceAll("{z}", d.getDayOfYear())
											.replaceAll("{W0S}", this.#getOrdinalSuffix(d.getWeekNo(0)))
											.replaceAll("{W7S}", this.#getOrdinalSuffix(d.getWeekNo(7)))
											.replaceAll("{WS}",	this.#getOrdinalSuffix(d.getWeekNo()))
											.replaceAll("{W0}", d.getWeekNo(0))
											.replaceAll("{W7}", d.getWeekNo(7))
											.replaceAll("{W}", d.getWeekNo())
											.replaceAll("{O}", this.#tzOffsetHour(false))
											.replaceAll("{P}", this.#tzOffsetHour())
											.replaceAll("{S}", '');

		return newTimeString;
	}

	#getOrdinalSuffix(num) {
		const suffixes = ["th", "st", "nd", "rd"];
		const value = num % 100;

		return num + (suffixes[(value - 20) % 10] || suffixes[value] || suffixes[0]);
	}

	#formatNumber(num) {
		return num < 10 ? '0' + num : num;
	}

	#setTimeZone(date) {
		const utc = date.getTime() + (this.#jsTzOffset * -1000);
		return new IMCGerDate(utc + (this.#tzOffset * 1000));
	}

	#tzOffsetHour(colon = true) {
		const sign = this.#tzOffset >= 0 ? '+' : '-';
			 colon = colon ? ':' : '';

		const totalMinutes = Math.abs(this.#tzOffset) / 60;

		const hours = this.#formatNumber(Math.floor(totalMinutes / 60));
		const minutes = this.#formatNumber(Math.floor(totalMinutes % 60));

		return (sign + hours + colon + minutes);
	}

	toString() {
		const date = new IMCGerDate();

		if (this.#tzOffset != this.#jsTzOffset) {
			date = this.#setTimeZone(date);
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
