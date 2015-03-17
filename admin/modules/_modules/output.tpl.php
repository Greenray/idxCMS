<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Modules
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>

<div class="module">[__Output management]</div>
<script>
    function CheckString(inputString, needle, startingIndex) {
      if (!startingIndex) startingIndex = 0;
      return inputString.indexOf(needle);
    }
    is_firefox = CheckString(navigator.userAgent, 'Firefox');
    is_ie      = CheckString(navigator.userAgent, 'MSIE');
    is_opera   = CheckString(navigator.userAgent, 'Opera');
    var section = 0;     // Flag of moving of a category entirely
    function GetSelectedIndexes(object) {
        var selectedIndexes = new Array;
        for (var i = 0; i < object.options.length; i++) {
            // If the element is selected...
            if (object.options[i].selected) {
                // ...if this is a category...
                if (object.options[i].value.substring(0,1) !== '/') {
                    // ...we add an element index into array
                    selectedIndexes.push(i);
                } else {
                    // Selected element section,
                    // so if it the first in the list of selected elements,
                    // we add it's index into array
                    if (selectedIndexes.length === 0) {
                        selectedIndexes.push(i);
                        var j = 1;
                        while (((i+j) <= (object.options.length-1)) && (object.options[i+j].value.substring(0,1) === '>')) {
                            selectedIndexes.push(j);
                            j++;
                        }
                        section = 1;
                        return selectedIndexes;
                    } else return selectedIndexes;   // The section is below of the chosen category, so in a array isn't added.
                }
            }
        }
        return selectedIndexes;
    }
    // If the section is selected, categories are displaced together with it irrespective of a choice
    function ShiftObjectUp(object, objSelectedIndexes) {
        var selectedElements = objSelectedIndexes.length;              // Number of xelected elements
        if (section === 1) {
            var prevSectionIndex = object.selectedIndex - 1;           // Next section
            var nextSection = new Array();
            var j = 0;                                                 // Number of elements
            // We search for the first element in the list of categories of this section
            while (object.options[prevSectionIndex].value.substring(0,1) === '>') {
                nextSection[prevSectionIndex] = object.options[prevSectionIndex].value;
                prevSectionIndex--;
                j++;
            }
            if (object.options[prevSectionIndex].value.substring(0,1) === '/') {
                j++;
            }
            // The element is found
            for (i = 0; i < selectedElements; i++) {
                object.insertBefore(object.options[objSelectedIndexes[0] + i], object.options[prevSectionIndex + i]);
            }
            return;
        } else {
            if (selectedElements === 1) {
                var currOption = object.options[objSelectedIndexes[0]];        // Current element
                var nextOption = object.options[objSelectedIndexes[0] - 1];    // Next element
                var sectOption = object.options[objSelectedIndexes[0] - 2];    // Element which can appear section
                // Element can be moved between sections too
                if (currOption.value.substring(0,1) !== '>') {
                    // Moving of categories between sections we will forbid while
                    if (nextOption.value.substring(0,1) === '/' ) {
                        nextOption = object.options[objSelectedIndexes[0]];
                    }
                // Post can't be in section, only in category
                } else if (sectOption.value.substring(0,1) === '/' ) {
                    nextOption = object.options[objSelectedIndexes[0] - 2];
                }
                // New position of the moving element
                object.insertBefore(currOption, nextOption);
            } else {
                // Moving of elements group
                for (i = 0; i < selectedElements; i++) {
                    currOption = object.options[objSelectedIndexes[i]];
                    nextOption = object.options[objSelectedIndexes[0] - (selectedElements - 1) + i];
                    object.insertBefore(currOption, nextOption);
                }
            }
        }
        return;
    }
    function ShiftObjectDown(object, objSelectedIndexes) {
        var selectedElements = objSelectedIndexes.length;
        if (section === 1) {
            var nextSectionIndex = object.selectedIndex + selectedElements;
            if (object.options[nextSectionIndex].value.substring(0,1) === '/') {
                var nextSection = new Array();
                nextSectionIndex++;
                var j = 0;
                // We search for the last element in the list of categories of this section
                while ((nextSectionIndex < object.options.length) && (object.options[nextSectionIndex].value.substring(0,1) === '>')) {
                    nextSection[nextSectionIndex] = object.options[nextSectionIndex].value;
                    nextSectionIndex++;
                    j++;
                }
            }
            for (i = 0; i <= j; i++) {
                object.insertBefore(object.options[nextSectionIndex - 1], object.options[objSelectedIndexes[0]]);
            }
            return;
        } else {
            if (selectedElements === 1) {
                var currOption = object.options[objSelectedIndexes[0]];
                var nextOption = object.options[objSelectedIndexes[0] + 2];
                var sectOption = object.options[objSelectedIndexes[0] + 1];
                if (currOption.value.substring(0,1) !== '>') {
                    if (object.options[objSelectedIndexes[0] + 1].value.substring(0,1) === '/' ) {
                        nextOption = object.options[objSelectedIndexes[0]];
                    }
                } else if (sectOption.value.substring(0,1) === '/' ) {
                        nextOption = object.options[objSelectedIndexes[0] + 3];
                }
                object.insertBefore(currOption, nextOption);
            } else {
                for (i = 0; i < selectedElements; i++) {
                    currOption = object.options[objSelectedIndexes[0]];
                    nextOption = object.options[objSelectedIndexes[i] + (selectedElements + 1) - i];
                    object.insertBefore(currOption, nextOption);
                }
            }
        }
        return;
    }
    function MoveUp(object) {
        if (object.selectedIndex !== -1) {
            object.options[object.selectedIndex-1].text  = object.options[object.selectedIndex].text;
            object.options[object.selectedIndex-1].value = object.options[object.selectedIndex].value;
            object.options[object.selectedIndex].text    = object.options[object.selectedIndex-1].text;
            object.options[object.selectedIndex].value   = object.options[object.selectedIndex-1].value;
            object.selectedIndex = object.selectedIndex - 1;
        }
        return;
    }
    function MoveDown(object) {
        if (object.selectedIndex !== -1) {
            object.options[object.selectedIndex+1].text  = object.options[object.selectedIndex].text;
            object.options[object.selectedIndex+1].value = object.options[object.selectedIndex].value;
            object.options[object.selectedIndex].text    = object.options[object.selectedIndex+1].text;
            object.options[object.selectedIndex].value   = object.options[object.selectedIndex+1].value;
            object.selectedIndex = object.selectedIndex + 1;
        }
        return;
    }
    // Moving of elements between lists
    function AddObject(from, to) {
        var newoption = document.createElement('option');
        if (from.selectedIndex !== -1) {
            newoption.text  = from.options[from.selectedIndex].text;
            newoption.value = from.options[from.selectedIndex].value;
            if (is_ie === -1)
                 to.add(newoption, null);
            else to.add(newoption, 0);
            from.remove(from.selectedIndex);
        }
        return;
    }
    function SortValues(object, direction) {
        if (object.selectedIndex !== -1) {
            var selIndexes = new Array();
            selIndexes = GetSelectedIndexes(object);
            if (direction === 'up') {
                ShiftObjectUp(object, selIndexes);
            }
            if (direction === 'down') {
                ShiftObjectDown(object, selIndexes);
            }
        }
        section = 0;
        return;
    }
    // Duplicate element in the list
    function Duplicate(object) {
        var newelement = document.createElement('option');
        if (object.selectedIndex !== -1) {
            newelement.text  = object.options[object.selectedIndex].text;
            newelement.value = object.options[object.selectedIndex].value;
            if (is_ie === -1)
                 object.add(newelement, null);
            else object.add(newelement, 0);
        }
        return;
    }
    function Remove(object) {
        if (object.selectedIndex !== -1) {
            object.remove(object.selectedIndex);
        }
        return;
    }
    // Save the result of sorting
    function SaveList(object) {
        if (object !== null) {
            object.multiple = true;
            i = 0;
            for (i = 0; i < object.options.length; i++) {
                object.options[i].selected = true;
            }
            return true;
        }
        return false;
    }
