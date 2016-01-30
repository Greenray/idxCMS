<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Template for short item description

die();?>

<div class="post">
    <div class="date">$date</div>
    <!-- IF !empty($rateid) -->
        <div id="rate[$rateid:]" class="star-rate">$rate</div>
    <!-- ENDIF -->
    <div class="title"><h1><a href="$link">$title</a></h1></div>
    <div class="text justify">$desc</div>
    <div class="info">
        <span class="author center">__Posted by__: <a href="{MODULE}user&user=$author" class="tip" title="__Profile__">$nick</a></span>
        <span class="admin">
            <a href="$link">__Read more...__ <!-- IF !empty($views) -->[$views]<!-- ENDIF --></a>
            <!-- IF !empty($downloads) -->__Downloads__ [$downloads]<!-- ENDIF -->
            <!-- IF !empty($visits) -->__Transitions__ [$visits]<!-- ENDIF -->
            <!-- IF $comments > 0 -->
                <a href="$link{COMMENT}$comments">__Comments__ [$comments]</a>
            <!-- ELSE -->
                <a href="$link">__Comments__</a>
            <!-- ENDIF -->
        </span>
    </div>
</div>
