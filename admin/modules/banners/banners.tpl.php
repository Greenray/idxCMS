<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Aphorizms
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="module">[__Banners]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            [each=banner]
            <tr><td>{banner[bbCodes]}</td><td></td></tr>
            <tr>
                <td><textarea id="{banner[id]}" name="text[]" cols="80" rows="10">{banner[text]}</textarea>
                </td><td class="banner">{banner[view]}</td>
            </tr>
            [/each.banner]
        </table>
        <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
