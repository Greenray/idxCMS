<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - FOOTER

if (!defined('idxADMIN') || !USER::loggedIn()) die();?>
<!DOCTYPE html>
<html lang="<?php echo SYSTEM::get('locale');?>">
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