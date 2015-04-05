<?php
# idxCMS Flat Files Content Management Sysytem
# Module Search
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="results">
    <div class="center"><b>[__Search results]: {count} [__coincidence]</b></div>
    <ul class="level1">
        [ifelse=count]
            [each=result]
                <li class="level1 parent">
                    <div class="bg">
                        <a class="level1" href="{result[link]}">{result[title]}</a>
                        <span class="subtitle">{result[text]}</span>
                    </div>
                </li>
            [endeach.result]
        [else]
            <div class="center"><em>[__Nothing founded]</em></div>
        [endelse]
    </ul>
</div>
