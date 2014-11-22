<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE POLLS - POLLS ARCHIVE TEMPLATE

die();?>
<table class="std">
    <tr><th colspan="2">{question}</th></tr>
    [each=answers]
        <tr>
            <td class="question">{answers[answer]}</td>
            <td class="right" style="width:50px;">{answers[voices]}%</td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="graph" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="{answers[voices]}%" height="5" style="white-space:nowrap;background:{answers[color]};"></td>
                        <td height="5"></td>
                    </tr>
                </table>
            </td>
        </tr>
    [endeach.answers]
    <tr><td class="odd center" colspan="2">[__Total votes]: {total}</td></tr>
</table>
