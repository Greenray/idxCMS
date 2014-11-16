<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# COMMENT TEMPLATE

die();?>

<div class="comment">
    <a name="{id}"></a>
    <div class="content">
        <span class="date">{date}</span>
        [ifelse=rateid]
            <span class="rate">
                <button type="button" id="dnUser{rateid}" onclick="Rate('dn', '{author}', this)"> - </button>
                <span id="rate{rateid}" class="{rate_color}">{rate}</span>
                <button type="button" id="upUser{rateid}" onclick="Rate('up', '{author}', this)"> + </button>
            </span>
        [else]
            <span class="{rate_color}" style="float:right;padding:0 26px 0 0;">{rate}</span>
        [endelse]
        <div class="author">
            <img src="{avatar}" hspace="5" vspace="5" alt="" /><br />
            [ifelse=opened]
                <strong><a href="javascript:InsertText(document.forms['post-comment'].elements['text'], '[b]{nick}![/b]' + '\n');">{nick}</a></strong><br />
            [else]
                {nick}<br />
            [endelse]
            [if=status]{status}<br />[endif]
            [if=stars][__Rate]: {stars}<br />[endif]
            [if=city]{city}[endif]
            [if=country]{country}<br />[endif]
        </div>
        <div class="text">{text}</div>
    </div>
    [if=opened]
        <span class="menu">
            <a href="{MODULE}user&amp;user={author}" title="[__Profile]">
                <img src="{ICONS}profile.png" width="16" height="16" class="tip" alt="[__Profile]" />
            </a>
            <a href="{MODULE}user.pm&amp;for={author}" class="tip" title="[__Private message]">
                <img src="{ICONS}user-pm.png" width="16" height="16" alt="" />
            </a>
        </span>
    [endif]
    [if=moderator]
        <div class="menu">
            <form name="topic" method="post" action="">
                <button formaction="{link}{COMMENT}{id}&amp;action=edit" class="submit">[__Edit]</button>
                <button formaction="{link}{COMMENT}{id}&amp;action=delete" class="submit">[__Delete]</button>
            </form>
        </div>
    [endif]
    [if=ban]
        <div class="menu">
            <form name="topic" method="post" action="">
                <button formaction="{link}{COMMENT}{id}&amp;action=ban&amp;host={ip}" class="submit">{ip}</button>
            </form>
        </div>
    [endif]
</div>
<div class="clear"></div>
