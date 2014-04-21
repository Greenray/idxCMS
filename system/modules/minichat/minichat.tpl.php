<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MINICHAT TEMPLATE

die();?>

[if=not_admin]
    <script type="text/javascript">
        var ns6 = document.getElementById && !document.all;
        function restrictInput(maxlength, e, placeholder) {
            if (window.event && event.srcElement.value.length >= maxlength)
                return false;
            else if (e.target && e.target == eval(placeholder) && e.target.value.length >= maxlength) {
                var pressedkey = /[a-zA-Z0-9\.\,\/]/;    // Detect alphanumeric keys
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
            var form = (id != "") ? document.getElementById(id) : name;
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
    </script>
[endif]
<script type="text/javascript">
    function checkForm(form) {
        var text = form.mctext.value;
        [if=captcha]
            var capt = form.captcheckout.value;
        [endif]
        if (text == "") {
            inlineMsg('mctext', '[__Fill this field]');
            return false;
        }
        [if=captcha]
            if (capt == "") {
                inlineMsg('captcheckout', '[__Fill this field]');
                return false;
            }
        [endif]
        return true;
    }
</script>
[each=msg]
    <div class="chat">
        <div class="name"><strong>{msg[nick]}</strong></div>
        <div class="info">
            {msg[date]}
            [if=msg[moderator]]
                <span class="menu">
                    <form name="minichat" method="post" action="">
                        <input type="hidden" name="message" value="{msg[id]}" />
                        <button type="submit" name="mcaction" value="delete" class="tip" title="[__Delete]">
                            <img src="{ICONS}delete.png" width="10" height="10" class="tip" alt="[__Delete]" />
                        </button>
                    </form>
                </span>
            [endif]
            [if=msg[ip]]
                <span class="menu">
                    <form name="minichat" method="post" action="">
                        <input type="hidden" name="host" value="{msg[ip]}" />
                        <button type="submit" name="mcaction" value="ban" class="tip" title="[__Ban]">
                            <img src="{ICONS}ban.png" width="10" height="10" class="tip" alt="[__Ban]" />
                        </button>
                    </form>
                </span>
            [endif]
        </div>
        <div class="text">{msg[text]}</div>
    </div>
[endeach.msg]
[ifelse=allow_post]
    <div class="post-comment">
        <form id="post" name="post" method="post" action="" onsubmit="return checkForm(this);">
                <textarea id="mctext" name="mctext" rows="5">{mctext}</textarea>
                [if=not_admin]
                    [__Max message length] [<script type="text/javascript">displayLimit("", "mctext", '{message-length}')</script>] [__symbols]
                [endif]
            <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
        </form>
    </div>
[else]
[endelse]