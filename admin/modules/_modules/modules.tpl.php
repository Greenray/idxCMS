<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: modules management template

die();?>

<div class="module">__Modules__</div>
<fieldset>
    <form name="form" method="post" >
        <table class="std">
            <tr>
                <th>__Title__</th>
                <th>__Type__</th>
                <th colspan="2">__Status__</th>
            </tr>
            <!-- FOREACH module = $modules -->
                <!-- IF !empty($module.system) -->
                    <tr class="dark">
                        <td>$module.title</td>
                        <td>__System module__</td>
                        <td class="center"><input type="hidden" name="enable[$module.module]" value="1" />__Enabled__</td>
                        <td class="center"><input type="checkbox" name="cache[$module.module]" value="1" <!-- IF !empty($module.cached) -->checked<!-- ENDIF --> /> __Cache__</td>
                    </tr>
                <!-- ELSE -->
                    <tr class="light">
                        <td colspan="2">$module.title</td>
                        <td class="center"><input type="checkbox" name="enable[$module.module]" value="1" <!-- IF !empty($module.enabled) -->checked<!-- ENDIF --> /> __Enable__</td>
                        <td class="center"><input type="checkbox" name="cache[$module.module]" value="1" <!-- IF !empty($module.cached) -->checked<!-- ENDIF --> /> __Cache__</td>
                    </tr>
                <!-- ENDIF -->
                <!-- IF !empty($module.ext) -->
                    <!-- FOREACH ext = $module.ext -->
                        <tr class="$ext.class">
                            <td>&emsp;&mdash; $ext.title</td>
                            <!-- IF !empty($ext.system) -->
                                <td> __System module extension__</td>
                                <td class="center"><input type="hidden" name="enable[$ext.module]" value="1" />__Enabled__</td>
                                <td class="center"><input type="checkbox" name="cache[$ext.module]" value="1" <!-- IF !empty($ext.cached) -->checked<!-- ENDIF --> /> __Cache__</td>
                            <!-- ELSE -->
                                <td> __Module extension__</td>
                                <td class="center"><input type="checkbox" name="enable[$ext.module]" value="1" <!-- IF !empty($ext.enabled) -->checked<!-- ENDIF --> /> __Enable__</td>
                                <td class="center"><input type="checkbox" name="cache[$ext.module]" value="1" <!-- IF !empty($ext.cached) -->checked<!-- ENDIF --> /> __Cache__</td>
                            <!-- ENDIF -->
                        </tr>
                    <!-- ENDFOREACH -->
                <!-- ENDIF -->
            <!-- ENDFOREACH -->
        </table>
        <p class="center"><input type="submit" name="save" value="__Save__" /></p>
    </form>
</fieldset>
