<?php
# idxCMS version 2.4
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# TEMPLATE FOR ITEM PRINTING

die();?>
<!DOCTYPE html>
<head>
    <title>{title} - [__Version for printer]</title>
    <link rel="stylesheet" href="{SKINS}print.css" type="text/css">
</head>
<body>
    <table width="90%">
        <tr>
            <td colspan="3" align="justify">
                <h1>{title}</h1>
                <div class="right">{date}</div>
                <p>[__Author]: <strong>{nick}</strong></p>
                <div class="text justify">{text}</div>
                <hr />
            </td>
        </tr>
        [if=reps]
            [each=reps]
                <tr><td colspan="3" class="left"><p>[__Author]: <strong>{nick}</strong></p></td></tr>
                <tr><td colspan="3" align="justify">{text}<hr /></td></tr>
            [endeach=reps]
        [endif]
        <tr>
            <td class="left">{site}</td>
            <td class="center"><small>&copy; {copyright}</small></td>
            <td class="right">{current_time}</td>
        </tr>
    </table>
</body>
</html>
