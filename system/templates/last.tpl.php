<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Template for last items

die();?>

<div class="last-items">
    <ul>
    <!-- FOREACH item = $items -->
        <li>
            <a href="$item.link">$item.title</a>
            <div class="info">
                $item.date<br />
                <!-- IF !empty($item.image) -->
                    <a href="{CONTENT}gallery{DS}$item.section{DS}$item.category{DS}$item.id{DS}$item.image" class="cbox">
                        <img src="{CONTENT}gallery{DS}$item.section{DS}$item.category{DS}$item.id{DS}$item.image.jpg" class="nomargin" alt="__Image__" />
                    </a><br />
                <!-- ENDIF -->
                __Views__: $item.views
                <!-- IF !empty($item.comments) -->
                    <a href="$item.link{COMMENT}$item.comments">__Comments__: $item.comments</a>
                <!-- ENDIF -->
            </div>
        </li>
    <!-- ENDFOREACH -->
    </ul>
</div>
