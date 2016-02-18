<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com greenray.spb@gmail.com
# Comment template

die();?>

<div class="comment">
    <a id="$id"></a>
    <div class="content">
        <div class="head">
            <span class="date">$date</span>
            <!-- IF !empty($rateid) -->
                <span class="rate">
                    <button type="button" id="dnUser[$rateid:]" class="icon icon-down" onclick="Rate('dn', '$author', this)"></button>
                    <span id="rate[$rateid:]" style="color:$rate_color">$rate</span>
                    <button type="button" id="upUser[$rateid:]" class="icon icon-up" onclick="Rate('up', '$author', this)"></button>
                </span>
            <!-- ELSEIF !empty($rate_color) -->
                <span class="rate $rate_color center">$rate</span>
            <!-- ENDIF -->
        </div>
        <div class="author center">
            <div class="avatar center">
                <img src="$avatar" alt="AVATAR" />
            </div>
            <div class="user center">
            <!-- IF !empty($opened) -->
                <strong><a href="javascript:InsertText(document.forms['comment'].elements['text'], '[b]$nick![/b]' + '\n');">$nick</a></strong>
            <!-- ELSE -->
                $nick
            <!-- ENDIF -->
            </div>
            <!-- IF !empty($status) --><div>$status</div><!-- ENDIF -->
            <!-- IF !empty($stars) --><div>__Rate__: <span id="stars[$rateid:]">$stars</span></div><!-- ENDIF -->
            <!-- IF !empty($city) --><div>$city</div><!-- ENDIF -->
            <!-- IF !empty($country) --><div>$country</div><!-- ENDIF -->
        </div>
        <div class="text justify">$text</div>
    </div>

        <div class="actions right">
            <form name="actions" method="post" >
            <!-- IF $moderator==true -->
                <button type="submit" formaction="$link{COMMENT}$id&amp;action=edit">__Edit__</button>
                <button type="submit" formaction="$link{COMMENT}$id&amp;action=delete">__Delete__</button>
            <!-- ENDIF -->
            <!-- IF !empty($ip) -->
                <button type="submit" formaction="$link{COMMENT}$id&amp;action=ban&amp;host=$ip">$ip</button>
            <!-- ENDIF -->
            <!-- IF !empty($opened) -->
                <button type="submit" formaction="{MODULE}user&amp;user=$author">__Profile__</button>
                <button type="submit" formaction="{MODULE}user.pm&amp;for=$author">__Private message__</button>
            <!-- ENDIF -->
            </form>
        </div>
</div>
<div class="clear"></div>
