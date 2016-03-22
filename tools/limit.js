/*
 * idxCMS Flat Files Content Management System v4.1
 * Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
 */

var ns6 = document.getElementById && !document.all;
function restrictInput(maxlength, e, placeholder) {
    if (window.event && event.srcElement.value.length >= maxlength)
        return false;
    else if (e.target && e.target == eval(placeholder) && e.target.value.length >= maxlength) {
        var pressedkey = /[a-zA-Z0-9\.\,\/]/;
        if (pressedkey.test(String.fromCharCode(e.which)))
            e.stopPropagation();
    }
    return true;
}
function countLimit(maxlength, e, placeholder) {
    var form = eval(placeholder);
    var lengthleft = maxlength - form.value.length;
    var placeholderobj = document.all ? document.all[placeholder] : document.getElementById(placeholder);
    if (window.event || e.target && e.target == eval(placeholder)) {
        if (lengthleft < 0)
            form.value = form.value.substring(0, maxlength);
        placeholderobj.innerHTML = lengthleft;
    }
}
function displayLimit(name, id, limit) {
    var form = (id !== '') ? document.getElementById(id) : name;
    var limit_text = '<strong><span id="' + form.toString() + '">' + limit + '</span></strong>';
    if (document.all || ns6)
        document.write(limit_text);
    if (document.all) {
        eval(form).onkeypress = function() { return restrictInput(limit, event ,form); }
        eval(form).onkeyup    = function() { countLimit(limit, event, form); }
    } else if (ns6) {
        document.body.addEventListener('keypress', function(event) { restrictInput(limit, event, form); }, true);
        document.body.addEventListener('keyup', function(event) { countLimit(limit, event, form); }, true);
    }
}
