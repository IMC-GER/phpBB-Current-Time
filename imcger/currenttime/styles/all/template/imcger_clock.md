# JavaScript class imcgerClock

## Description
The imcgerClock can be used to output a time as a string. The timezone offset and an object can be passed as parameters. The object must be a string with the placeholders for the time output. The imcgerClock is also output to this object. The object is updated every second or minute.

The time zone offset is specified in seconds. The offset for GMT+1 corresponds to +3600. If the tzOffset is not specified, the browser's time zone is assumed.

In a string passed to the class, the following placeholders are replaced by the specified values.

|Placeh.| Description										| Displays   |
| ----- | ------------------------------------------------- | ---------- |
| \{a\}	| Lowercase Ante meridiem and Post meridiem			| am oder pm |
| \{A\}	| Uppercase Ante meridiem and Post meridiem			| AM oder PM |
| \{g\}	| 12-hour format of an hour without leading zeros	| 1 bis 12	 |
| \{G\}	| 24-hour format of an hour without leading zeros	| 0 bis 23	 |
| \{h\}	| 12-hour format of an hour with leading zeros		| 01 bis 12	 |
| \{H\}	| 24-hour format of an hour with leading zeros		| 00 bis 23	 |
| \{i\}	| Minutes with leading zeros						| 00 bis 59	 |
| \{s\}	| Seconds with leading zeros						| 00 bis 59	 |


## Description
### Call with specified object
#### HTML Code
```html
<p id="time">GMT: {g}:{i}:{s} {a}</p>
```

#### JavaScript Code
```javascript
let timerObj = document.getElementById('time');
let gmtTime  = new imcgerClock(timerObj, 0); // Output: GMT: 8:36:54 am
```
### Call without parameters
#### HTML Code
```html
<p id="time"></p>
```

#### JavaScript Code
```javascript
let timerObj = document.getElementById('time');
let gmtTime  = new imcgerClock();

gmtTime.timeString = "GMT-5:  {g}:{i}:{s} {a}";
gmtTime.tzOffset   = -18000;

timerObj.innerHTML = gmtTime.toString(); // Output: GMT-5: 3:31:29 am
```
