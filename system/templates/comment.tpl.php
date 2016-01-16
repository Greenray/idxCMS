<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# Comment template

die();?>

<div class="comment">
    <a name="$id"></a>
    <div class="content">
        <div class="head">
            <span class="date">$date</span>
            <!-- IF !empty($rateid) -->
                <span class="rate">
                    <button type="button" id="dnUser[$rateid:]" onclick="Rate('dn', '$author', this)">
                        <img src="{ICONS}minus.png" width="12" height="12" alt="" />
                    </button>
                    <span id="rate[$rateid:]" style="color:$rate_color">$rate</span>
                    <button type="button" id="upUser[$rateid:]" onclick="Rate('up', '$author', this)">
                        <img src="{ICONS}plus.png" width="12" height="12" alt="" />
                    </button>
                </span>
            <!-- ELSEIF !empty($rate_color) -->
                <span class="rate $rate_color center">$rate</span>
            <!-- ENDIF -->
        </div>
        <div class="author center">
            <div class="avatar center">
                <img src="$avatar" hspace="5" vspace="5" alt="" />
            </div>
            <div class="user center">
            <!-- IF !empty($opened) -->
                <strong><a href="javascript:InsertText(document.forms['comment_post'].elements['text'], '[b]$nick![/b]' + '\n');">$nick</a></strong>
            <!-- ELSE -->
                $nick
            <!-- ENDIF -->
            </div>
            <!-- IF !empty($status) --><div>$status</div><!-- ENDIF -->
            <!-- IF !empty($stars) --><div>__Rate__: $stars</div><!-- ENDIF -->
            <!-- IF !empty($city) --><div>$city</div><!-- ENDIF -->
            <!-- IF !empty($country) --><div>$country</div><!-- ENDIF -->
        </div>
        <div class="text justify">$text</div>
    </div>
    <!-- IF !empty($moderator) -->
        <div class="actions right">
            <form name="topic" method="post" action="">
                <input type="submit" formaction="$link{COMMENT}$id&amp;action=edit" value="__Edit__" />
                <input type="submit" formaction="$link{COMMENT}$id&amp;action=delete" value="__Delete__" />
            <!-- IF !empty($ban) -->
                <input type="submit" formaction="$link{COMMENT}$id&amp;action=ban&amp;host=$ip" value="$ip" />
            <!-- ENDIF -->
            <!-- IF !empty($opened) -->
                <input type="submit" formaction="{MODULE}user&amp;user=$author" value="__Profile__" />
                <input type="submit" formaction="{MODULE}user.pm&amp;for=$author" value="__Private message__" />
            <!-- ENDIF -->
            </form>
        </div>
    <!-- ENDIF -->
</div>
<div class="clear"></div>
