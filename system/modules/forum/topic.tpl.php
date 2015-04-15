<?php
# idxCMS Flat Files Content Management Sysytem
# Module Forum
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="topic">
    <div class="content">
        <div class="title"><h1>{title}</h1></div>
        <div>
            <div class="info">
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
                [/if]
            </div>
            <div class="author center">
                <img src="{avatar}" hspace="5" vspace="5" alt="" /><br />
                <strong><a href="javascript:InsertText(document.forms['comment'].elements['text'], '[b]{nick}![/b]' + '\n');">{nick}</a></strong><br />
                [if=status]{status}<br />[/if]
                [if=stars][__Rate]: {stars}<br />[/if]
                [if=country]{country}<br />[/if]
                [if=city]{city}[/if]
            </div>
            <div class="text justify">{text}</div>
        </div>
    </div>
    [if=admin]
        <div class="menu">
            <form name="topic" method="post" action="">
                <button formaction="{link}{ITEM}{id}&amp;action={action_pin}" class="submit">{command_pin}</button>
                <button formaction="{link}&amp;action={action}" class="submit">{command}</button>
            </form>
        </div>
    [/if]
    [if=moderator]
        <div class="menu">
            <form name="topic" method="post" action="">
                <button formaction="{link}&amp;action=edit" class="submit">[__Edit]</button>
                <button formaction="{link}&amp;action=delete" class="submit">[__Delete]</button>
            </form>
        </div>
    [/if]
    [if=ip]
        <div class="menu">
            <form name="topic" method="post" action="">
                <button formaction="{link}&amp;action=ban&amp;host={ip}" class="submit">{ip}</button>
            </form>
        </div>
    [/if]
</div>
<div class="clear"></div>

