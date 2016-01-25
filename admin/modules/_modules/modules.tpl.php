<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov
# Administration: modules management template

die();?>

<div class="module">__Modules__</div>
<fieldset>
    <form name="form" method="post" action="">
        <table class="std">
            <tr>
                <th>__Title__</th>
                <th>__Type__</th>
                <th>__Status__</th>
            </tr>
            <!-- FOREACH module = $modules -->
                <!-- IF !empty($module.system) -->
                    <tr class="dark">
                        <td>$module.title</td>
                        <td>__System module__</td>
                        <td class="center"><input type="hidden" name="enable[$module.module]" value="1" />__Enabled__</td>
                    </tr>
                <!-- ELSE -->
                    <tr class="light">
                        <td colspan="2">$module.title</td>
                        <td class="center"><input type="checkbox" name="enable[$module.module]" value="1" <!-- IF !empty($module.enabled) -->checked<!-- ENDIF --> /> __Enable__</td>
                    </tr>
                <!-- ENDIF -->
                <!-- IF !empty($module.ext) -->
                    <!-- FOREACH ext = $module.ext -->
                        <tr class="$ext.class">
                            <td>&emsp;&mdash; $ext.title</td>
                            <!-- IF !empty($ext.system) -->
                                <td> __System module extension__</td>
                                <td class="center"><input type="hidden" name="enable[$ext.module]" value="1" />__Enabled__</td>
                            <!-- ELSE -->
                                <td> __Module extension__</td>
                                <td class="center"><input type="checkbox" name="enable[$ext.module]" value="1" <!-- IF !empty($ext.enabled) -->checked<!-- ENDIF --> /> __Enable__</td>
                            <!-- ENDIF -->
                        </tr>
                    <!-- ENDFOREACH -->
                <!-- ENDIF -->
            <!-- ENDFOREACH -->
        </table>
        <p class="center"><input type="submit" name="save" value="__Save__" /></p>
    </form>
</fieldset>
