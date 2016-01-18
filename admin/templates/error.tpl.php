<?php
# idxCMS Flat Files Content Management System 3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Administration: Template for error message.

die();?>

<div class="login-panel">
    <form id="error" name="error" method="post" action="$url" class="error">
        <h1><span class="log-in">__Message__</span></h1>
        <p class="float">$message</p>
        <p class="navigation"><input type="submit" name="error" value="__OK__" /></p>
        <a href="#" class="close"></a>
    </form>
</div>