<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE USER - PROFILE VIEW

die();?>
<table class="std">
    <tr class="even"><th colspan="2"><img src="{avatar}" hspace="5" vspace="5" alt="[__Avatar]" /></th></tr>
    <tr class="even"><th>[__Username]</th><td>{username}</td></tr>
    <tr class="even"><th>[__Nick]</th><td>{nickname}</td></tr>
    <tr class="even"><th>[__Personal status]</th><td>{status}</td></tr>
    [if=stars]<tr class="even"><th>[__Rate]</th><td>{stars}</td></tr>[endif]
    <tr class="even"><th>[__Registration]</th><td>{regdate}</td></tr>
    <tr class="even"><th>[__Last visit]</th><td>{lastvisit}</td></tr>
    <tr class="even"><th>[__Visits]</th><td>{visits}</td></tr>
    <tr class="even"><th>[__Posts]</th><td>{posts}</td></tr>
    <tr class="even"><th>[__Comments]</th><td>{comments}</td></tr>
    <tr class="even"><th>[__Topics]</th><td>{topics}</td></tr>
    <tr class="even"><th>[__Replies]</th><td>{replies}</td></tr>
    <tr class="even"><th>ICQ</th><td>{icq}</td></tr>
    <tr class="even"><th>[__Website]</th><td>{website}</td></tr>
    <tr class="even"><th>[__Country]</th><td>{country}</td></tr>
    <tr class="even"><th>[__City]</th><td>{city}</td></tr>
    [if=blocked]<tr class="even"><th>[__Blocked]</th><td>{blocked}</td></tr>[endif]
    [if=allow_pm]
        <tr class="even"><td colspan="2"><p class="center"><a href="{MODULE}user.pm&amp;for={username}">[__Private message]</a></p></td></tr>
    [endif]
</table>
