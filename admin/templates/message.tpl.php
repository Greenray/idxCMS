<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Template for error message

die();?>

<div>
    <form id="message" name="message" method="post" action="$url" class="message">
        <p class="title center">__Message__</p>
        <p class="text center">$message</p>
        <p class="center"><input type="submit" name="message" value="__OK__" /></p>
    </form>
</div>