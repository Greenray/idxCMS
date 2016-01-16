<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2011 - 2016 Victor Nabatov
# The template for full item

die();?>

<div class="post">
    <div class="info">
        <!-- IF !empty($rateid) -->
            <div id="rate[$rateid:]">$rate</div>
        <!-- ENDIF -->
        <div class="date">__Category__: <a href="{MODULE}$module{SECTION}$section{CATEGORY}$category">$category_title</a></div>
        <span class="date">$date</span>
    </div>
    <div class="title"><h1>$title</h1></div>
    <div class="text justify">
    <!-- IF !empty($image) -->
        <a class="cbox" href="{GALLERY}$section{DS}$category{DS}$id{DS}$image" title="$title">
            <img src="{GALLERY}$section{DS}$category{DS}$id{DS}[$image:].jpg" hspace="10" vspace="10" alt="" />
        </a>
    <!-- ENDIF -->
        $text
    </div>
    <!-- IF !empty($music) -->
        <div class="center" style="margin:10px 0;">
            <object type="application/x-shockwave-flash" data="{TOOLS}scmp3player.swf" id="mp3player1" width="$width" height="$height">
                <param name="movie" value="{TOOLS}scmp3player.swf">
                <param name="FlashVars" value="playerID=1&amp;bg=$bgcolor&amp;leftbg=$leftbg&amp;lefticon=$lefticon&amp;rightbg=$rightbg&amp;rightbghover=$rightbghover&amp;righticon=$righticon&amp;righticonhover=$righticonhover&amp;text=$pl_txt&amp;slider=$slider&amp;track=$track&amp;border=$border&amp;loader=$loader&amp;loop=no&amp;autostart=no&amp;soundFile={CONTENT}catalogs{DS}$section{DS}$category{DS}$id{DS}$music">
                <param name="quality" value="high">
                <param name="menu" value="FALSE">
                <param name="wmode" value="transparent">
            </object>
        </div>
        <div class="center"><hr />__Copyright__: &copy; $copyright | __Size__: $size __bytes__ | __Downloads__: $downloads</div>
        <div class="info">
            <span class="author center">__Posted by__: <a href="{MODULE}user&amp;user=$author">$nick</a></span>
            <span class="admin"><a href="$link&amp;get=1" target="_blank">__Download__</a></span>
        </div>
    <!-- ELSEIF !empty($file) -->
        <div class="center"><hr />__Copyright__: &copy; $copyright | __Size__: $size __bytes__ | __Downloads__: $downloads</div>
        <div class="info">
            <span class="author center">__Posted by__: <a href="{MODULE}user&amp;user=$author">$nick</a></span>
            <span class="admin"><a href="$link&amp;get=1" target="_blank">__Download__</a></span>
        </div>
    <!-- ELSEIF !empty($site) -->
        <div class="center"><hr /><a href="$link&amp;go=1" target="_blank">__Go__</a></div>
        <div class="info">
            <span class="author center">__Posted by__: <a href="{MODULE}user&amp;user=$author">$nick</a><br />__Copyright__: &copy; $copyright</span>
            <span class="admin">__Transitions__: $clicks</span>
        </div>
    <!-- ELSEIF !empty($image) -->
        <div class="info">
            <span class="author center">__Posted by__: <a href="{MODULE}user&amp;user=$author">$nick</a></span>
            <span class="admin">__Copyright__: $copyright</span>
        </div>
    <!-- ELSE -->
        <div class="info">
            <hr />
            <a href="{MODULE}posts.print{SECTION}$section{CATEGORY}$category{ITEM}$id" target="_blank">
                <img src="{ICONS}printer.png" width="16" height="16" hspace="5" vspace="5" class="tip" alt="__Version for printer__" />
            </a>
            <span class="author center">__Author__: <a href="{MODULE}user&user=$author">$nick</a></span>
            <!-- IF !empty($admin) -->
                <div class="actions">
                    <form name="post" method="post" action="">
                        <button type="submit" formaction="{MODULE}$module{SECTION}$section{CATEGORY}$category{ITEM}$id&action=$action">$command</button>
                    </form>
                </div>
            <!-- ENDIF -->
        </div>
    <!-- ENDIF -->
</div>
