<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Statistic
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="module">[__Keywords info]</div>
<fieldset>
    <table class="std">
        <tr><th>[__Search bots]</th><th>[__Keywords]</th><th>[__Page]</th><th>[__Counter]</th></tr>
        [each=word]<tr class="odd"><td>{word[0]}</td><td>{word[1]}</td><td>{word[2]}</td><td>{word[count]}</td></tr>[endeach.word]
    </table>
    <form name="clean" method="post" action="">
        <p align="center"><input type="submit" name="clean" value="[__Clean]" class="submit" /></p>
    </form>
</fieldset>
