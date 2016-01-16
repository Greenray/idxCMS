<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Template for last items

die();?>

<div class="last-items">
    <ul>
    <!-- FOREACH item = $items -->
        <li>
            <a href="$item.link">$item.title</a><br />
            <span class="info">
                $item.date<br />
                __Views__: $item.views
                <!-- IF !empty($item.comments) -->
                    <a href="$item.link{COMMENT}$item.comments">__Comments__: $item.comments</a>
                <!-- ENDIF -->
            </span>
        </li>
    <!-- ENDFOREACH -->
    </ul>
</div>
