"use strict";
var redips_init;
redips_init = function() {
	var a = REDIPS.drag;
	a.init();
	a.hover.color_td = "#FFCFAE";
	a.hover.color_tr = "#9BB3DA";
	a.hover.border_td = "2px solid #32568E";
	a.hover.border_tr = "2px solid #32568E";
	a.row_empty_color = "#eee";
	a.myhandler_row_moved = function() {
		a.row_opacity(a.obj, 85);
		a.row_opacity(a.obj_old, 20, "White");
	};
};
function save() {
	var b = REDIPS.drag.scan_table("sortable");
	if (!b) {
		alert("Table is empty!");
	}
	var a = new String();
	a = '<input type="hidden" name="p" value="' + b + '" />';
	document.getElementById("result").innerHTML = a;
}
if (window.addEventListener) {
	window.addEventListener("load", redips_init, false);
} else {
	if (window.attachEvent) {
		window.attachEvent("onload", redips_init);
	}
};