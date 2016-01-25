<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Module GALLERY: Template for the random image

die();?>

<!-- FOREACH random = $randoms -->
    <div class="center">
        <a href="$random.link">
            <img src="$random.path$random.id{DS}[$random.image:].jpg" width="200" height="150" alt="__Random image__" />
        </a>
    </div>
    <div class="random-image center">$random.title</div>
<!-- ENDFOREACH -->
