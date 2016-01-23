<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Module FORUM: Topic template

die();?>

<div class="topic">
    <a name="$id"></a>
    <div class="content">
        <div class="title"><h1>$title</h1></div>

            <div class="info">
                <span class="date">$date</span>
                <!-- IF !empty($profile) -->
                    <span class="profile">
                        <a href="{MODULE}user&user=$author" class="icon icon-profile tip" title="__Profile__"></a>
                        <a href="{MODULE}user.pm&for=$author" class="icon icon-pm tip" title="__Private message__"></a>
                    </span>
                <!-- ENDIF -->
            </div>
            <div class="author center">
                <div class="avatar center">
                    <img src="$avatar" hspace="5" vspace="5" alt="avatar" />
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
            <form name="topic" method="post" action="">
                <!-- IF !empty($ip) -->
                    <button type="submit" formaction="$link&action=ban&host=$ip">$ip</button>
                <!-- ENDIF -->
                <button type="submit" formaction="$link{ITEM}$id&action=$action_pin">$command_pin</button>
                <button type="submit" formaction="$link&action=$action">$command</button>
                <button type="submit" formaction="$link&action=edit">__Edit__</button>
                <button type="submit" formaction="$link&action=delete">__Delete__</button>
            </form>
        </div>
    <!-- ENDIF -->
</div>
<div class="clear"></div>

