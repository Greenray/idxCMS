<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - MODULE

if (!defined('idxADMIN') || !USER::loggedIn()) die();?>
<!DOCTYPE html>
<html lang="<?php echo SYSTEM::get('locale');?>">
<head>
    <title><?php echo __('Modules');?></title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="content-language" content="<?php echo SYSTEM::get('locale');?>" />
    <meta http-equiv="pragma" content="no-cache" />
    <link rel="stylesheet" type="text/css" href="<?php echo ADMIN;?>style.css" media="screen" />
    <script type="text/javascript" src="<?php echo TOOLS;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo TOOLS;?>jquery.ui-min.js"></script>
    <link type="text/css" href="<?php echo TOOLS;?>colorbox/colorbox.css" rel="stylesheet" media="screen" />
    <script type="text/javascript" src="<?php echo TOOLS;?>colorbox/jquery.colorbox-min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".cbox").colorbox({rel:'cbox'});
        });
    </script>
    <script type="text/javascript" src="<?php echo TOOLS;?>unitip-min.js"></script>
    <script type="text/javascript" src="<?php echo TOOLS?>message-min.js"></script>
    <script type="text/javascript">
        function ShowHide(obj) {
            if (obj == "none")
                 return "inline";
            else return "none";
        }
    </script>
</head>
<body style="background-color: #efefef;">
<div style="padding:2px;">
   <?php
    # Activate module
    if (file_exists(ADMIN.'modules'.DS.$id.'.php')) {
        include(ADMIN.'modules'.DS.$id.'.php');
    } elseif (file_exists(ADMINLIBS.$action.'.php')) {
        include(ADMINLIBS.$action.'.php');
    } else {
        $message = __('Module not found').': '.$id;
        include(ADMIN.'error.php');
    }
   ?>
</div>
</body>
</html>