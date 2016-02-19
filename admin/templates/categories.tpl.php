<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Template for sections and categories

die();?>

<script type="text/javascript" src="{TOOLS}redips{DS}redips.drag.min.js"></script>
<script type="text/javascript" src="{TOOLS}redips{DS}drag.min.js"></script>
<div class="module">__Categories__</div>
<fieldset>
    <form name="sections" method="post" >
        <div id="redips-nodrag">
            <table>
                <tr>
                    <th class=""></th>
                    <th class="id">ID</th>
                    <th class="pic">__Icon__</th>
                    <th class="title">__Title__</th>
                    <th class="desc">__Description__</th>
                    <th class="access">__Access__</th>
                    <th class="actions">__Posts__</th>
                    <th class="actions">__Actions__</th>
                </tr>
                <!-- IF !empty($system) -->
                    <!-- FOREACH system = $system -->
                        <tr><td colspan="8" class="redips-mark"><div>$system.title</div></td></tr>
                        <!-- FOREACH category = $system.categories -->
                            <tr class="$category.class">
                                <td class="redips-rowhandler center"><div class="redips-nodrag"></div></td>
                                <td class="id center">$category.id</td>
                                <td class="pic center"><img src="[$category.path:]icon.png" width="35" height="35" alt="icon" /></td>
                                <td class="title">$category.title</td>
                                <td class="desc">$category.desc</td>
                                <td class="access center">$category.access</td>
                                <td class="actions center">$category.items</td>
                                <td class="actions center">
                                    <button type="submit" name="edit" value="$system.id.$category.id" class="icon icon-edit tip" title="__Edit__"></button>
                                    <!-- IF !empty($category.items) -->
                                        <a href="{MODULE}admin&id=[$module:].items{SECTION}$system.id{CATEGORY}$category.id" class="icon icon-posts tip" title="__Posts__"></a>
                                    <!-- ENDIF -->
                                </td>
                            </tr>
                        <!-- ENDFOREACH -->
                    <!-- ENDFOREACH -->
                <!-- ENDIF -->
            </table>
        </div>
        <div id="redips-drag">
            <table id="sortable">
            <!-- FOREACH section = $sections -->
                <tr><td colspan="8" class="redips-mark"><div id="$section.id" class="group">$section.title</div></td></tr>
                <tr style="line-height:2px;height:2px;"><td colspan="7" style="border:0;">&nbsp;</td></tr>
                <!-- FOREACH category = $section.categories -->
                    <tr class="$category.class">
                        <td class="redips-rowhandler"><div id="$section.id.$category.id" class="redips-drag redips-row icon icon-move"></div></td>
                        <td class="id center">$category.id</td>
                        <td class="pic center"><img src="[$category.path:]icon.png" width="35" height="35" alt="icon" /></td>
                        <td class="title">$category.title</td>
                        <td class="desc">$category.desc</td>
                        <td class="access center">$category.access</td>
                        <td class="actions center">$category.items</td>
                        <td class="actions center">
                            <a href="{MODULE}admin&id=[$module:].categories&amp;edit=$section.id.$category.id" class="icon icon-edit tip" title="__Edit__"></a>
                            <a href="{MODULE}admin&id=[$module:].items{SECTION}$section.id{CATEGORY}$category.id" class="icon icon-posts tip" title="__Posts__"></a>
                            <!-- IF empty($category.items) -->
                                <a href="{MODULE}admin&id=[$module:].categories&amp;delete=$section.id.$category.id" class="icon icon-delete tip" title="__Delete__"></a>
                            <!-- ENDIF -->
                        </td>
                    </tr>
                <!-- ENDFOREACH -->
            <!-- ENDFOREACH -->
            </table>
        </div>
        <div id="result"></div>
        <p class="center">
            <input type="submit" name="new" value="__New category__" />
            <!-- IF !empty($sections) -->
                <input type="submit" name="action" value="__Save__" onclick="save()" />
            <!-- ENDIF -->
        </p>
    </form>
</fieldset>
