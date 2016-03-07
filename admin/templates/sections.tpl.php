<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Sections management template

die();?>
<script type="text/javascript" src="{TOOLS}redips{DS}redips.drag.min.js"></script>
<script type="text/javascript" src="{TOOLS}redips{DS}drag.min.js"></script>
<div class="module">__Sections__</div>
<fieldset>
    <form name="sections" method="post"  class="form">
        <div id="redips-drag">
            <table class="sortable">
                <tr>
                    <th class="redips-mark"></th>
                    <th class="id">ID</th>
                    <th class="title">__Title__</th>
                    <th class="desc">__Description__</th>
                    <th class="access">__Access__</th>
                    <th class="actions">__Actions__</th>
                </tr>
                <!-- FOREACH section = $system -->
                    <tr class="light">
                        <td class="redips-mark"></td>
                        <td class="id">drafts</td>
                        <td class="title">$section.title</td>
                        <td class="desc">$section.desc</td>
                        <td class="access center">$section.access</td>
                        <td class="actions center">
                            <span class="button_edit">
                                 <button type="submit" name="edit" value="drafts" class="tip" class="icon icon-edit tip" title="__Edit__"></button>
                            </span>
                        </td>
                    </tr>
                <!-- ENDFOREACH -->
                <!-- FOREACH section = $sections -->
                    <tr class="$section.class">
                        <td class="redips-rowhandler"><div id="$section.id" class="redips-drag redips-row icon icon-move"></div></td>
                        <td class="id"><input type="hidden" name="ids[]" value="$section.id" />$section.id</td>
                        <td class="title"><input type="hidden" name="titles[]" value="$section.title" />$section.title</td>
                        <td class="desc"><input type="hidden" name="descs[]" value="$section.desc" />$section.desc</td>
                        <td class="access center"><input type="hidden" name="accesses[]" value="$section.access" />$section.access</td>
                        <td class="actions center">
                            <span class="button_edit">
                                <a href="{MODULE}admin&id=[$module:].sections&amp;edit=$section.id" class="icon icon-edit tip" title="__Edit__"></a>
                            </span>
                            <!-- IF !empty($section.delete) -->
                                <span class="button_delete">
                                    <a href="{MODULE}admin&id=[$module:].sectionss&amp;delete=$section.id" class="icon icon-delete tip" title="__Delete__"></a>
                                </span>
                            <!-- ENDIF -->
                        </td>
                    </tr>
                <!-- ENDFOREACH -->
            </table>
        </div>
        <p align="center">
            <input type="submit" name="new" value="__New section__" />
            <!-- IF !empty($sections) -->
                <input type="submit" name="action" value="__Save__" />
            <!-- ENDIF -->
        </p>
    </form>
</fieldset>
