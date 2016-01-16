<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# Administration: Sections management template.

die();?>
<script type="text/javascript" src="{TOOLS}redips{DS}redips.drag.min.js"></script>
<script type="text/javascript" src="{TOOLS}redips{DS}drag.min.js"></script>
<div class="module">__Sections__</div>
<fieldset>
    <form name="sections" method="post" action="" class="form">
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
                                 <button type="submit" name="edit" value="drafts" class="tip" title="__Edit__">
                                    <img src="{ICONS}edit.png" width="16" height="16" alt="__Edit__" />
                                </button>
                            </span>
                        </td>
                    </tr>
                <!-- ENDFOREACH -->
                <!-- FOREACH section = $sections -->
                    <tr class="$section.class">
                        <td class="redips-rowhandler">
                            <div id="$section.id" class="redips-drag redips-row">
                                <img src="{ICONS}move.png" width="16" height="16" alt="__Move__" />
                            </div>
                        </td>
                        <td class="id"><input type="hidden" name="ids[]" value="$section.id" />$section.id</td>
                        <td class="title"><input type="hidden" name="titles[]" value="$section.title" />$section.title</td>
                        <td class="desc"><input type="hidden" name="descs[]" value="$section.desc" />$section.desc</td>
                        <td class="access center"><input type="hidden" name="accesses[]" value="$section.access" />$section.access</td>
                        <td class="actions center">
                            <span class="button_edit">
                                <button type="submit" name="edit" value="$section.id" class="tip" title="__Edit__">
                                    <img src="{ICONS}edit.png" width="16" height="16" alt="__Edit__" />
                                </button>
                            </span>
                            <!-- IF !empty($section.delete) -->
                                <span class="button_delete">
                                    <button type="submit" name="delete" value="$section.id" class="tip" title="__Delete__">
                                        <img src="{ICONS}delete.png" width="16" height="16" alt="__Delete__" />
                                    </button>
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
