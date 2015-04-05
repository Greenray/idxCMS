<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Polls
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

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
                <tr><td class="odd center" colspan="3">[__Total votes]: {polls[total]}</td></tr>
            </table>
            <p align="center">
                <input type="submit" name="stop" value="[__Stop]" class="submit" />
                <input type="submit" name="delete" value="[__Delete]" class="submit" />
            </p>
        </form>
    [endeach.polls]
    <form name="poll" method="post" action="">
        <table class="std">
            <tr><th colspan="3">[__New poll]</th></tr>
            <tr>
                <td align="left">[__Question]</td>
                <td align="left" class="row3" colspan="2"><input type="text" class="text" name="question" size="40" value="" /></td>
            </tr>
            <tr>
                <td align="left">[__Answers]</td>
                <td align="left" class="row3" colspan="2"><textarea id="variants" name="variants" cols="70" rows="5"/></textarea></td>
            </tr>
        </table>
        <p align="center">
            <input type="reset" value="[__Reset]" class="submit" />
            <input type="submit" name="new" value="[__Save]" class="submit" />
        </p>
    </form>
</fieldset>
