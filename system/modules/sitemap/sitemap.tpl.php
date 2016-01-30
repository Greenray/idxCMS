<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module SITEMAP: Template

die();?>

<!-- FOREACH point = $points -->
<div class="section">
    <ul class="level1">
        <li class="level1">
            <a class="level1" href="$point.link">
                <span class="bg">
                <!-- IF !empty($point.desc) -->
                    <span class="title">$point.name</span>
                    <span class="subtitle">$point.desc</span>
                <!-- ELSE -->
                    $point.name
                <!-- ENDIF -->
                </span>
            </a>
            <!-- IF !empty($point.sections) -->
                <ul class="level2">
                <!-- FOREACH section = $point.sections -->
                    <li class="level2">
                        <a class="level2" href="$section.link">
                        <!-- IF !empty($section.desc) -->
                            <span class="title">$section.title</span>
                            <span class="subtitle">$section.desc</span>
                        <!-- ELSE -->
                            $section.title
                        <!-- ENDIF -->
                        </a>
                    <!-- IF !empty($section.categories) -->
                        <ul class="level3">
                        <!-- FOREACH category = $section.categories -->
                            <li class="level3">
                                <img src="[$category.path:]icon.png" width="35" height="35" hspace="10" alt="" />
                                <a class="level3" href="$category.link">
                                <!-- IF !empty($category.desc) -->
                                    <span class="title">$category.title</span>
                                    <span class="subtitle">$category.desc</span>
                                <!-- ELSE -->
                                    $category.title
                                <!-- ENDIF -->
                                </a>
                            </li>
                        <!-- ENDFOREACH -->
                        </ul>
                    <!-- ENDIF -->
                    </li>
                <!-- ENDFOREACH -->
                </ul>
            <!-- ENDIF -->
        </li>
    </ul>
</div>
<!-- ENDFOREACH -->
