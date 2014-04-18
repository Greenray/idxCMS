<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE POSTS - POST FORM FOR USER

die();?>
<div class="center">[__Post]</div>
<div class="center">[__Your article will be published after premoderation]</div>
<fieldset>
    <form name="post" method="post" action="">
        <table class="std">
            <tr class="odd">
                <td class="label">[__Section]</td>
                <td colspan="3"><b>{section_title}</b></td>
            </tr>
            <tr class="odd">
                <td>[__Category]</td>
                <td colspan="3"><b>{category_title}</b></td>
            </tr>
            <tr class="odd">
                <td>[__Title]</td>
                <td colspan="3"><input type="text" name="title" value="{title}" size="50" class="required" onfocus="if (this.value == '{title}') {this.value = '';}" onblur="if (this.value == '') {this.value = '{title}';}" required="required" /></td>
            </tr>
            <tr class="odd">
                <td>[__Keywords]</td>
                <td colspan="3"><input type="text" class="text" id="keywords" name="keywords"  size="50"  value="{keywords}" /></td>
            </tr>
            <tr>
                <td colspan="4">
                    <div style="text-align:center;">
                        <p>
                            <a href="#post" onclick="document.getElementById('shdesc').style.display=ShowHide(document.getElementById('shdesc').style.display)">
                                [__Description]
                            </a>
                        </p>
                    </div>
                    <div id="shdesc" style="display:none;">
                        {bbCodes_desc}
                        <textarea id="desc" name="desc" cols="80" rows="5" >{desc}</textarea>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div style="text-align:center;">[__Text]</div>
                    {bbCodes_text}
                    <textarea id="text" name="text" cols="80" rows="25">{text}</textarea>
                </td>
            </tr>
            <tr class="odd">
                <td>[__Comments]</td>
                <td colspan="3">
                    <input type="checkbox" name="opened" value="1" id="opened" [if=opened]checked="checked"[endif] />
                    <label for="opened"> [__Allow]</label>
                </td>
            </tr>
        </table>
        <input type="hidden" name="item" value="{item}" />
        <p class="center">
            <input type="reset" value="[__Reset]" class="submit" />
            <input type="submit" name="save" value="[__Save]" class="submit" />
        </p>
    </form>
</fieldset>
