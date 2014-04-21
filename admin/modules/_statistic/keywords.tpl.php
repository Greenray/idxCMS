<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - KEYWORDS TEMPLATE

die();?>
<div class="module">[__Keywords info]</div>
<fieldset>
    <table class="std">
        <tr><th>[__Search bots]</th><th>[__Keywords]</th><th>[__Page]</th><th>[__Counter]</th></tr>
        [each=word]
            <tr class="odd"><td>{word[0]}</td><td>{word[1]}</td><td>{word[2]}</td><td>{word[count]}</td></tr>
        [endeach.word]
    </table>
    <form name="clean" method="post" action="">
        <p align="center"><input type="submit" name="clean" value="[__Clean]" class="submit" /></p>
    </form>
</fieldset>
