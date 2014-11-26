<?php
/**
 * @package    idxCMS
 * @subpackage MODULES
 * @file       system/modules/aphorisms/aphorisms.tpl.php
 * @version    2.3
 * @author     Victor Nabatov <greenray.spb@gmail.com>\n
 * @license    Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *             http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @copyright  (c) 2011 - 2014 Victor Nabatov\n
 * @link       https://github.com/Greenray/idxCMS/system/modules/aphorisms/aphorisms.tpl.php
 */

/** Module APORISMS - template */
die();?>

<script type="text/javascript" src="{TOOLS}jquery.ui.min.js"></script>
<script type="text/javascript" src="{TOOLS}jquery.flip.min.js"></script>
<script type="text/javascript">
    $(function() {
        $('#flippad a:not(.revert)').bind('click', function() {
            function createRequest() {
                var req;
                try {
                    req = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                    try {
                        req = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (E) {
                        req = false;
                    }
                }
                if (!req && typeof XMLHttpRequest !== 'undefined')
                    req = new XMLHttpRequest();
                return req;
            }
            function getData(url) {
                var data = new Array();
                var request = createRequest();
                request.open("GET", url, false);
                request.send(null);
                if (request.status === 200) {
                    data = request.responseText.split('$', 2);
                    return data[0];
                }
                return '';
            }
            $('#flipbox').flip({
                direction : $(this).attr('rel'),
                content   : getData('./?module=aphorisms&flip=1'),
                color     : '#829298'
            })
            return false;
        });
    });
</script>
<div id="flipbox">{text}</div>
<div id="flippad">
    <a href="#" rel="rl" rev="transparent" title="[__Previous]">
        <img src="{ICONS}arrow-left.png" width="16" height="16" alt="[__Previous]" />
    </a>
    <a href="#" rel="lr" rev="transparent" title="[__Next]">
        <img src="{ICONS}arrow-right.png" width="16" height="16" alt="[__Next]" />
    </a>
</div>
