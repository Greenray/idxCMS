<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# ADMINISTRATION - CATEGORIES TEMPLATE

die();?>
<script type="text/javascript" src="{TOOLS}redips-drag-min.js"></script>
<script type="text/javascript" src="{TOOLS}drag-min.js"></script>
<div class="module">[__Categories]</div>
<fieldset>
    <form name="sections" method="post" action="">
        <div id="drag">
            <table id="sortable">
                <tr>
                    <th></th>
                    <th class="id">ID</th>
                    <th class="icon">[__Icon]</th>
                    <th class="title">[__Title]</th>
                    <th class="desc">[__Description]</th>
                    <th class="access">[__Access]</th>
                    <th class="actions">[__Actions]</th>
                </tr>
                [each=sections]
                    <tr><td colspan="7" class="mark header"><div id="{sections[id]}" class="group">{sections[title]}</div></td></tr>
                    <tr style="line-height:2px;height:2px;"><td colspan="7" style="border:0;">&nbsp;</td></tr>
                    [each=sections[categories]]
                        <tr class="{categories[class]}">
                            <td class="rowhandler">
                                <div id="{sections[id]}.{categories[id]}" class="drag row">
                                    <img src="{ICONS}move.png" width="16" height="16" alt="[__Move]" />
                                </div>
                            </td>
                            <td class="id center">{categories[id]}</td>
                            <td class="icon center"><img src="{categories[path]}icon.png" width="35" height="35" alt="icon" /></td>
                            <td class="title">{categories[title]}</td>
                            <td class="desc">{categories[desc]}</td>
                            <td class="access center">{categories[access]}</td>
                            <td class="actions center">
                                <button type="submit" name="edit" value="{sections[id]}.{categories[id]}" class="tip" title="[__Edit]">
                                    <img src="{ICONS}edit.png" width="16" height="16" alt="[__Edit]" />
                                </button>
                                <button formaction="{MODULE}admin&amp;id={module}.items&amp;section={sections[id]}&amp;category={categories[id]}" class="tip" title="[__Posts]">
                                    <img src="{ICONS}posts.png" width="16" height="16" alt="[__Posts]" />
                                </button>
                                [if=categories[delete]]
                                    <button type="submit" name="delete" value="{sections[id]}.{categories[id]}" class="tip" title="[__Delete]">
                                        <img src="{ICONS}delete.png" width="16" height="16" alt="[__Delete]" />
                                    </button>
                                [endif]
                            </td>
                        </tr>
                    [endeach.sections[categories]]
                [endeach.sections]
            </table>
        </div>
        <div id="result"></div>
        <p class="center">
            <input type="submit" name="new" value="[__New category]" class="submit" />
            [if=sections]<input type="submit" name="action" value="[__Save]" onclick="save()" class="submit" />[endif]
        </p>
    </form>
</fieldset>
