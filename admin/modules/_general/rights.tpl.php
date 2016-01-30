<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Filemanager - file rights template

die();?>

<fieldset>
    <form name="form1" method="post" action="">
    <table class="std">
        <tr><th colspan="3">__Rights__</th></td></tr>
        <tr>
            <td class="dark center" colspan="3">
            <!-- IF !empty($dir) -->
                __Directory__
            <!-- ELSE -->
                __File__
            <!-- ENDIF -->
                : <span class="special">$file</span>
            </td>
        </tr>
        <tr>
            <td class="row1 center">__Owner__</td>
            <td class="row1 center">__Group__</td>
            <td class="row1 center">__Other__</td>
        </tr>
        <tr>
            <td class="center">
                <input type="checkbox" name="rights[0]" value="r" <!-- IF !empty($owner_r) -->checked<!-- ENDIF --> /> __Reading__
                <input type="checkbox" name="rights[1]" value="w" <!-- IF !empty($owner_w) -->checked<!-- ENDIF --> /> __Writing__
                <input type="checkbox" name="rights[2]" value="x" <!-- IF !empty($owner_x) -->checked<!-- ENDIF --> /> __Executing__
            </td>
            <td class="center">
                <input type="checkbox" name="rights[3]" value="r" <!-- IF !empty($group_r) -->checked<!-- ENDIF --> /> __Reading__
                <input type="checkbox" name="rights[4]" value="w" <!-- IF !empty($group_w) -->checked<!-- ENDIF --> /> __Writing__
                <input type="checkbox" name="rights[5]" value="x" <!-- IF !empty($group_x) -->checked<!-- ENDIF --> /> __Executing__
            </td>
            <td class="center">
                <input type="checkbox" name="rights[6]" value="r" <!-- IF !empty($other_r) -->checked<!-- ENDIF --> /> __Reading__
                <input type="checkbox" name="rights[7]" value="w" <!-- IF !empty($other_w) -->checked<!-- ENDIF --> /> __Writing__
                <input type="checkbox" name="rights[8]" value="x" <!-- IF !empty($other_x) -->checked<!-- ENDIF --> /> __Executing__
            </td>
        </tr>
    </table>
    <p align="center">
        <!-- IF !empty($dir) -->
            <input type="checkbox" name="recursively" value="1" /> __Recursively__<br />
        <!-- ENDIF -->
        <input type="hidden" name="file" value="$file" />
        <input type="submit" name="save" value="__Save__" />
        <input type="reset" value="__Reset__" />
        <input type="submit" value="__Back__" onclick="javascript:history.back();" />
    </p>
    </form>
</fieldset>
