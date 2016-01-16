<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module GALLERY: Images template

die();?>

<link rel="stylesheet" type="text/css" href="{TOOLS}colorbox/jquery.colorbox.css" media="screen" />
<script type="text/javascript" src="{TOOLS}colorbox/jquery.colorbox.min.js"></script>
<div class="gallery">
<!-- FOREACH image = $images -->
        <span class="item">
            <!-- IF !empty($image.rateid) -->
                <div id="rate[$image.rateid:]">$image.rate</div>
            <!-- ENDIF -->
            <a class="cbox" href="$image.path$image.id{DS}$image.image" title="$image.title">
                <img src="$image.path$image.id{DS}$image.image.jpg" width="$image.width" height="$image.height" hspace="10" vspace="10" alt="" />
            </a>
            <div class="title">$image.title</div>
            <div class="info">
                <a href="$image.link">__Read more...__ <!-- IF !empty($images.views) -->$image.views<!-- ENDIF --></a>
                <a href="$image.comment">__Comments__ <!-- IF !empty($comments) -->[$comments]<!-- ENDIF --></a>
            </div>
        </span>
<!-- ENDFOREACH -->
</div>
<div class="clear"></div>
