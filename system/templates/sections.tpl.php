<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Template for sections

die();?>

<div class="section">
    <ul class="level2">
    <!-- FOREACH section = $sections -->
        <li class="level2">
            <a class="level2" href="$section.link">
                <span class="title">$section.title</span>
                <span class="subtitle">$section.desc</span>
            </a>
            <!-- IF !empty($section.categories) -->
                <ul class="level3">
                <!-- FOREACH category = $section.categories -->
                    <li class="level3">
                        <img src="[$category.path:]icon.png" width="35" height="35" hspace="10" alt="" />
                        <a class="level3" href="$category.link">
                            <span class="title">$category.title</span>
                            <span class="subtitle">$category.desc</span>
                        </a>
                    </li>
                <!-- ENDFOREACH -->
                </ul>
            <!-- ENDIF -->
        </li>
    <!-- ENDFOREACH -->
    </ul>
</div>
