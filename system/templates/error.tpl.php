<?php
# idxCMS Flat Files Content Management System 3.0
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Template for error message

die();?>

<form id="error" name="error" method="post" action="$url" class="error">
    <p class="title center">__Error__</p>
    <p class="text center">$message</p>
    <p class="center"><input type="submit" name="error" value="__OK__" /></p>
</form>
