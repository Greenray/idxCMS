<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module MINICHAT: Template

die();?>

<script type="text/javascript" src='{TOOLS}limit.js'></script>
<script type="text/javascript">
    function checkForm(form) {
        if (form.mctext.value === '') {
            ShowAlert('__Enter the text__');
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
                    <form name="minichat" method="post" >
                        <input type="hidden" name="message" value="$message.id" />
                        <input type="submit" name="delete" value="__Delete__" />
                    <!-- IF !empty($message.ip) -->
                        <input type="hidden" name="host" value="$message.ip" />
                        <input type="submit" name="ban" value="__Ban__" />
                    <!-- ENDIF -->
                    </form>
                </div>
            <!-- ENDIF -->
        </div>
    <!-- ENDFOREACH -->
<!-- ENDIF -->
<!-- IF $allow_post==true -->
    <div class="chat-post center">
        <form id="post" name="post" method="post"  onsubmit="return checkForm(this);">
            <textarea id="mctext" name="mctext" rows="5">$mctext</textarea>
            <!-- IF !empty($message_length) -->
                __Max message length__ [<script type="text/javascript">displayLimit("document.post.mctext", "", '$message_length')</script>] __symbols__
            <!-- ENDIF -->
            <input type="reset" value="__Reset__" />
            <input type="submit" name="save" value="__Save__" />
        </form>
    </div>
<!-- ENDIF -->
