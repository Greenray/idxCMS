<?php
# idxCMS Flat Files Content Management Sysytem
# Module Menu
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div id="menu">
    <ul class="main_menu">
        [each=menu]
        <li>
            <a href="{menu[link]}">{menu[name]}</a>
            [ifelse=menu[section]]
                <ul>
                [each=menu[section]]
                    <li><a href="{section[link]}" style="width:{section[width]}px">{section[title]}</a></li>
                [/each.menu[section]]
                </ul>
            [else]
                [if=menu[category]]
                    <ul>
                    [each=menu[category]]
                        <li><a href="{category[link]}">{category[title]}</a></li>
                    [/each.menu[category]]
                    </ul>
                [/if]
            [/else]
        </li>
        [/each.menu]
    </ul>
</div>