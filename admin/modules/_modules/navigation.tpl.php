<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - NAVIGATION TEMPLATE

die();?>
<script type="text/javascript" src="{TOOLS}redips-drag-min.js"></script>
<script type="text/javascript" src="{TOOLS}drag-min.js"></script>
<div class="module">[__Navigation panel]</div>
<fieldset>
    <form name="navigation" method="post" action="">
        <div id="drag">
            <table id="sortable">
                <tr>
                    <th></th>
                    <th>[__Link]</th>
                    <th>[__Title] / [__Description]</th>
                    <th class="title">[__Icon]</th>
                </tr>
                [each=links]
                    <tr>
                        <td class="rowhandler"><div id="{links[link]}" class="drag row"><img src="{ICONS}move.png" width="16" height="16" alt="[__Move]" /></div></td>
                        <td style="width:23%;"><input type="text" id="links[]" name="links[]" value="{links[link]}" size="25" class="text" /></td>
                        <td style="width:60%;">
                            <input type="text" id="names[]" name="names[]" value="{links[name]}" size="20" class="text" />
                            <input type="text" id="descs[]" name="descs[]" value="{links[desc]}" size="50" class="text" />
                        </td>
                        <td style="width:17%;">
                            <select name="icons[]">
                                [each=links[icons]]
                                    <option value="{icons[id]}" [if=icons[selected]]selected="selected"[endif]>{icons[id]}</option>
                                [endeach.links[icons]]
                            </select>
                        </td>
                    </tr>
                [endeach.links]
            </table>
        </div>
        <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
