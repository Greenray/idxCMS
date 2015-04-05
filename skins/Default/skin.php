<?php
# idxCMS Flat Files Content Management Sysytem
# Layout definition
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

# Output points
$SKIN['left']        = __('Left panel');
$SKIN['up-center']   = __('Center column, upper than main module');
$SKIN['down-center'] = __('Center column, lower than main module');
$SKIN['right']       = __('Right panel');
$SKIN['boxes']       = __('Boxes');

$tpl = '
<div class="{class}">
    [if=title]<div class="title center">{title}</div>[endif]
    <div class="content {align}">
        {content}
    </div>
</div>';

CMS::call('SYSTEM')->registerSkin('box', $tpl);
CMS::call('SYSTEM')->registerSkin('darkbox', $tpl);
CMS::call('SYSTEM')->registerSkin('lightbox', $tpl);
CMS::call('SYSTEM')->registerSkin('main', $tpl);
CMS::call('SYSTEM')->registerSkin('panel', $tpl);
CMS::call('SYSTEM')->registerSkin('win', $tpl);
CMS::call('SYSTEM')->registerSkin('error', $tpl);
