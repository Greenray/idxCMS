<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - POSTS - CATEGORIES TEMPLATE

die();?>

<script type="text/javascript" src="{TOOLS}redips.drag.min.js"></script>
<script type="text/javascript" src="{TOOLS}drag.min.js"></script>
<div class="module">[__Categories]</div>
<fieldset>
    <form name="sections" method="post" action="">
        <div id="nodrag">
            <table>
                <tr>
                    <th></th>
                    <th class="id">ID</th>
                    <th class="icon">[__Icon]</th>
                    <th class="title">[__Title]</th>
                    <th class="desc">[__Description]</th>
                    <th class="access">[__Access]</th>
                    <th class="actions">[__Actions]</th>
                </tr>
                [each=system]
                    <tr><td colspan="7" class="header"><div>{system[title]}</div></td></tr>
                    [each=system[categories]]
                        <tr class="{categories[class]}">
                            <td class="rowhandler"><div class="nodrag"></div></td>
                            <td class="id center">{categories[id]}</td>
                            <td class="icon center"><img src="{categories[path]}icon.png" width="35" height="35" alt="icon" /></td>
                            <td class="title">{categories[title]}</td>
                            <td class="desc">{categories[desc]}</td>
                            <td class="access center">{categories[access]}</td>
                            <td class="actions center">
                                <button type="submit" name="edit" value="{system[id]}.{categories[id]}" title="[__Edit]">
                                    <img src="{ICONS}edit.png" width="16" height="16" alt="[__Edit]" />
                                </button>
                                [if=categories[posts]]
                                    <a href="./?module=admin&amp;id=posts.posts&amp;section={system[id]}&amp;category={categories[id]}">
                                        <img src="{ICONS}posts.png" width="16" height="16" alt="[__Posts]" />
                                    </a>
                                [endif]
                            </td>
                        </tr>
                    [endeach.system[categories]]
                [endeach.system]
            </table>
        </div>
        <div id="drag">
            <table id="sortable">
                [each=sections]
                    <tr>
                        <td colspan="7" class="mark header"><div id="{sections[id]}" class="group">{sections[title]}</div></td>
                    </tr>
                    <tr style="line-height:2px;height:2px;">
                        <td colspan="7" style="border:0;">&nbsp;</td>
                    </tr>
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
                                <button type="submit" name="edit" value="{sections[id]}.{categories[id]}" title="[__Edit]">
                                    <img src="{ICONS}edit.png" width="16" height="16" alt="[__Edit]" />
                                </button>
                                [ifelse=categories[posts]]
                                    <a href="./?module=admin&amp;id=posts.posts&amp;section={sections[id]}&amp;category={categories[id]}">
                                        <img src="{ICONS}posts.png" width="16" height="16" alt="[__Posts]" />
                                    </a>
                                [else]
                                    <button type="submit" name="delete" value="{sections[id]}.{categories[id]}" title="[__Delete]">
                                        <img src="{ICONS}delete.png" width="16" height="16" alt="[__Delete]" />
                                    </button>
                                [endelse]
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
            <button formaction="{MODULE}admin&amp;id=posts.posts&amp;new=1" class="submit">[__Post]</button>
        </p>
    </form>
</fieldset>
