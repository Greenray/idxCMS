<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# COMMENT POST TEMPLATE

die();?>
[if=comment-length]
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
    </script>
[endif]
<script type="text/javascript">
    function checkForm(form) {
        var text = form.text.value;
        [if=captcha]var capt = form.captcheckout.value;[endif]
        if (text == '') {
            ShowAlert('[__Enter a code]', '[__Error]');
            return false;
        }
        [if=captcha]
            if (capt === '') {
                ShowAlert('[__Enter a code]', '[__Error]');
                return false;
            }
        [endif]
        return true;
    }
</script>
<div class="post-comment center">
    {bbcodes}
    <form id="post-comment" name="post-comment" method="post" action="{action}" onsubmit="return checkForm(this);">
        <fieldset>
            <legend>[__Text]</legend>
            <textarea id="text" name="text" cols="20" rows="7">{text}</textarea>
            [if=not_admin]<div>[__Max message length] [<script type="text/javascript">displayLimit("", "text", '{comment-length}')</script>] [__symbols]<div>[endif]
        </fieldset>
        <p class="center">
            [if=for]<input type="hidden" name="for" value="{for}" />[endif]
            [if=captcha]{captcha}[endif]
            <input type="submit" name="save" value="[__Save]" class="submit" />
        </p>
    </form>
</div>
