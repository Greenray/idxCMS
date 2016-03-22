<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Module USER: Template for private messages

die();?>

<div class="comment">
    <a id="$id"></a>
    <div class="content">
    <!-- IF !empty($inbox) -->
        <div class="head">
            <span class="date">$time</span>
            <span class="actions"><a href="{MODULE}user&amp;user=$author" class="icon icon-profile tip" title="__Profile__"></a></span>
        </div>
        <div class="author center">
            <div class="avatar center"><img src="$avatar" alt="AVATAR" /></div>
            $nick
            <!-- IF !empty($status) --><div>$status</div><!-- ENDIF -->
            <!-- IF !empty($stars) --><div>__Rate__: $stars</div><!-- ENDIF -->
            <!-- IF !empty($city) --><div>$city</div><!-- ENDIF -->
            <!-- IF !empty($country) --><div>$country</div><!-- ENDIF -->
        </div>
        <div class="text justify">$text</div>
    </div>
    <div class="actions">
        <form method="post"  class="actions">
            <button type="submit" name="delete" value="$id">__Delete__</button>
        </form>
        <form method="post"  class="actions">
            <input type="hidden" name="user" value="$author" />
            <button type="submit" name="mode" value="outbox">__Outbox__</button>
        </form>
        <!-- IF !empty($reply) -->
            <form method="post"  class="actions">
                <input type="hidden" name="re" value="$id" />
                <button type="submit" name="reply" value="$author">__Reply__</button>
            </form>
        <!-- ENDIF -->
    <!-- ELSE -->
        <div class="author center">
            <img src="$avatar" alt="AVATAR" /><br />
            $nick
            <!-- IF !empty($country) --><br />$country<!-- ENDIF -->
            <!-- IF !empty($city) --><br />$city<!-- ENDIF -->
        </div>
        <div class="date">$time</div>
        <div class="text justify">$text</div>
        <div class="actions">
            <form method="post"  class="actions">
                <input type="hidden" name="mode" value="outbox" />
                <button type="submit" name="remove" value="$id">__Delete__</button>
            </form>
        </div>
    <!-- ENDIF -->
    </div>
</div>
<div class="clear"></div>
