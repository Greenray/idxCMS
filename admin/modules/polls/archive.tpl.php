<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - POLLS - ARCHIVE TEMPLATE

die();?>
<div class="module">[__Polls]</div>
<fieldset>
    [each=polls]
        <form name="polls{polls[id]}" method="post" action="">
            <input type="hidden" name="poll" value="{polls[id]}" />
            <table class="std">
                <tr><th colspan="3">{polls[question]}</th></tr>
                [each=polls[answers]]
                    <tr>
                        <td class="question" colspan="2">{answers[answer]}</td>
                        <td style="width:200px;">{answers[voices]}%</td>
                    </tr>
                <tr>
                    <td colspan="3">
                        <table class="graph" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="{answers[voices]}%" height="5" style="white-space:nowrap;background:{answers[color]};"></td>
                                <td height="5"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                [endeach.polls[answers]]
                <tr><td class="odd" colspan="3" style="text-align:center;">[__Total votes]: {polls[total]}</td></tr>
            </table>
            <p align="center"><input type="submit" name="remove" value="[__Delete]" class="submit" /></p>
        </form>
    [endeach.polls]
</fieldset>
