<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# BBCODES PANEL

die();?>

<div class="bbcodes center">
    <script>
        var clientPC = navigator.userAgent.toLowerCase();
        var isIE  = ((clientPC.indexOf("msie") !== -1) && (clientPC.indexOf("opera") === -1));
        var isWin = ((clientPC.indexOf("win") !== -1) || (clientPC.indexOf("16bit") !== -1));
        var id;
        var stored_selection = '';
        var puw;                    // Popup window
        // Show hidden additionsl input field.
        function OpenField(field, area) {
            id = document.getElementById(area);
            if (!id.selectionEnd || (id.selectionEnd - id.selectionStart == 0))
                document.getElementById(field + area).style.display = 'block';
        }
        function GetFromDropDown(val, name, area) {
            if (!val) return;
            if (name === 'colors')   CreateBBTag('[color="' + val + '"]','[/color]', area);
            if (name === 'bgcolors') CreateBBTag('[bgcolor="' + val + '"]','[/bgcolor]', area);
            if (name === 'format')   CreateBBTag('[' + val + ']' + val,'[/' + val + ']', area);
            if (name === 'typeface') CreateBBTag('[font="' + val + '"]','[/font]', area);
            if (name === 'size')     CreateBBTag('[size=' + val + ']','[/size]', area);
        }
        function CreateBBTag(opener, closer, area) {
            if (isIE && isWin)
                 CreateBBTag_IE(opener, closer, area);
            else CreateBBTag_nav(opener, closer, area);
            return;
        }
        function CreateBBTag_nav(opener, closer, area) {
            id = document.getElementById(area);
            if (id.selectionEnd && (id.selectionEnd - id.selectionStart > 0)) {
                preString  = (id.value).substring(0, id.selectionStart);
                newString  = opener + (id.value).substring(id.selectionStart, id.selectionEnd) + closer;
                postString = (id.value).substring(id.selectionEnd);
                id.value = preString + newString + postString;
                id.focus();
            } else {
                var offset = id.selectionStart;
                preString  = (id.value).substring(0, offset);
                newString  = opener + closer;
                postString = (id.value).substring(offset);
                id.value = preString + newString + postString;
                id.selectionStart = offset + opener.length;
                id.selectionEnd   = offset + opener.length;
                id.focus();
            }
            return;
        }
        function CreateBBTag_IE(opener, closer, area) {
            id = document.getElementById(area);
            var aSelection = document.selection.createRange().text;
            var range = id.createTextRange();
            if (aSelection) {
                document.selection.createRange().text = opener + aSelection + closer;
                id.focus();
                range.move('textedit');
                range.select();
            } else {
                var oldStringLength = range.text.length + opener.length;
                id.value += opener + closer;
                id.focus();
                range.move('character', oldStringLength);
                range.collapse(false);
                range.select();
            }
            return;
        }
        function AddLink(type, area, link) {
            if (isIE && isWin)
                 AddLink_IE(type, area, link);
            else AddLink_nav(type, area, link);
            return;
        }
        function AddLink_nav(type, area, link) {
            var text = bb = '';
            id = document.getElementById(area);
            if (id.selectionEnd && (id.selectionEnd - id.selectionStart > 0))
                 text = (id.value).substring(id.selectionStart, id.selectionEnd);
            else text = document.getElementById('txt_' + area).value;
            var preString = '';
            var postString = '';
            if (text) {
                preString = (id.value).substring(0, id.selectionStart);
                bb = '[' + type + '=' +link + ']' + text + '[/' + type + ']';
                postString = (id.value).substring(id.selectionEnd);
                id.value  = preString + bb + postString;
                id.focus();
                return;
            } else bb = '[' + type + ']' + link + '[/' + type + ']';
            var offset = id.selectionStart;
            preString  = (id.value).substring(0, offset);
            postString = (id.value).substring(offset);
            id.value  = preString + bb + postString;
            id.selectionStart = offset + opener.length;
            id.selectionEnd   = offset + opener.length;
            id.focus();
            return;
        }
        function AddLink_IE(type, area, link) {
            var text = '';
            id = document.getElementById(area);
            var aSelection = document.selection.createRange().text;
            var range = id.createTextRange();
            if (aSelection)
                 text = aSelection;
            else text = document.getElementById('txt_' + area).value;
            if (text) {
                document.selection.createRange().text = '[' + type + '=' + link + ']' + text + '[/' + type + ']';
                id.focus();
                range.move('textedit');
                return;
            } else {
                id.value += '[' + type + ']' + link + '[/' + type + ']';
                id.focus();
                range.collapse(false);
                range.select();
                return;
            }
        }
        function AddList(type, area) {
            if (isIE && isWin)
                 AddList_IE(type, area);
            else AddList_nav(type, area);
            return;
        }
        function AddList_nav(type, area) {
            id = document.getElementById(area);
            var offset = id.selectionStart;
            var minus = 0;
            var opener = '[' + type + ']';
            var closer = '[/' + type + ']';
            minus += 1;
            var items = new Array();
            var itemString = '';
            var item = '';
            while (item === prompt('Enter an item\r\nLeave the box empty or click Cancel\r\nto complete the list', ''))
                items.push('[*]' + item + '[/*]');
            itemString = items.join('');
            itemsize = items.length;
            minus += itemsize;
            var preString  = (id.value).substring(0, offset);
            var newString  = opener + itemString + closer;
            var postString = (id.value).substring(offset);
            id.value  = preString + newString + postString;
            id.selectionStart = offset + newString.length - minus;
            id.selectionEnd   = offset + newString.length - minus;
            id.focus();
            return;
        }
        function AddList_IE(type, area) {
            id = document.getElementById(area);
            var range = id.createTextRange();
            var minus = 0;
            var opener = '[' + type + ']';
            var closer = '[/' + type + ']';
            minus += 1;
            var items = new Array();
            var itemString = '';
            var item;
            while (item === prompt('Enter an item\r\nLeave the box empty or click Cancel\r\nto complete the list', ''))
                items.push('[*]' + item + '[/*]');
            itemString = items.join('');
            itemsize   = items.length;
            minus += itemsize;
            tag = opener + itemString + closer;
            var oldStringLength = range.text.length + tag.length - minus;
            id.value += tag;
            id.focus();
            range.move('character', oldStringLength);
            range.collapse(false);
            range.select();
            return;
        }
        function RemoveBBTag(area) {
            id = document.getElementById(area);
            var text = '';
            if (id.value !== undefined) {
                text = id.value;
                text = text.replace (/\[[^\]]*\]/g, '');
                id.value = text;
            } else if (id.innerText !== undefined) {
                text = id.innerText;
                text = text.replace (/\[[^\]]*\]/g, '');
                id.innerText = text;
            } else {
                text = id.textContent;
                text = text.replace (/\[[^\]]*\]/g, '');
                id.textContent = text;
            }
        }
        function SetSmile(tag, area) {
            id = document.getElementById(area);
            var offset = id.selectionStart;
            preString  = (id.value).substring(0, offset);
            postString = (id.value).substring(offset);
            id.value  = preString + tag + postString;
            id.focus();
            return;
        }
        function MozWrap(area, open, close) {
            var selLength = area.textLength;
            var selStart  = area.selectionStart;
            var selEnd    = area.selectionEnd;
            if (selEnd == 1 || selEnd == 2) selEnd = selLength;
            var s1 = (area.value).substring(0, selStart);
            var s2 = (area.value).substring(selStart, selEnd);
            var s3 = (area.value).substring(selEnd, selLength);
            area.value = s1 + open + s2 + close + s3;
            return;
        }
        function InsertText(area, text) {
            if (area.createTextRange && area.caretPos) {
                var caretPos = area.caretPos;
                caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) === ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
            } else {
                var selStart = area.selectionStart;
                var selEnd   = area.selectionEnd;
                MozWrap(area, text, '');
                area.selectionStart = selStart + text.length;
                area.selectionEnd   = selEnd + text.length;
            }
        }
        function CheckSelection() {
            var myselection = '';
            if (window.getSelection)        myselection = window.getSelection();
            else if (document.selection)    myselection = document.selection.createRange().text;
            else if (document.getSelection) myselection = document.getSelection();
            if ((myselection !== '') && (myselection !== null)) {
                if (myselection !== stored_selection ) {
                    stored_selection = (myselection.toString() !== '') ? myselection.toString() : null;
                }
            } else stored_selection = null;
        }
        function AddQuote(area) {
            if ((stored_selection !== '') && (stored_selection !== null)) {
                InsertText(area, '[quote]' + stored_selection + '[/quote]\n');
            }
            return false;
        }
        // The possibility to open and close additional form with the same button
        function ShowForm(area) {
            id = document.getElementById(area);
            if (id.style.display === 'none')
                 id.style.display = 'block';
            else id.style.display = 'none';
        }
        function SetImagesPath(val) {
            category = val.options[val.selectedIndex].value;
        }
        function OpenBrowseWin(area, url) {
            id = area;
            puw = window.open(url, 'popWin', 'menubar=no,toolbar=no,location=no,directories=no,scrollbars=yes,resizable=yes,width=640,height=480,top=100,status=no');
            puw.focus();
        }
        function InsertImage(area, url, side, border, margin, alt) {
            var dialog = 'image_' + area;
            id = document.getElementById(area);
            if (!url) url = document.getElementById('image_url_' + area).value;
            preString = (id.value).substring(0, id.selectionStart);
            bb = '[img]' + url + '[/img]';
            postString = (id.value).substring(id.selectionEnd);
            id.value  = preString + bb + postString;
            id.focus();
            HideDialog(dialog);
        }
        function ChooseColor(cmd, area) {
            document.getElementById('cmd_' + area).value = cmd;
            ShowForm('color_' + area);
        }
        function ViewColor(color, area) {
            id = document.getElementById('clr_val_' + area);
            if (!color) color = id.value;
            document.getElementById('preview_' + area).style.backgroundColor = color;
            id.value = color;
        }
        function SetColor(color, area) {
            var cmd = document.getElementById('cmd_' + area).value;
            if (!color) color = document.getElementById('clr_val_' + area).value;
            if (cmd === 'color') CreateBBTag('[color="' + color + '"]','[/color]', area);
            else if (cmd === 'bgcolor') CreateBBTag('[bgcolor="' + color + '"]','[/bgcolor]', area);
            HideDialog('color_' + area);
        }
        function AddUrl(cmd, area) {
            document.getElementById('type_' + area).value = cmd;
            // This field will be open while typing
            if (cmd === 'mp3') document.getElementById('label_' + area).style.display = 'none';
            ShowForm('link_' + area);
        }
        function InsertLink(area) {
            var url = document.getElementById('url_' + area).value;
            var cmd = document.getElementById('type_' + area).value;
            switch(cmd) {
                case "url":
                    AddLink('url', area, url);
                    break;
                case "mp3":
                    AddLink('mp3', area, url);
                    break;
                case "email":
                    AddLink('email', area, url);
                    break;
                default:
                    break;
            }
            document.getElementById(area).focus();
            HideDialog('link_' + area);
        }
        function HideDialog(dialog) {
            document.getElementById(dialog).style.display = 'none';
        }
        function Preview(area, url) {
            var text = '';
            // Preview width must be equql with #layout-center
            var width = screen.width - 450;
            var id_txt = document.getElementById(area);
            // Fucking browsers standards...
            if (id_txt.value !== undefined)
                text = id_txt.value;
            else if (id_txt.innerText !== undefined)
                 text = id_txt.innerText;
            else text = id_txt.textContent;
            // We can't parse lines endings in popup window
            text = text.replace (/(\r\n|\n)/gm, '<br />');
            // Now we create hidden form to send preview data in popup window
            var h_form = document.createElement('FORM');
            h_form.setAttribute('action', url);
            // Method POST is choosen for bbCodes parsing.
            h_form.setAttribute('method', 'POST');
            h_form.setAttribute('target', 'preview');
            h_form.style.display = 'none';
            // Article title. It is empty when user post comment or reply in forum
            var title = '';
            var id_title = document.getElementById('title');
            if (id_title !== undefined) title = id_title.value;
            var element = document.createElement('INPUT');
            element.setAttribute('type', 'text');
            element.setAttribute('name', 'title');
            element.setAttribute('value', title);
            h_form.appendChild(element);
            element = document.createElement('INPUT');
            element.setAttribute('type', 'text');
            element.setAttribute('name', 'text');
            element.setAttribute('value', text);
            h_form.appendChild(element);
            document.body.appendChild(h_form);
            puw = window.open(url, 'preview', 'menubar=no,toolbar=no,location=no,directories=no,scrollbars=yes,resizable=yes,width=' + width + ',height=500,top=50,status=no');
            puw.focus();
            h_form.submit();
            // We don't need this form any more.
            document.body.removeChild(h_form);
        //    h_form.removeNode(true);
        }
        // Localization
        function __(key) {
            return (window.language && language[key]) ? language[key] : key;
        }
        document.cookie = 'javascript=true';   // Sets a cookie if javascript is enabled
    </script>
    <div class="center">
        <select class="bbselect" onchange="GetFromDropDown(value,'typeface','{area}');" name="fontface">
            <option value="">[__Font]</option>
            <option style="font-family:arial;" value="arial">Arial</option>
            <option style="font-family:courier;" value="courier">Courier</option>
            <option style="font-family:garamond;" value="garamond">Garamond</option>
            <option style="font-family:helvetica;" value="helvetica">Helvetica</option>
            <option style="font-family:times;" value="times">Times</option>
            <option style="font-family:verdana;" value="verdana">Verdana</option>
            <option style="font-family:mono;" value="mono">Mono</option>
        </select>
        <select class="bbselect" onchange="GetFromDropDown(value, 'size', '{area}');" name="fontsize">
            <option value="">[__Size]</option>
            <option style="font-size:8px;" value="8">8px</option>
            <option style="font-size:9px;" value="9">9px</option>
            <option style="font-size:10px;" value="10">10px</option>
            <option style="font-size:11px;" value="11">11px</option>
            <option style="font-size:12px;" value="12">12px</option>
            <option style="font-size:13px;" value="13">13px</option>
            <option style="font-size:14px;" value="14">14px</option>
        </select>
        <br />
        <button type="button" class="bbbutton" onclick="CreateBBTag('[b]','[/b]','{area}');">
            <img src="{bbimg}bold.gif" name="bold" title="[__Bold]" width="20" height="20" alt="B" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[i]','[/i]','{area}');">
            <img src="{bbimg}italic.gif" name="italic" title="[__Italic]" width="20" height="20" alt="I" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[u]','[/u]','{area}');">
            <img src="{bbimg}underline.gif" name="underline" title="[__Underline]" width="20" height="20" alt="U" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[s]','[/s]','{area}');">
            <img src="{bbimg}strikethrough.gif" name="strikethrough" title="[__Strikethrough]" width="20" height="20" alt="S" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[sub]','[/sub]','{area}');">
            <img src="{bbimg}subscript.gif" name="subscript" title="[__Subscript]" width="20" height="20" alt="" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[sup]','[/sup]','{area}');">
            <img src="{bbimg}superscript.gif" name="superscript" title="[__Superscript]" width="20" height="20" alt="" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[left]','[/left]','{area}');">
            <img src="{bbimg}left.gif" name="left" title="[__Left]" width="20" height="20" alt="L" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[center]','[/center]','{area}');">
            <img src="{bbimg}center.gif" name="center" title="[__Center]" width="20" height="20" alt="C" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[right]','[/right]','{area}');">
            <img src="{bbimg}right.gif" name="right" title="[__Right]" width="20" height="20" alt="R" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[justify]','[/justify]','{area}');">
            <img src="{bbimg}justify.gif" name="justify" title="[__Justify]" width="20" height="20" alt="J" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[indent]','[/indent]','{area}');">
            <img src="{bbimg}indent.gif" name="indent" title="[__Indent]" width="20" height="20" alt="" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[outdent]','[/outdent]','{area}');">
            <img src="{bbimg}outdent.gif" name="outdent" title="[__Outdent]" width="20" height="20" alt="" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[hr]','','{area}');">
            <img src="{bbimg}hr.gif" name="hr" title="[__Hor]" width="20" height="20" alt="_" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[p]','[/p]','{area}');">
            <img src="{bbimg}p.gif" name="p" title="[__Hor]" width="20" height="20" alt="P" />
        </button>
        <button type="button" class="bbbutton" onclick="AddList('[ul]','{area}');">
            <img src="{bbimg}bullist.gif" name="ul" title="[__Bullets]" width="20" height="20" alt="" />
        </button>
        <button type="button" class="bbbutton" onclick="AddList('[ol]','{area}');">
            <img src="{bbimg}numlist.gif" name="ol" title="[__Numbered list]" width="20" height="20" alt="" />
        </button>
        <button type="button" class="bbbutton" onclick="ChooseColor('color', '{area}');">
            <img src="{bbimg}color.gif" name="color" title="[__Color]" width="20" height="20" alt="" />
        </button>
        <button type="button" class="bbbutton" onclick="ChooseColor('bgcolor', '{area}');">
            <img src="{bbimg}backcolor.gif" name="bgcolor" title="[__Background]" width="20" height="20" alt="" />
        </button>
        [if=full]
            <br />
            <button type="button" class="bbbutton" onclick="CreateBBTag('[spoiler]','[/spoiler]','{area}');">
                <img src="{bbimg}spoiler.gif" name="spoiler" title="[__Spoiler]" width="20" height="20" alt="" />
            </button>
            <button type="button" class="bbbutton" onclick="ShowForm('image_{area}');">
                <img src="{bbimg}image.gif" name="image" title="[__Image]" width="20" height="20" alt="" />
            </button>
            <button type="button" class="bbbutton" onclick="AddUrl('url','{area}');">
                <img src="{bbimg}link.gif" name="link" title="[__URL]" width="20" height="20" alt="" />
            </button>
            <button type="button" class="bbbutton" onclick="AddUrl('email','{area}');">
                <img src="{bbimg}email.gif" name="email" title="[__Email]" width="20" height="20" alt="" />
            </button>
        [endif]
        <button type="button" class="bbbutton" onclick="CreateBBTag('[code]','[/code]','{area}');">
            <img src="{bbimg}code.gif" name="code" title="[__Code]" width="20" height="20" alt="" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[php]','[/php]','{area}');">
            <img src="{bbimg}php.gif" name="php" title="[__PHP]" width="20" height="20" alt="" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[html]','[/html]','{area}');">
            <img src="{bbimg}html.gif" name="html" title="[__HTML]" width="20" height="20" alt="" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[quote]','[/quote]','{area}');">
            <img src="{bbimg}quote.gif" name="quote" title="[__Quote]" width="20" height="20" alt="" />
        </button>
        <button type="button" class="bbbutton" onmouseover="CheckSelection();" onclick="AddQuote(document.forms['{form}'].elements['{area}']);">
            <img src="{bbimg}quote_selected.gif" name="quote_selected" title="[__Quote selectd]" width="20" height="20" alt="" />
        </button>
        <button type="button" class="bbbutton" onclick="CreateBBTag('[offtopic]','[/offtopic]','{area}');">
            <img src="{bbimg}offtopic.gif" name="offtopic" title="[__Off Topic]" width="20" height="20" alt="" />
        </button>
        [if=full]
            <button type="button" class="bbbutton" onclick="AddUrl('mp3','{area}');">
                <img src="{bbimg}mp3.gif" name="mp3" title="[__MP3]" width="20" height="20" alt="" />
            </button>
            <button type="button" class="bbbutton" onclick="CreateBBTag('[youtube]','[/youtube]','{area}');">
                <img src="{bbimg}youtube.gif" name="youtube" title="[__YouTube]" width="20" height="20" alt="" />
            </button>
        [endif]
        <button type="button" class="bbbutton" onclick="ShowForm('smiles_{area}');">
            <img src="{bbimg}smiles.gif" name="smiles" title="[__Smiles]" width="20" height="20" alt="" />
        </button>
        [if=moderator]
            <button type="button" class="bbbutton" onclick="CreateBBTag('[note]','[/note]','{area}');">
                <img src="{bbimg}note.png" name="note" title="[__Note]" width="20" height="20" alt="" />
            </button>
        [endif]
        <button type="button" class="bbbutton" onclick="RemoveBBTag('{area}');">
            <img src="{bbimg}cleanup.gif" name="remove" title="[__Remove BBcodes]" width="20" height="20" alt="" />
        </button>
        <button type="button" class="bbbutton" onclick="Preview('{area}','{MODULE}editor');">
            <img src="{bbimg}preview.gif" name="preview" title="[__Preview]" width="20" height="20" alt="" />
        </button>
    </div>
    [if=full]
        <div id="image_{area}" unselectable="on" class="bbtools none">
            <div class="img">[__Link] (URL): <input type="text" id="image_url_{area}" size="50" /></div>
            <p>
                <input type="button" onclick="OpenBrowseWin('{area}','{path}');" value="[__Choose / Upload]" />
                <input type="button" onclick="InsertImage('{area}');HideDialog('image_{area}');" value="[__Insert]" />
                <input type="button" onclick="HideDialog('image_{area}');" value="[__Cancel]" />
            </p>
        </div>
        <div id="link_{area}" unselectable="on" class="bbtools none">
            <input type="hidden" id="type_{area}" value="" />
            <div class="link_text">
                [__Link] (URL): <input type="text" id="url_{area}" size="50" value="" />
            </div>
            <div id="label_{area}" class="link_text none"">
                [__Text]: <input type="text" id="txt_{area}" size="50" value="" />
            </div>
            <p>
                <input type="button" onclick="InsertLink('{area}');HideDialog('link_{area}');" value="[__Insert]" />
                <input type="button" onclick="HideDialog('link_{area}');" value="[__Cancel]" />
            </p>
        </div>
    [endif]
    <div id="color_{area}" unselectable="on" class="bbtools none">
        <input type="hidden" id="cmd_{area}" value="" />
        <div style="background:black;padding:1px;height:22px;width:125px;float:left;">
            <div id="preview_{area}" class="red" style="height:100%;width:100%;"></div>
        </div>
        <input type=text id="clr_val_{area}" value="red" size="17" onpaste="ViewColor('', '{area}');" onblur="ViewColor('', '{area}');" />
        <input type="button" onmouseover="ViewColor('', '{area}');" onclick="SetColor('', '{area}');HideDialog('color_{area}');" value="[__OK]" />
        <input type="button" onclick="HideDialog('color_{area}');" value="[__Cancel]" />
        <br />
        [__Choose color or enter a] <a href="http://en.wikipedia.org/wiki/Web_colors" target="_blank">[__color name]</a>
        <br clear=all />
        <table cellspacing="1" cellpadding="0" width="480">
            [each=colors]
                <tr>
                    [each=colors[colors]]
                        <td style="background:#{colors[color]};height:12px;width:12px;" onmouseover="ViewColor('#{colors[color]}', '{area}');" onclick="SetColor('#{colors[color]}', '{area}');"></td>
                    [endeach.colors[colors]]
                </tr>
            [endeach.colors]
        </table>
    </div>
    <div id="smiles_{area}" unselectable="on" class="bbtools none">
        [each=smile]<img src="{SMILES}{smile}.gif" alt="{smile}" onclick="SetSmile('[{smile}]','{area}');HideDialog('smiles_{area}');" />[endeach.smile]
    </div>
</div>
