<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - BANS TEMPLATE

die();?>
<div class="module">[__Bans]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            [each=ban]
                <tr class="odd"><td><input type="text" name="ban[]" value="{ban}" size="80" class="text" /></td></tr>
            [endeach.ban]
            <tr class="odd"><td><input type="text" name="ban[]" value="" size="80" class="text" /></td></tr>
        </table>
        <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
