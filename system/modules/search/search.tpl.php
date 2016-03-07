<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module SEARCH: Search form

die();?>

<form name="search" method="post"  class="navigation center">
    <input type="hidden" name="module" value="search" />
    <input type="text" name="search" value="__Search__" onClick="$(this).val('');" />
    <input type="submit" value="__Submit__" />
</form>
