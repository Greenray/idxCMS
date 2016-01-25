<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Module APHORISMS: Aphorisms template

die();?>

<script type="text/javascript" src="{MODULES}aphorisms{DS}jquery.ui.min.js"></script>
<script type="text/javascript" src="{MODULES}aphorisms{DS}jquery.flip.min.js"></script>
<script type="text/javascript">
$(function() {
    $('#flippad a:not(.revert)').bind('click', function() {
        function createRequest() {
            var req;
            try {
                req = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    req = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (E) {
                    req = false;
                }
            }
            if (!req && typeof XMLHttpRequest !== 'undefined') req = new XMLHttpRequest();
            return req;
        }
        function getData(url) {
            var data = new Array();
            var request = createRequest();
            request.open("GET", url, false);
            request.send(null);
            if (request.status === 200) {
                data = request.responseText.split('$', 2);
                return data[0];
            }
            return '';
        }
        $('#flipbox').flip({
            direction : $(this).attr('rel'),
            content   : getData('{MODULE}aphorisms&flip=1'),
            color     : 'transparent'
        })
        return false;
    });
});
</script>
<div id="flipbox" class="center">$text</div>
<div id="flippad" class="center">
    <a href="#" rel="rl" rev="#000000" class="icon icon-arrow-left" title="__Left__"></a>
    <a href="#" rel="lr" rev="#000000" class="icon icon-arrow-right" title="__Right__"></a>
</div>
