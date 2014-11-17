<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - FRAMESET

if (!defined('idxADMIN') || !USER::loggedIn()) die();?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo SYSTEM::get('locale');?>" lang="<?php echo SYSTEM::get('locale');?>">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="content-language" content="<?php echo SYSTEM::get('locale');?>" />
    <title><?php echo __('Administration');?></title>
    <link rel="shortcut icon" href="<?php echo ROOT;?>favicon.ico" />
</head>
<frameset rows= "7%,88%,5%" framespacing="0" frameborder="yes" border="1">
    <frameset>
        <frame src="<?php echo MODULE;?>admin&amp;id=header" name="header" marginwidth="0" marginheight="0" noresize scrolling="no">
    </frameset>
    <frameset cols="190,*" framespacing="0" frameborder="yes" border="0">
        <frame src="<?php echo MODULE;?>admin&amp;id=nav" name="nav" marginwidth="3" marginheight="3" noresize scrolling="no">
        <frame src="<?php echo MODULE;?>admin" name="main" marginwidth="3" marginheight="3" scrolling="auto">
    </frameset>
    <frame src="<?php echo MODULE;?>admin&amp;id=footer" name="footer" marginwidth="0" marginheight="0" noresize scrolling="no">
</frameset>
<noframes>
    <body bgcolor="white" text="#000000">
        <p>Sorry, but your browser does not support frames</p>
    </body>
</noframes>
</html>
