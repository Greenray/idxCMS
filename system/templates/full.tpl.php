<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
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
            <img src="{GALLERY}$section{DS}$category{DS}$id{DS}[$image:].jpg" alt="IMAGE" />
        </a>
    <!-- ENDIF -->
        $text
    </div>
    <!-- IF !empty($music) -->
        <div class="center" style="margin:10px 0;">
            <object type="application/x-shockwave-flash" data="{TOOLS}scmp3player.swf" id="mp3player1" width="$width" height="$height">
                <param name="movie" value="{TOOLS}scmp3player.swf">
                <param name="FlashVars" value="playerID=1&bg=$bgcolor&leftbg=$leftbg&lefticon=$lefticon&rightbg=$rightbg&rightbghover=$rightbghover&righticon=$righticon&righticonhover=$righticonhover&text=$pl_txt&slider=$slider&track=$track&border=$border&loader=$loader&loop=no&autostart=no&soundFile={CONTENT}catalogs{DS}$section{DS}$category{DS}$id{DS}$music">
                <param name="quality" value="high">
                <param name="menu" value="FALSE">
                <param name="wmode" value="transparent">
            </object>
        </div>
        <div class="center"><hr />__Copyright__: &copy; $copyright | __Size__: $size __bytes__ | __Downloads__: $downloads</div>
        <div class="info">
            <span class="author center">__Posted by__: <a href="{MODULE}user&amp;user=$author" class="tip" title="__Profile__">$nick</a></span>
            <span class="admin"><a href="$link&get=1" target="_blank">__Download__</a></span>
        </div>
    <!-- ELSEIF !empty($file) -->
        <div class="center"><hr />__Copyright__: &copy; $copyright | __Size__: $size __bytes__ | __Downloads__: $downloads</div>
        <div class="info">
            <span class="author center">__Posted by__: <a href="{MODULE}user&amp;user=$author" class="tip" title="__Profile__">$nick</a></span>
            <span class="admin"><a href="$link&get=1" target="_blank">__Download__</a></span>
        </div>
    <!-- ELSEIF !empty($site) -->
        <div class="center"><hr /><a href="$link&go=1" target="_blank">__Go__</a></div>
        <div class="info">
            <span class="author center">__Posted by__: <a href="{MODULE}user&amp;user=$author" class="tip" title="__Profile__">$nick</a><br />__Copyright__: &copy; $copyright</span>
            <span class="admin">__Transitions__: $clicks</span>
        </div>
    <!-- ELSEIF !empty($image) -->
        <div class="info">
            <span class="author center">__Posted by__: <a href="{MODULE}user&amp;user=$author" class="tip" title="__Profile__">$nick</a></span>
            <span class="admin">__Copyright__: $copyright</span>
        </div>
    <!-- ELSE -->
        <div class="info">
            <hr />
            <a href="{MODULE}posts.print{SECTION}$section{CATEGORY}$category{ITEM}$id" class="icon icon-printer tip" title="__Version for printer__" target="_blank"></a>
            <span class="author center">__Author__: <a href="{MODULE}user&amp;user=$author" class="tip" title="__Profile__">$nick</a></span>
            <!-- IF !empty($admin) -->
                <div class="actions">
                    <form name="post" method="post" >
                        <button type="submit" formaction="{MODULE}$module{SECTION}$section{CATEGORY}$category{ITEM}$id&amp;action=$action">$command</button>
                    </form>
                </div>
            <!-- ENDIF -->
        </div>
    <!-- ENDIF -->
</div>
