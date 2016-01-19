<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Module USER: Feedback template

die();?>

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
<script type="text/javascript">
    function checkForm(form) {
        <!-- IF !empty($email) -->
            var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
            if ((form.email.value === '') || !form.email.match(emailRegex)) {
                ShowAlert('__Error in the email__');
                return false;
            }
        <!-- ENDIF -->
        if (form.message.value === '') {
            ShowAlert('__Enter a text__');
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
    <div><h2>__Private message to Administrator__</h2></div>
    $bbcodes
    <form id="feedback" name="feedback" method="post" action="$action" onsubmit="return checkForm(this);">
        <!-- IF !empty($email) -->
            <p>
                <label>__Email__</label>
                <input type="email" id="email" name="email" size="30" value="$email" class="required" />
            </p>
        <!-- ENDIF -->
        <textarea id="text" name="text" cols="20" rows="7">$message</textarea>
        <!-- IF !empty($message_length) -->
        <div>__Max message length__ [<script type="text/javascript">displayLimit("", "text", '$message_length')</script>] __symbols__</div>
        <!-- ENDIF -->
        <!-- IF !empty($captcha) -->
            <p class="center">$captcha</p>
        <!-- ENDIF -->
        <p class="center">
            <!-- IF !empty($captcha) -->$captcha<!-- ENDIF -->
            <button type="submit">__Submit__</button>
            <button type="reset">__Reset__</button>
        </p>
    </form>
</div>
