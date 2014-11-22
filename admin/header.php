<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - HEADER

if (!defined('idxADMIN') || !USER::loggedIn()) die();?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo SYSTEM::get('locale');?>" lang="<?php echo SYSTEM::get('locale');?>">
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