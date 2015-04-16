<?php
# idxCMS Flat Files Content Management Sysytem
# Module Minichat
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="poll">
    [ifelse=voited]
        <table>
            <tr><th colspan="2" class="center">{question}</th></tr>
            [each=answers]
                <tr><td>{answers[answer]}</td><td class="right" style="width:35px">{answers[voices]}%</td></tr>
                <tr>
                    <td colspan="2">
                        <table cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="{answers[voices]}%" height="5" style="white-space:nowrap;background:{answers[color]};"></td>
                                <td height="5"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            [/each.answers]
            <tr><td colspan="2" class="center">[__Total votes]: {total}</td></tr>
        </table>
    [else]
        <form name="poll" method="post" action="">
            <input type="hidden" name="poll" value="{id}" />
            <table>
                <tr><th colspan="2" class="center">{question}</th></tr>
                [each=answers]
                    <tr><td><input type="radio" name="answer" value="{answers[id]}" /></td><td>{answers[answer]}</td></tr>
                [/each.answers]
            </table>
            <p class="center"><input type="submit" name="save" value="[__Submit]" class="submit" /></p>
        </form>
    [/else]
    <div class="center"><a href="{MODULE}polls.archive">[__Polls archive]</a></div>
</div>
