<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Module SEARCH: Search form

die();?>

<form name="search" method="post" action="" class="navigation center">
    <input type="hidden" name="module" value="search" />
    <input type="text" name="search" value="__Search__" onClick="$(this).val('');" />
    <input type="submit" value="__Submit__" />
</form>
