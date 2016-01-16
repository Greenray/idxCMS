<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module TAGCLOUD: Template

die();?>

<script type="text/javascript" src="[$path:]swfobject.js"></script>
<div id="flashcontent" class="center">$tags_txt</div>
<script type="text/javascript">
    var so = new SWFObject("[$path:]tagcloud.swf", "tagcloud", "$width", "$height", "7", "$bgcolor");
    so.addParam("wmode", "$wmode");
    so.addVariable("mode", "tags");
    so.addVariable("distr", "$distr");
    so.addVariable("tspeed", "$speed");
    so.addVariable("tagcloud", "$tagcloud");
    so.write("flashcontent");
</script>