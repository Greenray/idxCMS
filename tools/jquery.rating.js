/*
 * Rate system for articles, catalog and gallery items
 * @version   v1.0.3 : 2008/05/06
 * @author    http://www.nofunc.com/AJAX_Star_Rating/
 */
function _$(a, b) { return((typeof (b) === "object" ? b : document).getElementById(a)); }
function _$S(a)   { return((typeof (a) === "object" ? a : _$(a)).style); }
function agent(a) { return(Math.max(navigator.userAgent.toLowerCase().indexOf(a), 0)); }
function abPos(c) {
    var a = (typeof (c) === "object" ? c : _$(c));
    var b = { X: 0, Y: 0 };
    while (a !== null) {
        b.X += a.offsetLeft;
        b.Y += a.offsetTop;
        a = a.offsetParent;
    }
    return(b);
}
function XY(b, a) {
    if (!b) { b = window.event; }
    var c = agent("msie") ? { X: b.clientX + document.documentElement.scrollLeft, Y: b.clientY + document.documentElement.scrollTop } : { X: b.pageX, Y: b.pageY };
    return (a ? c[a] : c);
}
function handleError(a) { alert("Error: " + a); }
var star = {};
star.mouse = function (a, b) {
    if (star.stop || isNaN(star.stop)) {
        star.stop = 0;
        document.onmousemove = function (h) {
            var i = star.num;
            var g = abPos(_$("star" + i));
            var d = XY(h);
            var f = d.X - g.X;
            var c = d.Y - g.Y;
            star.num = b.id.substr(4);
            if (f < 1 || f > 84 || c < 0 || c > 19) {
                star.stop = 1;
                star.revert();
            } else {
                _$S("starCur" + i).width = f + "px";
                _$S("starUser" + i).color = "";
                _$("starUser" + i).innerHTML = Math.round(f / 84 * 100);
            }
        };
    }
};
star.update = function (e, b) {
    var f = star.num;
    var c = parseInt(_$("starUser" + f).innerHTML);
    f = e.id.substr(4);
    _$("starCur" + f).title = c;
    var d = new XMLHttpRequest();
    d.open("GET", "./?module=rate&id=" + b + "&val=" + c, true);
    d.onreadystatechange = function () {
        if (d.readyState !== 4) { return; }
        clearTimeout(a);
        if (d.status === 200) {
            var g = new Array();
            g = d.responseText.split("$", 2);
            _$("rate" + f).innerHTML = g[0];
        } else {
            handleError(d.statusText);
        }
    };
    d.send(null);
    var a = setTimeout(function () {
        d.abort();
        handleError("Time over");
    }, 10000);
};
star.revert = function () {
    var b = star.num;
    var a = parseInt(_$("starCur" + b).title);
    _$S("starCur" + b).width = Math.round(a * 84 / 100) + "px";
    _$("starUser" + b).innerHTML = (a > 0 ? Math.round(a) : 0);
    _$("starUser" + b).style.color = "";
    document.onmousemove = "";
};
star.num = 0;

/**
 * Rates comments and replies.
 *
 * @param   string b Action: up|down rate
 * @param   string c User name
 * @param   string f Rate ID
 * @returns integer  Rate value
 */
function Rate(b, c, f) {
    var g = f.id.substr(6);
    var d = _$("rate" + g);
    if (d.value !== undefined) {
        var val = d.value;
    } else {
        if (d.innerText !== undefined) {
            val = d.innerText;
        } else {
            val = d.textContent;
        }
    }
    var e = new XMLHttpRequest();
    e.open("GET", "./?module=rate&user=" + c + "&act=" + b + "&id=" + g + "&rate=" + val, true);
    e.onreadystatechange = function () {
        if (e.readyState !== 4) { return; }
        clearTimeout(a);
        if (e.status === 200) {
            var h = new Array();
            var s = _$("stars" + g);
            h = e.responseText.split("$", 3);
            d.innerHTML = h[0];
            d.style = h[1];
            if (h[2] !== '') s.innerHTML = h[2];
        } else {
            handleError(e.statusText);
        }
    };
    e.send(null);
    var a = setTimeout(function () {
        e.abort();
        handleError("Time over");
    }, 10000);
};
