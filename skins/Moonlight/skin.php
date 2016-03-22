<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Additional skins definition

if (!defined('idxCMS')) die();

# Output points
$SKIN['left']        = __('Left panel');
$SKIN['up-center']   = __('Center column, upper than main module');
$SKIN['down-center'] = __('Center column, lower than main module');
$SKIN['right']       = __('Right panel');
$SKIN['boxes']       = __('Boxes');

$tpl = '
<div class="$class">
    <!-- IF !empty($title) --><div class="title center"><h3>$title</h3></div><!-- ENDIF -->
    <div class="content">
        $content
    </div>
</div>
';

$main = '
<div class="$class">
    <!-- IF !empty($title) --><div class="title center"><h2>$title</h2></div><!-- ENDIF -->
    <div class="content">
        $content
    </div>
</div>
';

$message = '
<script type="text/javascript">
    dhtmlx.modalbox({
        type: "alert",
        title: "$title",
        text: "$message",
        buttons: ["Ok"],
        callback: function(){
            document.location.href="$url";
        }
    });
</script>
';

$error = '
<script type="text/javascript">
    dhtmlx.modalbox({
        type: "alert-error",
        title: "__Error__",
        text: "<strong>$message</strong>",
        buttons: ["Ok"],
        callback: function(){
            document.location.href="{ROOT}";
        }
    });
</script>
';

CMS::call('SYSTEM')->registerSkin('box', $tpl);
CMS::call('SYSTEM')->registerSkin('darkbox', $tpl);
CMS::call('SYSTEM')->registerSkin('lightbox', $tpl);
CMS::call('SYSTEM')->registerSkin('main', $main);
CMS::call('SYSTEM')->registerSkin('panel', $tpl);
CMS::call('SYSTEM')->registerSkin('win', $tpl);
CMS::call('SYSTEM')->registerSkin('error', $error);
CMS::call('SYSTEM')->registerSkin('message', $message);
