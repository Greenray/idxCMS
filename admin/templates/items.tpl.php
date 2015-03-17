<?php
# idxCMS Flat Files Content Management Sysytem
# Administration
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>

<div class="module">[__Topics]</div>
<fieldset>
    <form name="topics" method="post" action="">
        <table class="std">
            <tr class="even">
                <td>
                    <input type="hidden" name="section" value="{section_id}" />
                    [__Section]: <b>{section_title}</b>
                </td>
                <td colspan="8">
                    <input type="hidden" name="category" value="{category_id}" />
                    [__Category]: <b>{category_title}</b>
                </td>
            </tr>
            <tr>
                <th class="title">[__Title]</th>
                <th class="author">[__Date]</th>
                <th class="author">[__Author]</th>
                [if=items[file]]
                    <th>[__File]</th>
                    <th>[__Size]</th>
                    <th>[__Downloads]</th>
                [endif]
                [if=items[song]]
                    <th>[__File]</th>
                    <th>[__Size]</th>
                    <th>[__Downloads]</th>
                [endif]
                [if=items[site]]
                    <th>[__Site URL]</th>
                    <th>[__Transitions]</th>
                [endif]
                <th>[__Views]</th>
                <th>[__Comments]</th>
                <th class="actions">[__Actions]</th>
            </tr>
            [each=items]
                <tr class="odd">
                    <td style="padding:0 10px;">{items[title]}</td>
                    <td class="center">{items[date]}</td>
                    <td class="author">{items[nick]}</td>
                    [if=items[file]]
                        <td>{items[file]}</td>
                        <td class="right">{items[size]}</td>
                        <td class="center">{items[downloads]}</td>
                    [endif]
                    [if=items[song]]
                        <td>{items[song]}</td>
                        <td class="right">{items[size]}</td>
                        <td class="center">{items[downloads]}</td>
                    [endif]
                    [if=items[site]]
                        <td class="left">{items[site]}</td>
                        <td class="center">{items[clicks]}</td>
                    [endif]
                    <td class="center">{items[views]}</td>
                    <td class="center">{items[comments]}</td>
                    <td class="actions center">
                        <button type="submit" name="{items[action]}" value="{items[id]}" class="tip" title="{items[command]}">
                            <img src="{ICONS}{items[action]}.png" width="16" height="16" alt="{items[command]}" />
                        </button>
                        <button type="submit" name="edit" value="{items[id]}" class="tip" title="[__Edit]">
                            <img src="{ICONS}edit.png" width="16" height="16" alt="[__Edit]" />
                        </button>
                        <button type="submit" name="delete" value="{items[id]}" class="tip" title="[__Delete]">
                            <img src="{ICONS}delete.png" width="16" height="16" alt="[__Delete]" />
                        </button>
                    </td>
                </tr>
            [endeach.items]
        </table>
        <p class="center"><input type="submit" name="new" value="[__New post]" class="submit" /></p>
    </form>
</fieldset>
