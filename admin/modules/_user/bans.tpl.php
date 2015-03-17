<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - User
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>

<div class="module">[__Bans]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            [each=ban]<tr class="odd"><td><input type="text" name="ban[]" value="{ban}" size="80" class="text" /></td></tr>[endeach.ban]
            <tr class="odd"><td><input type="text" name="ban[]" value="" size="80" class="text" /></td></tr>
        </table>
        <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
