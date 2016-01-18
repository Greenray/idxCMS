<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module MINICHAT: Template

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
        if (form.mctext.value === '') {
            ShowAlert('__Enter a text__');
            return false;
        }
        return true;
    }
</script>
<!-- IF !empty($messages) -->
    <!-- FOREACH message = $messages -->
        <div class="chat">
            <div class="info"><strong>$message.nick</strong> $message.date</div>
            <div class="text justify">$message.text</div>
            <!-- IF !empty($message.moderator) -->
                <div class="right">
                    <form name="minichat" method="post" action="">
                        <input type="hidden" name="message" value="$message.id" />
                        <input type="hidden" name="mcaction" value="delete" />
                        <input type="submit" name="mcaction" value="__Delete__" />
                    <!-- IF !empty($message.ip) -->
                        <input type="hidden" name="host" value="$message.ip" />
                        <input type="hidden" name="mcaction" value="ban" />
                        <input type="submit" name="mcaction" value="__Ban__" />
                    <!-- ENDIF -->
                    </form>
                </div>
            <!-- ENDIF -->
        </div>
    <!-- ENDFOREACH -->
<!-- ENDIF -->
<!-- IF $allow_post==true -->
    <div class="minichat_post center">
        <form id="post" name="post" method="post" action="" onsubmit="return checkForm(this);">
            <textarea id="mctext" name="mctext" rows="5">$mctext</textarea>
            <!-- IF !empty($message_length) -->
                __Max message length__ [<script type="text/javascript">displayLimit("", "mctext", '$message_length')</script>] __symbols__
            <!-- ENDIF -->
            <input type="reset" value="__Reset__" />
            <input type="submit" name="save" value="__Save__" />
        </form>
    </div>
<!-- ENDIF -->
