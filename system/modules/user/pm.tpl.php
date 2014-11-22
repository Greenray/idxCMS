<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE USER - PRIVATE MESSAGES TEMPLATE

die();?>
<div class="comment">
    <a name="{id}"></a>
    <div class="content">
        [ifelse=inbox]
            <div class="author">
                <img src="{avatar}" hspace="5" vspace="5" alt="" /><br />
                {nick}<br />
                [if=status]{status}<br />[endif]
                [if=stars][__Rate]: {stars}<br />[endif]
                [if=country]{country}<br />[endif]
                [if=city]{city}[endif]
            </div>
            <div class="info">
                {time}
                <span class="menu">
                    <a href="{MODULE}user&amp;user={author}" title="[__Profile]">
                        <img src="{ICONS}profile.png" width="16" height="16" alt="[__Profile]" />
                    </a>
                </span>
            </div>
            <div class="text">{text}</div>
        </div>
        <div class="menu">
            <form method="post" action="" class="menu">
                <button type="submit" name="delete" value="{id}" class="submit">[__Delete]</button>
            </form>
            <form method="post" action="" class="menu">
                <input type="hidden" name="user" value="{author}" />
                <button type="submit" name="mode" value="outbox" class="submit">[__Outbox]</button>
            </form>
            [if=reply]
                <form method="post" action="" class="menu">
                    <input type="hidden" name="re" value="{id}" />
                    <button type="submit" name="reply" value="{author}" class="submit">[__Reply]</button>
                </form>
            [endif]
        [else]
            <div class="author">
                <img src="{avatar}" hspace="5" vspace="5" alt="" /><br />
                {nick}
                [if=country]<br />{country}[endif]
                [if=city]<br />{city}[endif]
            </div>
            <div class="date">{time}</div>
            <div class="text">{text}</div>
            <div class="menu">
                <form method="post" action="" class="menu">
                    <input type="hidden" name="mode" value="outbox" />
                    <button type="submit" name="remove" value="{id}" class="submit">[__Delete]</button>
                </form>
            </div>
        [endelse]
    </div>
</div>
<div class="clear"></div>
