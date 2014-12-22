jQuery.fn.extend({everyTime: function (b, c, d, e, a) {
        return this.each(function () {
            jQuery.timer.add(this, b, c, d, e, a);
        });
    }, oneTime: function (a, b, c) {
        return this.each(function () {
            jQuery.timer.add(this, a, b, c, 1);
        });
    }, stopTime: function (a, b) {
        return this.each(function () {
            jQuery.timer.remove(this, a, b);
        });
    }});
jQuery.event.special;
jQuery.extend({timer: {global: [], guid: 1, dataKey: "jQuery.timer", regex: /^([0-9]+(?:\.[0-9]*)?)\s*(.*s)?$/, powers: {ms: 1, cs: 10, ds: 100, s: 1000, das: 10000, hs: 100000, ks: 1000000}, timeParse: function (c) {
            if (c === undefined || c === null) {
                return null;
            }
            var b = this.regex.exec(jQuery.trim(c.toString()));
            if (b[2]) {
                var a = parseFloat(b[1]);
                var d = this.powers[b[2]] || 1;
                return a * d;
            } else {
                return c;
            }
        }, add: function (d, c, h, g, b, f) {
            var a = 0;
            if (jQuery.isFunction(h)) {
                if (!b) {
                    b = g;
                }
                g = h;
                h = c;
            }
            c = jQuery.timer.timeParse(c);
            if (typeof c !== "number" || isNaN(c) || c <= 0) {
                return;
            }
            if (b && b.constructor !== Number) {
                f = !!b;
                b = 0;
            }
            b = b || 0;
            f = f || false;
            var e = jQuery.data(d, this.dataKey) || jQuery.data(d, this.dataKey, {});
            if (!e[h]) {
                e[h] = {};
            }
            g.timerID = g.timerID || this.guid++;
            var j = function () {
                if (f && this.inProgress) {
                    return;
                }
                this.inProgress = true;
                if ((++a > b && b !== 0) || g.call(d, a) === false) {
                    jQuery.timer.remove(d, h, g);
                }
                this.inProgress = false;
            };
            j.timerID = g.timerID;
            if (!e[h][g.timerID]) {
                e[h][g.timerID] = window.setInterval(j, c);
            }
            this.global.push(d);
        }, remove: function (d, c, e) {
            var a = jQuery.data(d, this.dataKey), b;
            if (a) {
                if (!c) {
                    for (c in a) {
                        this.remove(d, c, e);
                    }
                } else {
                    if (a[c]) {
                        if (e) {
                            if (e.timerID) {
                                window.clearInterval(a[c][e.timerID]);
                                delete a[c][e.timerID];
                            }
                        } else {
                            for (var f in a[c]) {
                                window.clearInterval(a[c][f]);
                                delete a[c][f];
                            }
                        }
                        for (b in a[c]) {
                            break
                        }
                        if (!b) {
                            b = null;
                            delete a[c];
                        }
                    }
                }
                for (b in a) {
                    break
                }
                if (!b) {
                    jQuery.removeData(d, this.dataKey);
                }
            }
        }}});
jQuery(window).bind("unload", function () {
    jQuery.each(jQuery.timer.global, function (a, b) {
        jQuery.timer.remove(b);
    });
});
(function (a) {
    a.fn.Horinaja = function (c) {
        b = {capture: "", delai: 300, duree: 4000, pagination: true};
        var b = a.extend(b, c);
        return this.each(function () {
            var n = b.capture;
            var l = a("#" + n + " > ul > li").width();
            var e = (b.delai) * 1000;
            var d = (b.duree) * 1000;
            var m = b.pagination;
            var k = a("#" + n + " > ul > li").length;
            var g = 0;
            var f = 0;
            function o(p, q) {
                if (m) {
                    a("#" + n + " > ol.horinaja_pagination > li:eq(" + p + ")").fadeTo("fast", q);
                }
            }
            function j() {
                if (g !== -((l * k) - l)) {
                    a("#" + n + " > ul").animate({left: (g - l) + "px"}, e);
                    g = g - l;
                    o(f, 0.4);
                    f = f + 1;
                    o(f, 0.7);
                } else {
                    a("#" + n + " > ul").animate({left: "0px"}, e);
                    g = 0;
                    o(f, 0.4);
                    f = 0;
                    o(f, 0.7);
                }
            }
            a(this).everyTime(d, n, function () {
                j();
            });
            a("#" + n).css({overflow: "hidden", position: "relative"});
            a("#" + n + " > ul").css({width: l * k + "px"});
            a("#" + n + " > ul > li").css({width: l, "float": "left"});
            if (m) {
                a("#" + n + " > ul").after('<ol class="horinaja_pagination"></ol>');
                a("#" + n + " > ol.horinaja_pagination").css({width: l + "px"});
                var h = Math.floor(l / k);
                for (i = 1; i !== (k + 1); i++) {
                    a("#" + n + " > ol.horinaja_pagination").append('<li><a style="width:' + h + 'px;">' + i + "</a></li>");
                }
                a("#" + n + " > ol.horinaja_pagination > li").fadeTo("fast", 0.4);
                a("#" + n + " > ol.horinaja_pagination > li:first").fadeTo("fast", 0.7);
            }
            a(this).bind("mousewheel", function (r, s) {
                var p = s > 0 ? "Up" : "Down", q = Math.abs(s);
                if (p === "Up") {
                    if (g !== 0) {
                        a("#" + n + " > ul").animate({left: (g + l) + "px"}, e);
                        g = g + l;
                        o(f, 0.4);
                        f = f - 1;
                        o(f, 0.7);
                    }
                } else {
                    if (g !== -((l * k) - l)) {
                        a("#" + n + " > ul").animate({left: (g - l) + "px"}, e);
                        g = g - l;
                        o(f, 0.4);
                        f = f + 1;
                        o(f, 0.7);
                    }
                }
                return false;
            });
            a(this).bind("mouseenter", function () {
                a(this).stopTime(n);
            });
            a(this).bind("mouseleave", function () {
                a(this).everyTime(d, n, function () {
                    j();
                });
            });
            if (m) {
                a("#" + n + " > ol.horinaja_pagination > li").each(function (p) {
                    a(this).bind("click", {index: p}, function (s) {
                        var q = parseInt(s.data.index);
                        o(q, 0.7);
                        o(f, 0.4);
                        if (f > q) {
                            var r = f - q;
                            g = g + (l * r);
                            f = q;
                            a("#" + n + " > ul").animate({left: (g) + "px"}, e);
                        } else {
                            if (f < q) {
                                r = q - f;
                                g = g - (l * r);
                                f = q;
                                a("#" + n + " > ul").animate({left: (g) + "px"}, e);
                            }
                        }
                    });
                });
            }
        });
    };
})(jQuery);