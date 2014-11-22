<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - ERROR MESSAGE

if (!defined('idxCMS')) die();?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo SYSTEM::get('locale');?>" lang="<?php echo SYSTEM::get('locale');?>">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="content-language" content="<?php echo SYSTEM::get('locale');?>" />
    <link href="<?php echo ADMIN?>style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<table class="message center">
    <tr><td class="module row2"><?php echo $message[0]?></td></tr>
    <tr><td class="row2" style="padding:0 10px 10px 10px;"><?php echo $message[1]?></td></tr>
</table>
</body>
</html>
