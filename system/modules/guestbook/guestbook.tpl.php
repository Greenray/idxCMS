<?php
# idxCMS Flat Files Content Management Sysytem
# Module Guestbook
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

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
            [/else]
            [if=status]{status}<br />[/if]
            [if=stars][__Rate]: {stars}<br />[/if]
            [if=country]{country}<br />[/if]
            [if=city]{city}[/if]
        </div>
        <div class="info">
            <span class="date">{date}</span>
            <span class="menu">
            [if=user]
                <a href="{MODULE}user&amp;user={author}" title="[__Profile]"><img src="{ICONS}profile.png" width="16" height="16" alt="[__Profile]" /></a>
                <a href="{MODULE}user.pm&amp;for={author}" title="[__Private message]"><img src="{ICONS}user-pm.png" width="16" height="16" alt="" /></a>
            [/if]
            </span>
        </div>
        <div class="text justify">{text}</div>
    </div>
    <div class="menu">
    [if=ban]<a href="{ban}&amp;action=ban&amp;host={ip}" title="[__Ban]">[ {ip} ]</a>[/if]
    [if=moderator]
        <a href="{MODULE}guestbook&amp;message={id}&amp;action=edit">[__Edit]</a>
        <a href="{MODULE}guestbook&amp;message={id}&amp;action=delete">[__Delete]</a>
    [/if]
    </div>
</div>
<div class="clear"></div>
