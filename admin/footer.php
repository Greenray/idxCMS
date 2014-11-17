<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - FOOTER

if (!defined('idxADMIN') || !USER::loggedIn()) die();?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo SYSTEM::get('locale');?>" lang="<?php echo SYSTEM::get('locale');?>">
<head>
    <title><?php echo __('Copyright');?></title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="content-language" content="<?php echo SYSTEM::get('locale');?>" />
    <style type="text/css">
        <!--
        body { background: #006699; color: yellow; text-align: center }
        #footer           { font: bold 8pt arial, helvetica, verdana, sans-serif; text-shadow: 1px 1px 1px #000; padding: 10px 0 0 }
        #footer a:link,
        #footer a:active,
        #footer a:visited { color: #fff; text-decoration: none }
        #footer a:hover   { color: yellow; text-decoration: underline }
        -->
    </style>
</head>
<body>
<div id="footer"><?php echo IDX_POWERED.' '.IDX_COPYRIGHT;?></div>
</body>
</html>