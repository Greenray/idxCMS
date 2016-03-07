<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module FORUM: Topic template

die();?>

<div class="topic">
    <a id="$id"></a>
    <div class="content">
        <div class="title"><h1>$title</h1></div>

            <div class="info">
                <span class="date">$date</span>
                <!-- IF !empty($profile) -->
                    <span class="profile">
                        <a href="{MODULE}user&amp;user=$author" class="icon icon-profile tip" title="__Profile__"></a>
                        <a href="{MODULE}user.pm&amp;for=$author" class="icon icon-pm tip" title="__Private message__"></a>
                    </span>
                <!-- ENDIF -->
            </div>
            <div class="author center">
                <div class="avatar center">
                    <img src="$avatar" alt="avatar" />
                </div>
                <div class="user center">
                <!-- IF !empty($opened) -->
                    <strong><a href="javascript:InsertText(document.forms['comment'].elements['text'], '[b]$nick![/b]' + '\n');">$nick</a></strong>
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
    <!-- IF !empty($admin) -->
        <div class="actions right">
            <form name="topic" method="post" >
                <!-- IF !empty($ip) -->
                    <button type="submit" formaction="$link&amp;action=ban&amp;host=$ip">$ip</button>
                <!-- ENDIF -->
                <button type="submit" formaction="$link{ITEM}$id&amp;action=$action_pin">$command_pin</button>
                <button type="submit" formaction="$link&amp;action=$action">$command</button>
                <button type="submit" formaction="$link&amp;action=edit">__Edit__</button>
                <button type="submit" formaction="$link&amp;action=delete">__Delete__</button>
            </form>
        </div>
    <!-- ENDIF -->
</div>
<div class="clear"></div>

