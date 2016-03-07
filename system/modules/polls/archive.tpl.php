<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module POLLS: Archive template

die();?>

<table class="std">
    <tr><th colspan="2">$question</th></tr>
    <!-- FOREACH answer = $answers -->
        <tr>
            <td class="question">$answer.answer</td>
            <td class="right" style="width:50px;">[$answer.voices:]%</td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="graph">
                    <tr>
                        <td width="[$answer.voices:]%" height="5" style="white-space:nowrap;background:$answer.color;"></td>
                        <td height="5"></td>
                    </tr>
                </table>
            </td>
        </tr>
    <!-- ENDFOREACH -->
    <tr><td class="light center" colspan="2">__Total votes__: $total</td></tr>
</table>
