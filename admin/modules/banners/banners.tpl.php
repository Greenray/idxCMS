<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Banners template.

die();?>

<div class="module">__Banners__</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
        <!-- FOREACH banner = $banner -->
            <tr>
                <td>$banner.bbCodes</td>
                <td></td>
            </tr>
            <tr>
                <td class="center"><textarea id="$banner.id" name="text[]" cols="80" rows="10">$banner.text</textarea></td>
                <td class="banner">$banner.view</td>
            </tr>
        <!-- ENDFOREACH -->
        </table>
        <p class="center"><input type="submit" name="save" value="__Save__" /></p>
    </form>
</fieldset>
