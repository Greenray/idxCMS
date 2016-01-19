<?php
# idxCMS Flat Files Content Management System 3.0
# Copyright (c) 2016 Victor Nabatov
# Administration: Template for error message

die();?>

<div>
    <form id="message" name="message" method="post" action="$url" class="message">
        <p class="title center">__Message__</p>
        <p class="text center">$message</p>
        <p class="center"><input type="submit" name="message" value="__OK__" /></p>
    </form>
</div>