<?php
# idxCMS Flat Files Content Management Sysytem
# Module Catalogs
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="post">
    <div class="info">
        [if=rateid]<div id="rate{rateid}">{rate}</div>[/if]
        <div class="date">[__Category]: <a href="{MODULE}catalogs{SECTION}{section}{CATEGORY}{category}">{category_title}</a></div>
        <span class="date">{date}</span>
    </div>
    <div class="title"><h1>{title}</h1></div>
    <div class="text justify">{text}</div>
    [if=song]
        <div class="center" style="margin:10px 0;">
            <object type="application/x-shockwave-flash" data="{TOOLS}scmp3player.swf" id="mp3player1" width="{width}" height="{height}">
                <param name="movie" value="{TOOLS}scmp3player.swf">
                <param name="FlashVars" value="playerID=1&amp;bg={bgcolor}&amp;leftbg={leftbg}&amp;lefticon={lefticon}&amp;rightbg={rightbg}&amp;rightbghover={rightbghover}&amp;righticon={righticon}&amp;righticonhover={righticonhover}&amp;text={pl_txt}&amp;slider={slider}&amp;track={track}&amp;border={border}&amp;loader={loader}&amp;loop={loop}&amp;autostart={autostart}&amp;soundFile={CONTENT}catalogs{DS}{section}{DS}{category}{DS}{id}{DS}{song}">
                <param name="quality" value="high">
                <param name="menu" value="FALSE">
                <param name="wmode" value="transparent">
            </object>
        </div>
        <div class="center"><hr />[__Copyright]: &copy; {copyright} | [__Size]: {size} [__bytes] | [__Downloads]: {downloads}</div>
        <div class="info">
            <span class="author center">[__Posted by]: <a href="{MODULE}user&amp;user={author}">{nick}</a></span>
            <span class="admin"><a href="{link}&amp;get=1" target="_blank">[__Download]</a></span>
        </div>
    [/if]
    [if=file]
        <div class="center"><hr />[__Copyright]: &copy; {copyright} | [__Size]: {size} [__bytes] | [__Downloads]: {downloads}</div>
        <div class="info">
            <span class="author center">[__Posted by]: <a href="{MODULE}user&amp;user={author}">{nick}</a></span>
            <span class="admin"><a href="{link}&amp;get=1" target="_blank">[__Download]</a></span>
        </div>
    [/if]
    [if=site]
        <div class="center"><hr /><a href="{link}&amp;go=1" target="_blank">[__Go]</a></div>
        <div class="info">
            <span class="author center">[__Posted by]: <a href="{MODULE}user&amp;user={author}">{nick}</a><br />[__Copyright]: &copy; {copyright}</span>
            <span class="admin">[__Transitions]: {clicks}</span>
        </div>
    [/if]
</div>
