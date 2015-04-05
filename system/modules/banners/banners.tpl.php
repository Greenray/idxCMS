<?php
# idxCMS Flat Files Content Management Sysytem
# Module Banners
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<script type="text/javascript" src="{TOOLS}jquery.mousewheel.js"></script>
<script type="text/javascript" src="{TOOLS}jquery.horinaja.js"></script>
<script type="text/javascript">
    $(function() {
        $('#banner').Horinaja({
            capture    :'banner',
            delai      : 0.3,
            duree      : 10,
            pagination : false
        });
    });
</script>
<div id="banner" class="horinaja">
    <ul>
    [each=banner]<li>{banner[text]}</li>[endeach.banner]
    </ul>
</div>
