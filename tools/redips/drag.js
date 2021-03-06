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
	// set hover color for TR TD
	rd.hover.color_td = "#FFCFAE";
	rd.hover.color_tr = "#9BB3DA";
    rd.hover.border_td = "2px solid #32568E";
	rd.hover.border_tr = "2px solid #32568E";
	// define color for empty row
	rd.row_empty_color = "#eee";
    // dragged elements can be placed only to the empty cells
	rd.myhandler_row_moved = function() {
		rd.row_opacity(rd.obj, 85);
		rd.row_opacity(rd.obj_old, 20, "White");
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
