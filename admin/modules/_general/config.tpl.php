<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# ADMINISTRATION - GENERAL CONFIGURATION TEMPLATE

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
                    selecto.top = (e.clientY + window.pageYOffset - 120) + "px";
                }
            }
            curselectorinput = o;
        }
    }
    function closeColorSelector() {
        document.getElementById("colorselector").style.display = "none";
    }
</script>
<div class="module">[__Site configuration]</div>
<fieldset>
    <form name="config" method="post" action="">
        <table class="std">
            <th colspan="3">[__General options]</th>
            <tr class="odd">
                <td>[__Site title]</td>
                <td colspan="2"><input type="text" name="title" value="{title}" size="50" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Site URL]</td>
                <td><input type="text" name="url" value="{url}" size="50" class="text" /></td>
                <td>[__Leave empty for autodetection]</td>
            </tr>
            <tr class="odd">
                <td>[__Description]</td>
                <td colspan="2"><input type="text" name="description" value="{description}" size="80" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Copyright]</td>
                <td colspan="2"><input type="text" name="copyright" value="{copyright}" size="80" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Slogan]</td>
                <td colspan="2"><input type="text" name="slogan" value="{slogan}" size="80" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Cookie prefix for your site]</td>
                <td><input type="text" name="cookie" value="{cookie}" size="10" class="text" /></td>
                <td>[__You may use the site name]</td>
            </tr>
            <tr class="odd">
                <td>[__Keywords]</td>
                <td colspan="2"><input type="text" name="keywords" value="{keywords}" size="80" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Meta tags for your site]</td>
                <td colspan="2">
                    &lt;meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /&gt;<br />
                    &lt;meta http-equiv="Content-Language" content="<i>[__Will be set automaticaly]</i>" /&gt;
                    <textarea name="meta_tags" cols="20" rows="7">{meta_tags}</textarea>
                </td>
            </tr>
            <tr class="odd">
                <td>[__Module on index page]</td>
                <td colspan="2">
                    <select name="index-module">
                        [each=modules]
                            <option value="{modules[module]}" [if=modules[selected]]selected="selected"[endif]>{modules[title]}</option>
                        [endeach.modules]
                    </select>
                </td>
            </tr>
            <tr class="odd">
                <td>[__Default skin]</td>
                <td colspan="2">
                    <select name="skin">
                        [each=skins]
                            <option value="{skins[skin]}" [if=skins[selected]]selected="selected"[endif]>{skins[skin]}</option>
                        [endeach.skins]
                    </select>
                    [each=skins]
                        <input type="hidden" name="skins[]" value="{skins[skin]}" />
                    [endeach.skins]
                </td>
            </tr>
            <tr class="odd">
                <td>[__Allow users to select skin]</td>
                <td colspan="2"><input type="checkbox" name="allow-skin" value="1" [if=allow-skin]checked="checked"[endif] /></td>
            </tr>
            <tr class="odd">
                <td>[__Default language]</td>
                <td colspan="2">
                    <select name="lang">
                        [each=langs]
                            <option value="{langs[lang]}" [if=langs[selected]]selected="selected"[endif]>{langs[lang]}</option>
                        [endeach.langs]
                    </select>
                </td>
            </tr>
            <tr class="odd">
                <td>[__Allow users to select language]</td>
                <td colspan="2"><input type="checkbox" name="allow-lang" value="1" [if=allow-lang]checked="checked"[endif] /></td>
            </tr>
            <tr class="odd">
                <td>[__Try to detect language]</td>
                <td colspan="2"><input type="checkbox" name="detect-lang" value="1" [if=detect-lang]checked="checked"[endif] /></td>
            </tr>
            <tr class="odd">
                <td>[__Default timezone]</td>
                <td colspan="2">
                    <select name="tz">
                        [each=tz]
                            <option value="{tz[tz]}" [if=tz[selected]]selected="selected"[endif]>{tz[title]}</option>
                        [endeach.tz]
                    </select>
                </td>
            </tr>
            <tr class="odd">
                <td>[__CAPTCHA system]</td>
                <td colspan="2">
                    <select name="captcha">
                        [each=captcha]
                            <option value="{captcha[captcha]}" [if=captcha[selected]]selected="selected"[endif]>{captcha[captcha]}</option>
                        [endeach.captcha]
                    </select>
                </td>
            </tr>
            <tr class="odd">
                <td>[__Items per page]</td>
                <td colspan="2"><input type="text" name="per-page" value="{per-page}" size="3" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Number of latest elements]</td>
                <td colspan="2"><input type="text" name="last" value="{last}" size="3" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Max file size]</td>
                <td><input type="text" name="file-max-size" value="{file-max-size}" size="10" class="text" /> [__byte(s)]</td>
                <td>[__Default] = {max_filesize}</td>
            </tr>
            <tr class="odd">
                <td>[__Max image size]</td>
                <td><input type="text" name="image-max-size" value="{image-max-size}" size="10" class="text" /> [__byte(s)]</td>
                <td>[__Default] = {max_filesize}</td>
            </tr>
            <tr class="odd">
                <td>[__Thumb width]</td>
                <td colspan="2"><input type="text" name="thumb-width" value="{thumb-width}" size="6" class="text" /> px</td>
            </tr>
            <tr class="odd">
                <td>[__Thumb height]</td>
                <td colspan="2"><input type="text" name="thumb-height" value="{thumb-height}" size="6" class="text" /> px</td>
            </tr>
            <tr class="odd">
                <td>[__Show welcome message]</td>
                <td><input type="checkbox" name="welcome" value="1" [if=welcome]checked="checked"[endif] /></td>
                <td>[__It is visible only to guests]</td>
            </tr>
            <tr class="odd">
                <td>[__Welcome message]</td>
                <td colspan="2"><textarea name="welcome_msg" cols="20" rows="10">{welcome_msg}</textarea></td>
            </tr>
            <th colspan="3">[__Search]</th>
            <tr class="odd">
                <td>[__Min searching query length]</td>
                <td colspan="2"><input type="text" name="query-min" value="{query-min}" size="4" class="text" /> [__symbols]</td>
            </tr>
            <tr class="odd">
                <td>[__Max searching query length]</td>
                <td colspan="2"><input type="text" name="query-max" value="{query-max}" size="4" class="text" /> [__symbols]</td>
            </tr>
            <tr class="odd">
                <td>[__Output block length]</td>
                <td colspan="2"><input type="text" name="block" value="{block}" size="6" class="text" /> [__symbols]</td>
            </tr>
            <tr class="odd">
                <td>[__Results per page]</td>
                <td colspan="2"><input type="text" name="per-page" value="{per-page}" size="6" class="text" /></td>
            </tr>
            <tr class="odd">
                <td>[__Allow guests to use search]</td>
                <td colspan="2"><input type="checkbox" name="allow-guest" value="1" [if=allow-guest]checked="checked"[endif] /></td>
            </tr>
            <th colspan="3">[__Audio player]</th>
            <tr class="odd">
                <td>[__Player width]</td>
                <td colspan="2"><input type="text" name="width" value="{width}" size="6" class="text" /> px</td>
            </tr>
            <tr class="odd">
                <td>[__Player height]</td>
                <td colspan="2"><input type="text" name="height" value="{height}" size="6" class="text" /> px</td>
            </tr>
            <tr class="odd"><td>[__Background]</td><td colspan="2">{bgcolor}</td></tr>
            <tr class="odd"><td>[__Left background]</td><td colspan="2">{leftbg}</td></tr>
            <tr class="odd"><td>[__Left icon color]</td><td colspan="2">{lefticon}</td></tr>
            <tr class="odd"><td>[__Right background]</td><td colspan="2">{rightbg}</td></tr>
            <tr class="odd"><td>[__Right icon color]</td><td colspan="2">{righticon}</td></tr>
            <tr class="odd"><td>[__Active right background]</td><td colspan="2">{rightbghover}</td></tr>
            <tr class="odd"><td>[__Active right icon color]</td><td colspan="2">{righticonhover}</td></tr>
            <tr class="odd"><td>[__Text color]</td><td colspan="2">{playertext}</td></tr>
            <tr class="odd"><td>[__Slider color]</td><td colspan="2">{slider}</td></tr>
            <tr class="odd"><td>[__Track color]</td><td colspan="2">{track}</td></tr>
            <tr class="odd"><td>[__Border color]</td><td colspan="2">{border}</td></tr>
            <tr class="odd"><td>[__Loader color]</td><td colspan="2">{loader}</td></tr>
            <tr class="odd">
                <td>[__Autostart]</td>
                <td colspan="2"><input type="checkbox" name="autostart" value="1" [if=autostart]checked="checked"[endif] /></td>
            </tr>
            <tr class="odd">
                <td>[__Loop]</td>
                <td colspan="2"><input type="checkbox" name="loop" value="1" [if=loop]checked="checked"[endif] /></td>
            </tr>
            <th colspan="3">[__Video player]</th>
            <tr class="odd">
                <td>[__Player width]</td>
                <td colspan="2"><input type="text" name="width" value="{width}" size="6" class="text" /> px</td>
            </tr>
            <tr class="odd">
                <td>[__Player height]</td>
                <td colspan="2"><input type="text" name="height" value="{height}" size="6" class="text" /> px</td>
            </tr>
        </table>
        <p class="center"><input type="submit" name="save" value="[__Save]" class="submit" /></p>
    </form>
</fieldset>
