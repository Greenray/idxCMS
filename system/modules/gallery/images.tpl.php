<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module GALLERY: Images template

die();?>

<div class="gallery">
<!-- FOREACH image = $images -->
    <div class="item">
        <!-- IF !empty($image.rateid) -->
            <div id="rate[$image.rateid:]">$image.rate</div>
        <!-- ENDIF -->
        <a class="cbox" href="$image.path$image.id{DS}$image.image" title="$image.title">
            <img src="$image.path$image.id{DS}$image.image.jpg" width="$image.width" height="$image.height" alt="IMAGE" />
        </a>
        <div class="title">$image.title</div>
        <div class="info">
            <a href="$image.link">__Read more...__ <!-- IF !empty($images.views) -->$image.views<!-- ENDIF --></a>
            <a href="$image.comment">__Comments__ <!-- IF !empty($comments) -->[$comments]<!-- ENDIF --></a>
        </div>
    </div>
<!-- ENDFOREACH -->
</div>
<div class="clear"></div>
