/*jslint white: true, browser: true, undef: true, nomen: true, eqeqeq: true, plusplus: false, bitwise: true, regexp: true, strict: true, newcap: true, immed: true, maxerr: 14 */
/*global window: false, REDIPS: true */

/* enable strict mode */
"use strict";

// define redipsInit variable
var redipsInit;

// redips initialization
redipsInit = function () {
	// set REDIPS.drag reference
	var	rd = REDIPS.drag;
	// lib initialization
	rd.init();
	// set hover color for TR
	rd.hover.colorTr = '#bbb';
	// define color for empty row
	rd.style.rowEmptyColor = '#fff';
    // dragged elements can be placed only to the empty cells
	rd.event.rowClicked = function () {
		// find table
		var tbl = rd.findParent('TABLE', rd.obj);
		// if row belongs to the "sortable" table
		if (tbl.className.indexOf('sortable') > -1) {
			rd.enableTable(false, 'boxes');
			rd.enableTable(true, 'sortable');
		}
		// row belongs to the "boxes" table
		else {
			rd.enableTable(true, 'boxes');
			rd.enableTable(false, 'sortable');
		}
	};
};

function save() {
	var b = REDIPS.drag.scan_table("sortable");
	if (!b) {
		alert("Table is empty!");
	}
	var c = new String();
	c = '<input type="hidden" name="p" value="' + b + '" />';
	document.getElementById("result").innerHTML = c;
};

if (window.addEventListener) {
	window.addEventListener("load", redipsInit, false);
} else {
	if (window.attachEvent) {
		window.attachEvent("onload", redipsInit);
	}
};
