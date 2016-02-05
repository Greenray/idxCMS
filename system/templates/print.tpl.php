<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Template for item printing

die();?>

<!DOCTYPE html>
<html lang="$locale">
<head>
    <meta charset="UTF-8">
    <title>$title - __Version for printer__</title>
    <link rel="stylesheet" type="text/css" href="{SKINS}print.css" />
</head>
<body>
<table width="90%">
    <tr>
        <td colspan="3" align="justify">
            <h1>$title</h1>
            <div class="right">$date</div>
            <p>__Author__: <strong>$nick</strong></p>
            <div class="text justify">$text</div>
            <hr />
        </td>
    </tr>
    <!-- IF !empty($reps) -->
        <!-- FOREACH rep = $reps -->
            <tr><td colspan="3" class="left"><p>__Author__: <strong>$nick</strong></p></td></tr>
            <tr><td colspan="3" align="justify">$text<hr /></td></tr>
        <!-- ENDFOREACH -->
    <!-- ENDIF -->
    <tr>
        <td class="left">$site</td>
        <td class="center"><small>&copy; $copyright</small></td>
        <td class="right">$current_time</td>
    </tr>
</table>
</body>
</html>
