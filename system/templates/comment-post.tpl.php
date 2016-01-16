<?php
# idxCMS Flat Files Content Management System 3.0
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# Comment post template

die();?>

<!-- IF !empty($message_length) -->
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
<!-- ENDIF -->
<script type="text/javascript">
    function checkForm(form) {
        if (form.text.value === '') {
            ShowAlert('__Enter a code__');
            return false;
        }
        <!-- IF !empty($captcha) -->
            if (form.captcheckout.value === '') {
                ShowAlert('__Enter a code__');
                return false;
            }
        <!-- ENDIF -->
        return true;
    }
</script>
<div class="comment_post center">
    $bbcodes
    <form id="comment" name="comment" method="post" action="$action" onsubmit="return checkForm(this);">
        <textarea id="text" name="text" cols="20" rows="7">$text</textarea>
        <!-- IF !empty($email) -->
            <p>
                <label>__Email__</label>
                <input type="email" id="email" name="email" size="30" value="$email" class="required" onClick="$(this).val('');" />
            </p>
        <!-- ENDIF -->
        <!-- IF !empty($message_length) -->
            <div>__Max message length__ [<script type="text/javascript">displayLimit("", "text", '$message_length')</script>] __symbols__</div>
        <!-- ENDIF -->
        <!-- IF !empty($captcha) -->$captcha<!-- ENDIF -->
        <p class="navigation center">
        <!-- IF !empty($for) -->
            <input type="hidden" name="for" value="$for" />
        <!-- ENDIF -->
            <input type="reset" name="reset" value="__Reset__" />
            <input type="submit" name="save" value="__Save__" />
        </p>
    </form>
</div>