</script>
<fieldset>
    <form name="output" method="post" action="" onsubmit="SaveList(document.output.elements['active[]'])">
        <table>
            <tr class="odd">
                <td>[__Skin]</td>
                <td colspan="2"><input type="hidden" name="skin" value="{skin}" />{skin}</td>
            </tr>
            <tr>
                <th class="center" style="width:45%;">[__Included modules]</th>
                <td style="width:10%">&nbsp;</td>
                <th class="center" style="width:45%;">[__Excluded modules]</th>
            </tr>
            <tr>
                <td class="center" style="width:45%;padding:0">
                    <select name="active[]" size="30" style="width:100%" multiple>
                        [foreach=active.key.desc]<option value="{key}">{desc}</option>[endforeach.active]
                    </select>
                </td>
                <td class="center" style="width:10%;">
                    <input type="button" id="add" name="add" value="&lt; [__Include]" onclick="AddObject(document.output.elements['unused[]'], document.output.elements['active[]'])" />
                    <input type="button" id="del" name="del" value="[__Exclude] &gt;" onclick="AddObject(document.output.elements['active[]'], document.output.elements['unused[]'])" />
                </td>
                <td class="center" style="width:45%;padding:0">
                    <select name="unused[]" size="30" style="width:100%" multiple>
                        [foreach=unused.key.desc]<option value="{key}">{desc}</option>[endforeach.unused]
                    </select>
                </td>
            </tr>
            <tr>
                <td class="center" style="width:45%;">
                    <input type="button" id="up" name="up" value="[__Up]" onclick="SortValues(document.output.elements['active[]'], 'up')" />
                    <input type="button" id="down" name="down" value="[__Down]" onclick="SortValues(document.output.elements['active[]'], 'down')" />
                    <input type="button" id="duplicate" name="duplicate" value="[__Duplicate]" onclick="Duplicate(document.output.elements['active[]'], document.output.elements['active[]'])" />
                    <input type="button" id="remove" name="remove" value="[__Delete]" onclick="Remove(document.output.elements['active[]'])" />
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td class="left" colspan="3">
                    [__In this form you can define the positions of modules on pages.<br />- "Page" - site page;<br />- "Box" - is the module which will be displayed on page.<br />You can move elements between windows, and also establish display of the same module to different pages.]
                </td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
