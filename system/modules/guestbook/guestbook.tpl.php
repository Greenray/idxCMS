<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# GUESTBOOK TEMPLATE

die();?>

<div class="comment">
    <a name="{id}"></a>
    <div class="content">
        <div class="author center">
            <img src="{avatar}" hspace="5" vspace="5" alt="" /><br />
            [ifelse=user]
                <strong><a href="javascript:InsertText(document.forms['post-comment'].elements['text'], '[b]{nick}![/b]' + '\n');">{nick}</a></strong><br />
            [else]
                {nick}<br />
            [endelse]
            [if=status]{status}<br />[endif]
            [if=stars][__Rate]: {stars}<br />[endif]
            [if=country]{country}<br />[endif]
            [if=city]{city}[endif]
        </div>
        <div class="info">
            <span class="date">{date}</span>
            <span class="menu">
                [if=user]
                    <a href="{MODULE}user&amp;user={author}" title="[__Profile]">
                        <img src="{ICONS}profile.png" width="16" height="16" alt="[__Profile]" />
                    </a>
                    <a href="{MODULE}user.pm&amp;for={author}" title="[__Private message]">
                        <img src="{ICONS}user-pm.png" width="16" height="16" alt="" />
                    </a>
                [endif]
            </span>
        </div>
        <div class="text justify">
            {text}
        </div>
    </div>
    <div class="menu">
        [if=ban]<a href="{ban}&amp;action=ban&amp;host={ip}" title="[__Ban]">[ {ip} ]</a>[endif]
        [if=moderator]
            <a href="{MODULE}guestbook&amp;message={id}&amp;action=edit">[__Edit]</a>
            <a href="{MODULE}guestbook&amp;message={id}&amp;action=delete">[__Delete]</a>
        [endif]
    </div>
</div>
<div class="clear"></div>
