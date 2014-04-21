<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - POSTS - SECTIONS TEMPLATE

die();?>
<script type="text/javascript" src="{TOOLS}redips-drag-min.js"></script>
<script type="text/javascript" src="{TOOLS}drag-min.js"></script>
<div class="module">[__Sections]</div>
<fieldset>
    <form name="sections" method="post" action="" class="form">
        <div id="drag">
            <table class="sortable">
                <tr>
                    <th class="mark"></th>
                    <th class="mark id">[__ID]</th>
                    <th class="mark title">[__Title]</th>
                    <th class="mark desc">[__Description]</th>
                    <th class="mark access">[__Access]</th>
                    <th class="mark actions">[__Actions]</th>
                </tr>
                <tr class="odd">
                    <td class="mark"></td>
                    <td class="mark id">drafts</td>
                    <td class="mark title">{drafts[title]}</td>
                    <td class="mark desc">{drafts[desc]}</td>
                    <td class="mark access center">{drafts[access]}</td>
                    <td class="mark actions center">
                        <span class="button_edit">
                             <button type="submit" name="edit" value="drafts" class="tip" title="[__Edit]">
                                <img src="{ICONS}edit.png" width="16" height="16" alt="[__Edit]" />
                            </button>
                        </span>
                    </td>
                </tr>
                [each=sections]
                    <tr class="{sections[class]}">
                        <td class="rowhandler">
                            <div id="{sections[id]}" class="drag row">
                                <img src="{ICONS}move.png" width="16" height="16" alt="[__Move]" />
                            </div>
                        </td>
                        <td class="id">
                            <input type="hidden" name="ids[]" value="{sections[id]}" />
                            {sections[id]}
                        </td>
                        <td class="title">
                            <input type="hidden" name="titles[]" value="{sections[title]}" />
                            {sections[title]}
                        </td>
                        <td class="desc">
                            <input type="hidden" name="descs[]" value="{sections[desc]}" />
                            {sections[desc]}
                        </td>
                        <td class="access center">
                            <input type="hidden" name="accesses[]" value="{sections[access]}" />
                            {sections[access]}
                        </td>
                        <td class="actions center">
                            <span class="button_edit">
                                <button type="submit" name="edit" value="{sections[id]}" class="tip" title="[__Edit]">
                                    <img src="{ICONS}edit.png" width="16" height="16" alt="[__Edit]" />
                                </button>
                            </span>
                            [if=sections[delete]]
                                <span class="button_delete">
                                    <button type="submit" name="delete" value="{sections[id]}" class="tip" title="[__Delete]">
                                        <img src="{ICONS}delete.png" width="16" height="16" alt="[__Delete]" />
                                    </button>
                                </span>
                            [endif]
                        </td>
                    </tr>
                [endeach.sections]
            </table>
        </div>
        <p class="center">
            [if=sections]<input type="submit" name="action" value="[__Save]" class="submit" />[endif]
            <span class="button add"><input type="submit" name="new" value="[__New section]" class="submit" /></span>
        </p>
    </form>
</fieldset>
