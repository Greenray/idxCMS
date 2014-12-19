<?php
/**
 * @file      system/modules/rate/rate.tpl.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>\n
 * @copyright (c) 2011 - 2014 Victor Nabatov
 * @license   <http://creativecommons.org/licenses/by-nc-sa/3.0/> Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 */

/**
 * Rate system for comments and replays.
 * rate.tpl.php - Template for rates.
 * @package Rate
 */

die();?>

<div class="star">
    <div><ul id="star{item}" {event} class="star"><li id="starCur{item}" class="curr" title="{value}" style="width:{width}px;"></li></ul></div>
    <div style="clear: both;" /></div>
    <div class="rates">
        <div id="starVoted{item}" class="voted">{voted}</div>
        <div class="sep">/</div>
        <div id="starUser{item}" class="user">{value}</div>
    </div>
    <br style="clear: both;" />
</div>
