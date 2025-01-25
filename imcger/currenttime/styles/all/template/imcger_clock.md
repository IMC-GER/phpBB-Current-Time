# JavaScript class IMCGerClock

## Description
The IMCGerClock can be used to output a time as a string. The timezone offset and an object can be passed as parameters. The object must be a string with the placeholders for the time output. The IMCGerClock is also output to this object. The object is updated every second or minute.

The time zone offset is specified in seconds. The offset for GMT+1 corresponds to +3600. If the tzOffset is not specified, the browser's time zone is assumed.

In a string passed to the class, the following placeholders are replaced by the specified values.

|Placeh.| Description																| Displays 	|
| ----- | ------------------------------------------------------------------------- | ----------|
| \{a\}	| Lowercase Ante meridiem and Post meridiem									| am / pm	|
| \{A\}	| Uppercase Ante meridiem and Post meridiem									| AM / PM	|
| \{g\}	| 12-hour format of an hour without leading zeros							| 1 – 12	|
| \{G\}	| 24-hour format of an hour without leading zeros							| 0 – 23	|
| \{h\}	| 12-hour format of an hour with leading zeros								| 01 – 12	|
| \{H\}	| 24-hour format of an hour with leading zeros								| 00 – 23	|
| \{i\}	| Minutes with leading zeros												| 00 – 59	|
| \{s\}	| Seconds with leading zeros												| 00 – 59	|
| \{y\}	| A two digit representation of a year										|			|
| \{Y\}	| A full numeric representation of a year									|			|
| \{n\}	| Numeric representation of a month, without leading zeros					| 1 – 12	|
| \{m\}	| Numeric representation of a month, with leading zeros						| 01 – 12	|
| \{M\}	| A short textual representation of a month, three letters					| Jan – Dec	|
| \{jS\}| Day of the month with suffix and without leading zeros					| 1st – 31st|
| \{j\}	| Day of the month without leading zeros									| 1 – 31	|
| \{d\}	| Day of the month, 2 digits with leading zeros								| 01 – 31	|
| \{D\}	| A textual representation of a day, three letters							| Mon – Sun |
| \{z1\}| The day of the year (starting from 1)										| 1 – 366	|
| \{z\}	| The day of the year (starting from 0)										| 0 – 365	|
|\{W0S\}| Start of the week on Sunday counting similar to ISO with suffix			| 1st – 53rd|
|\{W7S\}| Weeks starting on Sunday, first week starts on 1st Januar with suffix		| 1st – 54th|
| \{WS\}| Week number of year, weeks starting on Monday with suffix					| 1st – 53rd|
| \{W0\}| Start of the week on Sunday counting similar to ISO						| 1 – 53	|
| \{W7\}| Weeks starting on Sunday, first week starts on 1st Januar					| 1 – 54	|
| \{W\}	| ISO 8601 week number of year, weeks starting on Monday					| 1 – 53	|
| \{O\}	| Difference to Greenwich time (GMT) without colon between hours and minutes| +0200		|
| \{P\}	| Difference to Greenwich time (GMT) with colon between hours and minutes 	| +02:00	|


## Description
### Call with specified object
#### HTML Code
```html
<p id="time">GMT: {g}:{i}:{s} {a}</p>
```

#### JavaScript Code
```javascript
let timerObj = document.getElementById('time');
let gmtTime  = new IMCGerClock(timerObj, 0); // Output: GMT: 8:36:54 am
```
### Call without parameters
#### HTML Code
```html
<p id="time"></p>
```

#### JavaScript Code
```javascript
let timerObj = document.getElementById('time');
let gmtTime  = new IMCGerClock();

gmtTime.timeString = "GMT-5:  {g}:{i}:{s} {a}";
gmtTime.tzOffset   = -18000;

timerObj.innerHTML = gmtTime.toString(); // Output: GMT-5: 3:31:29 am
```
