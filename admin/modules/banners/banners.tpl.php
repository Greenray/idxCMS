<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - BANNERS TEMPLATE

die();?>
<div class="module">[__Banners]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            [each=banner]
                <tr><td>{banner[bbCodes]}</td><td></td></tr>
                <tr>
                    <td><textarea id="{banner[id]}" name="text[]" cols="80" rows="10">{banner[text]}</textarea></td>
                    <td class="banner">{banner[view]}</td>
                </tr>
            [endeach.banner]
        </table>
        <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
