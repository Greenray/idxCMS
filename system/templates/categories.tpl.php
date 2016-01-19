<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Template for categories

die();?>

<div class="section">
    <ul class="level3">
    <!-- FOREACH category = $categories -->
        <li class="level3">
            <img src="[$category.path:]icon.png" width="35" height="35" hspace="10" alt="" />
            <a class="level3" href="$category.link">
                <span class="title">$category.title</span>
                <span class="subtitle">$category.desc</span>
            </a>
            <!-- IF !empty($category.last_id) -->
                <ul class="level3">
                    <li class="level3">
                        <span style="float:left;margin:0 5px 0 0;">__Last addition__: </span>
                        <a class="level3" href="$category.link{ITEM}$category.last_id">
                            <span class="title">$category.last_title</span>
                        </a>
                    </li>
                </ul>
            <!-- ENDIF -->
        </li>
    <!-- ENDFOREACH -->
    </ul>
</div>
