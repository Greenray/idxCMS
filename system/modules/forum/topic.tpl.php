<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE FORUM - TOPIC TEMPLATE

die();?>
<div class="topic">
    <div class="content">
        <div class="title"><h1>{title}</h1></div>
        <div class="author">
            <img src="{avatar}" hspace="5" vspace="5" alt="" /><br />
            <strong><a href="javascript:InsertText(document.forms['comment'].elements['text'], '[b]{nick}![/b]' + '\n');">{nick}</a></strong><br />
            [if=status]{status}<br />[endif]
            [if=stars][__Rate]: {stars}<br />[endif]
            [if=country]{country}<br />[endif]
            [if=city]{city}[endif]
        </div>
        [if=rateid]<div id="rate{rateid}">{rate}</div>[endif]
        <div class="info">
            <div class="date">[__Category]: <a href="{category_link}">{category_title}</a></div>
            <span class="date">{date}</span>
            [if=profile]
                <span class="profile">
                    <a href="{MODULE}user&amp;user={author}" title="[__Profile]">
                        <img src="{ICONS}profile.png" width="16" height="16" class="tip" alt="[__Profile]" />
                    </a>
                    <a href="{MODULE}user.pm&amp;for={author}" title="[__Private message]">
                        <img src="{ICONS}user-pm.png" width="16" height="16" class="tip" alt="[__Private message]" />
                    </a>
                </span>
            [endif]
        </div>
        <div class="text">
            {text}
        </div>
    </div>
    [if=admin]
        <div class="menu">
            <form name="topic" method="post" action="">
                <button formaction="{link}{ITEM}{id}&amp;action={action_pin}" class="submit">{command_pin}</button>
                <button formaction="{link}&amp;action={action}" class="submit">{command}</button>
            </form>
        </div>
    [endif]
    [if=moderator]
        <div class="menu">
            <form name="topic" method="post" action="">
                <button formaction="{link}&amp;action=edit" class="submit">[__Edit]</button>
                <button formaction="{link}&amp;action=delete" class="submit">[__Delete]</button>
            </form>
        </div>
    [endif]
    [if=ip]
        <div class="menu">
            <form name="topic" method="post" action="">
                <button formaction="{link}&amp;action=ban&amp;host={ip}" class="submit">{ip}</button>
            </form>
        </div>
    [endif]
</div>
<div class="clear"></div>

