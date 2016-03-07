<?php
# idxCMS Flat Files Content Management System v3.3
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Polls template.

die();?>
<div class="module">__Polls__</div>
<fieldset>
<!-- FOREACH poll = $polls -->
    <form name="polls$poll.id" method="post" >
        <input type="hidden" name="poll" value="$poll.id" />
        <table class="std">
            <tr><th colspan="3">$poll.question</th></tr>
            <!-- FOREACH answer = $poll.answers -->
                <tr>
                    <td class="question" colspan="2">$answer.answer</td>
                    <td style="width:200px;">[$answer.voices:]%</td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table class="graph">
                            <tr>
                                <td width="[$answer.voices:]%" height="5" style="white-space:nowrap;background:$answer.color;"></td>
                                <td height="5"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            <!-- ENDFOREACH -->
            <tr><td class="light center" colspan="3">__Total votes__: $poll.total</td></tr>
        </table>
        <p align="center">
            <input type="submit" name="stop" value="__Stop__" />
            <input type="submit" name="delete" value="__Delete__" />
        </p>
    </form>
<!-- ENDFOREACH -->
    <form name="poll" method="post" >
        <table class="std">
            <tr><th colspan="3">__New poll__</th></tr>
            <tr>
                <td align="left">__Question__</td>
                <td align="left" class="row3" colspan="2"><input type="text" name="question" size="40" value="" /></td>
            </tr>
            <tr>
                <td align="left">__Answers__</td>
                <td align="left" class="row3" colspan="2"><textarea id="variants" name="variants" cols="70" rows="5"/></textarea></td>
            </tr>
        </table>
        <p align="center">
            <input type="reset" value="__Reset__" />
            <input type="submit" name="new" value="__Save__" />
        </p>
    </form>
</fieldset>
