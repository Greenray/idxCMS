<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# Module BANNERS - TEMPLATE

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
