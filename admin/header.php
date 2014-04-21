<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - HEADER

if (!defined('idxADMIN') || !USER::loggedIn()) die();?>
<!DOCTYPE html>
<html lang="<?php echo SYSTEM::get('locale');?>">
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
<div><h1><?php echo __('Content Management System idxCMS')?></h1></div>
</body>
</html>