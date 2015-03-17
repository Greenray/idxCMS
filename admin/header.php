<?php
# idxCMS Flat Files Content Management Sysytem
# Administration
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !USER::loggedIn()) die();?>

<!DOCTYPE html>
<head>
    <title><?php echo __('Header');?></title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="content-language" content="<?php echo SYSTEM::get('locale');?>" />
    <style type="text/css">
        <!--
        body { background-color: #006699; color: yellow; text-align: center }
        h1   { font: bold 14pt arial, helvetica, verdana, sans-serif; text-shadow: 1px 1px 1px #000 }
        -->
    </style>
</head>
<body>
    <div class="header" style="padding:0 0 0 180px"><h1><?php echo __('Content Management System idxCMS')?></h1></div>
</body>
</html>