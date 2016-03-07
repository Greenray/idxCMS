<?php
# idxCMS Flat Files Content Management System v4.0
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Main configuration template.

die();?>

<script>
    var curselectorinput;
    function selectColor(color) {
        document.getElementById(curselectorinput).value = color;
        if (document.all) {
            document.getElementById(curselectorinput + "btn").style.background = color;
        } else {
            if (document.getElementById) {
                document.getElementById(curselectorinput + "btn").style.background = color;
            }
        }
        closeColorSelector();
    }
    function openColorSelector(o, e) {
        selecto = document.getElementById("colorselector").style;
        if (selecto.display === "block") {
            closeColorSelector();
        } else {
            selecto.display = "block";
            if (document.all && typeof(window.opera) !== "object") {
                selecto.left = event.x + document.body.scrollLeft - 420;
                selecto.top  = event.y + document.body.scrollTop - 120;
            } else {
                if (document.getElementById) {
                    selecto.left = (e.clientX + window.pageXOffset - 440) + "px";
                    selecto.top  = (e.clientY + window.pageYOffset - 120) + "px";
                }
            }
            curselectorinput = o;
        }
    }
    function closeColorSelector() {
        document.getElementById("colorselector").style.display = "none";
    }
</script>
<div class="module">__Site configuration__</div>
<fieldset>
    <form name="config" method="post" >
        <table class="std">
            <tr><th colspan="3">__General options__</th></tr>
            <tr class="light">
                <td>__Site title__</td>
                <td colspan="2"><input type="text" name="title" value="$title" size="50" /></td>
            </tr>
            <tr class="light">
                <td>__Site URL__</td>
                <td><input type="text" name="url" value="$url" size="50" /></td>
                <td class="help">__Leave empty for autodetection__</td>
            </tr>
            <tr class="light">
                <td>__Description__</td>
                <td colspan="2"><input type="text" name="description" value="$description" size="80" /></td>
            </tr>
            <tr class="light">
                <td>__Slogan__</td>
                <td colspan="2"><input type="text" name="slogan" value="$slogan" size="80" /></td>
            </tr>
            <tr class="light">
                <td>__Allow random aphorism as a slogan__</td>
                <td colspan="2"><input type="checkbox" name="random_slogan" value="$random_slogan" <!-- IF !empty($random_slogan) -->checked<!-- ENDIF --> /></td>
            </tr>
            <tr class="light">
                <td>__Cookie prefix for your site__</td>
                <td><input type="text" name="cookie" value="$cookie" size="10" /></td>
                <td class="help">__You may use the site name__</td>
            </tr>
            <tr class="light">
                <td>__Keywords__</td>
                <td colspan="2"><input type="text" name="keywords" value="$keywords" size="80" /></td>
            </tr>
            <tr class="light">
                <td>__Default skin__</td>
                <td colspan="2">
                    <select name="skin">
                    <!-- FOREACH skin = $skins -->
                        <option value="$skin.skin" <!-- IF !empty($skin.selected) -->selected="selected"<!-- ENDIF -->>$skin.skin</option>
                    <!-- ENDFOREACH -->
                    </select>
                </td>
            </tr>
            <tr class="light">
                <td>__Allow users to select skin__</td>
                <td colspan="2"><input type="checkbox" name="allow_skin" value="$allow_skin" <!-- IF !empty($allow_skin) -->checked<!-- ENDIF --> /></td>
            </tr>
            <tr class="light">
                <td>__Default language__</td>
                <td colspan="2">
                    <select name="lang">
                    <!-- FOREACH lang = $langs -->
                        <option value="$lang.lang" <!-- IF !empty($lang.selected) -->selected="selected"<!-- ENDIF -->>$lang.lang</option>
                    <!-- ENDFOREACH -->
                    </select>
                </td>
            </tr>
            <tr class="light">
                <td>__Allow users to select language__</td>
                <td colspan="2"><input type="checkbox" name="allow_language" value="1" <!-- IF !empty($allow_language) -->checked<!-- ENDIF --> /></td>
            </tr>
            <tr class="light">
                <td>__Try to detect language__</td>
                <td colspan="2"><input type="checkbox" name="detect_language" value="1" <!-- IF !empty($detect_language) -->checked<!-- ENDIF --> /></td>
            </tr>
            <tr class="light">
                <td>__Default timezone__</td>
                <td colspan="2">
                    <select name="tz">
                    <!-- FOREACH tz = $tzs -->
                        <option value="$tz.tz" <!-- IF !empty($tz.selected) -->selected="selected"<!-- ENDIF -->>$tz.title</option>
                    <!-- ENDFOREACH -->
                    </select>
                </td>
            </tr>
            <tr class="light">
                <td>__CAPTCHA system__</td>
                <td colspan="2">
                    <select name="captcha">
                    <!-- FOREACH captcha = $captchas -->
                        <option value="$captcha.captcha" <!-- IF !empty($captcha.selected) -->selected="selected"<!-- ENDIF -->>$captcha.captcha</option>
                    <!-- ENDFOREACH -->
                    </select>
                </td>
            </tr>
            <tr class="light">
                <td>__Items per page__</td>
                <td colspan="2"><input type="text" name="per_page" value="$per_page" size="3" /></td>
            </tr>
            <tr class="light">
                <td>__Number of latest elements__</td>
                <td colspan="2"><input type="text" name="last" value="$last" size="3" /></td>
            </tr>
            <tr class="light">
                <td>__Max file size__</td>
                <td><input type="text" name="max_filesize" value="$max_filesize" size="10" /> __byte(s)__</td>
                <td class="help">__Default__ = $php_max_filesize</td>
            </tr>
            <tr class="light">
                <td>__Thumb width__</td>
                <td colspan="2"><input type="text" name="thumb_width" value="$thumb_width" size="3" /> px</td>
            </tr>
            <tr class="light">
                <td>__Thumb height__</td>
                <td colspan="2"><input type="text" name="thumb_height" value="$thumb_height" size="3" /> px</td>
            </tr>
            <tr class="light">
                <td>__Show welcome message__</td>
                <td><input type="checkbox" name="welcome" value="1" <!-- IF !empty($welcome) -->checked<!-- ENDIF --> /></td>
                <td class="help">__It is visible only to guests__</td>
            </tr>
            <tr class="light">
                <td>__Welcome message__</td>
                <td colspan="2"><textarea name="welcome_msg" cols="20" rows="10">$welcome_msg</textarea></td>
            </tr>
            <th colspan="3">__Search__</th>
            <tr class="light">
                <td>__Min searching query length__</td>
                <td colspan="2"><input type="text" name="query_min" value="$query_min" size="4" /> __symbols__</td>
            </tr>
            <tr class="light">
                <td>__Max searching query length__</td>
                <td colspan="2"><input type="text" name="query_max" value="$query_max" size="4" /> __symbols__</td>
            </tr>
            <tr class="light">
                <td>__Output block length__</td>
                <td colspan="2"><input type="text" name="block" value="$block" size="4" /> __symbols__</td>
            </tr>
            <tr class="light">
                <td>__Results per page__</td>
                <td colspan="2"><input type="text" name="per_page" value="$per_page" size="4" /></td>
            </tr>
            <tr class="light">
                <td>__Allow guests to use search__</td>
                <td colspan="2"><input type="checkbox" name="allow_guest" value="1" <!-- IF !empty($allow_guest) -->checked<!-- ENDIF --> /></td>
            </tr>
            <th colspan="3">__Cache__</th>
            <tr class="light">
                <td>__Cache for pages__</td>
                <td colspan="2"><input type="checkbox" name="page_cache" value="1" <!-- IF !empty($page_cache) -->checked<!-- ENDIF --> /></td>
            </tr>
            <tr class="light">
                <td>__Cache for CSS__</td>
                <td colspan="2"><input type="checkbox" name="css_cache" value="1" <!-- IF !empty($css_cache) -->checked<!-- ENDIF --> /></td>
            </tr>
            <tr class="light">
                <td>__Expiration of the cache__</td>
                <td colspan="2"><input type="text" name="expired" value="$expired" size="5" />  __seconds__</td>
            </tr>
            <th colspan="3">__Audio player__</th>
            <tr class="light">
                <td>__Player width__</td>
                <td colspan="2"><input type="text" name="width" value="$width" size="4" /> px</td>
            </tr>
            <tr class="light">
                <td>__Player height__</td>
                <td colspan="2"><input type="text" name="height" value="$height" size="4" /> px</td>
            </tr>
            <tr class="light"><td>__Background__</td><td colspan="2">$bgcolor</td></tr>
            <tr class="light"><td>__Left background__</td><td colspan="2">$leftbg</td></tr>
            <tr class="light"><td>__Left icon color__</td><td colspan="2">$lefticon</td></tr>
            <tr class="light"><td>__Right background__</td><td colspan="2">$rightbg</td></tr>
            <tr class="light"><td>__Right icon color__</td><td colspan="2">$righticon</td></tr>
            <tr class="light"><td>__Active right background__</td><td colspan="2">$rightbghover</td></tr>
            <tr class="light"><td>__Active right icon color__</td><td colspan="2">$righticonhover</td></tr>
            <tr class="light"><td>__Text color__</td><td colspan="2">$playertext</td></tr>
            <tr class="light"><td>__Slider color__</td><td colspan="2">$slider</td></tr>
            <tr class="light"><td>__Track color__</td><td colspan="2">$track</td></tr>
            <tr class="light"><td>__Border color__</td><td colspan="2">$border</td></tr>
            <tr class="light"><td>__Loader color__</td><td colspan="2">$loader</td></tr>
            <tr class="light">
                <td>__Autostart__</td>
                <td colspan="2"><input type="checkbox" name="autostart" value="1" <!-- IF !empty($autostart) -->checked<!-- ENDIF --> /></td>
            </tr>
            <tr class="light">
                <td>__Loop__</td>
                <td colspan="2"><input type="checkbox" name="loop" value="1" <!-- IF !empty($loop) -->checked<!-- ENDIF --> /></td>
            </tr>
            <th colspan="3">__Video player__</th>
            <tr class="light">
                <td>__Player width__</td>
                <td colspan="2"><input type="text" name="width" value="$width" size="4" /> px</td>
            </tr>
            <tr class="light">
                <td>__Player height__</td>
                <td colspan="2"><input type="text" name="height" value="$height" size="4" /> px</td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="save" value="__Save__" /></p>
    </form>
</fieldset>