<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module POLLS: Poll template

die();?>

<!-- FOREACH poll = $polls -->
<div class="poll">
    <!-- IF $poll.voited==TRUE -->
        <table>
            <tr><th colspan="2" class="center">$poll.question</th></tr>
            <!-- FOREACH answer = $poll.answers -->
                <tr>
                    <td>$answer.answer</td>
                    <td class="result right">[$answer.voices:]%</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table>
                            <tr>
                                <td width="[$answer.voices:]%" style="white-space:nowrap;background:$answer.color;"></td>
                                <td class="color">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            <!-- ENDFOREACH -->
            <tr><td colspan="2" class="center">__Total votes__: $poll.total</td></tr>
        </table>
    <!-- ELSE -->
        <form name="poll" method="post" >
            <input type="hidden" name="poll" value="$poll.id" />
            <table>
                <tr><th colspan="2" class="center">$poll.question</th></tr>
                <!-- FOREACH answer = $poll.answers -->
                    <tr>
                        <td class="center"><input type="radio" name="answer" value="$answer.id" /></td>
                        <td>$answer.answer</td>
                    </tr>
                <!-- ENDFOREACH -->
            </table>
            <p class="center"><input type="submit" name="save" value="__Submit__" /></p>
        </form>
    <!-- ENDIF -->
    <div class="center"><a href="{MODULE}polls.archive">__Polls archive__</a></div>
</div>
<!-- ENDFOREACH -->
