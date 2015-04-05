<?php
# idxCMS Flat Files Content Management Sysytem
# Module User
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="comment">
    <a name="{id}"></a>
    <div class="content">
        [ifelse=inbox]
            <div class="author center">
                <img src="{avatar}" hspace="5" vspace="5" alt="" /><br />
                {nick}<br />
                [if=status]{status}<br />[/if]
                [if=stars][__Rate]: {stars}<br />[/if]
                [if=country]{country}<br />[/if]
                [if=city]{city}[/if]
            </div>
            <div class="info">
                {time}
                <span class="menu"><a href="{MODULE}user&amp;user={author}" title="[__Profile]"><img src="{ICONS}profile.png" width="16" height="16" alt="[__Profile]" /></a></span>
            </div>
            <div class="text justify">{text}</div>
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
            [/if]
        [else]
            <div class="author center">
                <img src="{avatar}" hspace="5" vspace="5" alt="" /><br />
                {nick}
                [if=country]<br />{country}[/if]
                [if=city]<br />{city}[/if]
            </div>
            <div class="date">{time}</div>
            <div class="text justify">{text}</div>
            <div class="menu">
                <form method="post" action="" class="menu">
                    <input type="hidden" name="mode" value="outbox" />
                    <button type="submit" name="remove" value="{id}" class="submit">[__Delete]</button>
                </form>
            </div>
        [/else]
    </div>
</div>
<div class="clear"></div>
