# JavaSkript Klasse imcgerClock

## Beschreibung
Mit imcgerClock kann eine Uhrzeit als String ausgegeben werden. Als Parameter können der Zeitzonenoffset
und ein Objekt angegeben werden. Das Objekt muss ein String mit den Platzhaltern für die Ausgabe der Zeit
sein. An dieses Objekt erfolgt auch die Ausgabe der imcgerClock. Diese wird jede Sekunde bzw. Minute
aktualisiert.
Der Zeitzonenoffset wird in Sekunden angegeben. Der Offset für GMT+1 entspricht +3600. Wird der
tzOffset nicht angegeben wird die Zeitzone des Browsers angenommen.

In einem an die Klasse übergebene String werden folgende Platzhaltern durch die angegebenen Werte ersetzt.

|Platzh.| Beschreibung											| Anzeige    |
| ----- | ----------------------------------------------------- | ---------- |
| \{a\}	| Ante meridiem und Post meridiem in Kleinbuchstaben	| am oder pm |
| \{A\}	| Ante meridiem und Post meridiem in Großbuchstaben 	| AM oder PM |
| \{g\}	| Stunde im 12-Stunden-Format; ohne vorangestellte Null | 1 bis 12	 |
| \{G\}	| Stunde im 24-Stunden-Format; ohne vorangestellte Null | 0 bis 23	 |
| \{h\}	| Stunde im 12-Stunden-Format; mit vorangestellter Null | 01 bis 12	 |
| \{H\}	| Stunde im 24-Stunden-Format; mit vorangestellter Null | 00 bis 23	 |
| \{i\}	| Minuten; mit vorangestellter Null 					| 00 bis 59	 |
| \{s\}	| Sekunden; mit vorangestellter Null					| 00 bis 59	 |


## Anwendung
### Aufruf mit angegeben Objekt
#### HTML Code
```html
<p id="time">GMT: {g}:{i}:{s} {a}</p>
```

#### JavaScript Code
```javascript
let timerObj = document.getElementById('time');
let gmtTime  = new imcgerClock(timerObj, 0);
```
### Aufruf ohne Parameter
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

timerObj.innerHTML = gmtTime.toString();
```
