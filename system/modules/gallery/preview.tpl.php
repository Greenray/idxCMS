<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module GALLERY: Images template

die();?>

<div class="preview center">
<!-- FOREACH image = $images -->
    <a class="cbox" href="{CONTENT}gallery{DS}$image.section{DS}$image.category{DS}$image.id{DS}$image.image" title="$image.title">
        <img src="{CONTENT}gallery{DS}$image.section{DS}$image.category{DS}$image.id{DS}$image.image.jpg" width="100" height="75" alt="IMAGE" />
    </a>
<!-- ENDFOREACH -->
</div>
