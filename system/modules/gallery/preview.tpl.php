<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Module GALLERY: Images template

die();?>

<div class="preview center">
<!-- FOREACH image = $images -->
    <a class="cbox" href="{CONTENT}gallery{DS}$image.section{DS}$image.category{DS}$image.id{DS}$image.image" title="$image.title">
        <img src="{CONTENT}gallery{DS}$image.section{DS}$image.category{DS}$image.id{DS}$image.image.jpg" width="100" height="75" hspace="10" vspace="10" alt="" />
    </a>
<!-- ENDFOREACH -->
</div>
