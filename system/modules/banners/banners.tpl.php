<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module BANNERS: Banners template

die();?>

<script type="text/javascript" src="{MODULES}banners{DS}jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="{MODULES}banners{DS}jquery.horinaja.js"></script>
<div id="banner" class="horinaja">
    <ul>
    <!-- FOREACH banner = $banners -->
        <li>$banner.text</li>
    <!-- ENDFOREACH -->
    </ul>
</div>
