<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module SEARCH: Search results template

die();?>

<div class="results">
    <div class="center"><b>__Search results__: $count __coincidence__</b></div>
    <!-- IF !empty($count) -->
        <ul class="level1">
        <!-- FOREACH result = $results -->
            <li class="level1">
                <a class="level1" href="$result.link">$result.title</a>
                <span class="subtitle">$result.text</span>
            </li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ELSE -->
        <div class="center"><em>__Nothing founded__</em></div>
    <!-- ENDIF -->
</div>
