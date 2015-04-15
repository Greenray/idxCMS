<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Statistics
# Version 2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

die();?>
<div class="module">[__Site statistics]</div>
<fieldset>
    <table class="std">
        <tr class="odd"><td>[__Total hosts]</td><td>{total_hosts}</td></tr>
        <tr class="odd"><td>[__Today hosts]</td><td>{today_hosts}</td></tr>
        <tr>
            <th colspan="2">
                <div class="center">
                    <p><a href="#ref" onclick="document.getElementById('ref').style.display=ShowHide(document.getElementById('ref').style.display)">[__Referers]</a></p>
                </div>
                <div id="ref" class="none">
                    <table class="std">
                    [foreach=ref.host.count]
                        <tr class="odd">
                            <td class="stat left">{host}</td>
                            <td class="stat">{count}</td>
                        </tr>
                    [/foreach.ref]
                    </table>
                </div>
            </th>
        </tr>
        <tr>
            <th colspan="2">
                <div class="center">
                    <p><a href="#ua" onclick="document.getElementById('ua').style.display=ShowHide(document.getElementById('ua').style.display)">[__Browsers]</a></p>
                </div>
                <div id="ua" class="none">
                    <table class="std">
                    [foreach=ua.agent.count]
                        <tr class="odd">
                            <td class="stat left">{agent}</td>
                            <td class="stat">{count}</td>
                        </tr>
                    [/foreach.ua]
                    </table>
                </div>
            </th>
        </tr>
        <tr><th colspan="2">[__Today hosts]</th></tr>
        [foreach=hosts.host.time]<tr class="odd"><td>{host}</td><td>{time}</td></tr>[/foreach.hosts]
        <tr class="odd"><td colspan="2">[foreach=users.key.user]{user} [/foreach.users]</td></tr>
        <tr>
            <th colspan="2">
                <div class="center"><p><a href="#ip" onclick="document.getElementById('ip').style.display=ShowHide(document.getElementById('ip').style.display)">[__IP]</a></p></div>
                <div id="ip" class="none">
                    <table class="std">
                    [foreach=ip.host.count]
                        <tr class="odd">
                            <td class="stat left">{host}</td>
                            <td class="stat">{count}</td>
                        </tr>
                    [/foreach.ip]
                    </table>
                </div>
            </th>
        </tr>
    </table>
    <form name="clean" method="post" action="">
        <p align="center">
            <input type="submit" name="cleanrefs" value="[__Clean referers]" class="submit" />
            <input type="submit" name="cleanua" value="[__Clean agents]" class="submit" />
            <input type="submit" name="cleanstats" value="[__Clean stats]" class="submit" />
        </p>
    </form>
</fieldset>
<div class="module">[__Spiders]</div>
<fieldset>
    <table class="std">
        <tr class="odd"><td>[__Total]</td><td>{total}</td></tr>
        <tr class="odd"><td>[__Today]</td><td>{today}</td></tr>
        <tr>
            <th colspan="2">
                <div class="center">
                    <p><a href="#sua" onclick="document.getElementById('sua').style.display=ShowHide(document.getElementById('sua').style.display)">[__User agents]</a></p>
                </div>
                <div id="sua" class="none">
                    <table class="std">
                    [foreach=sua.name.count]
                        <tr class="odd">
                            <td class="stat left">{name}</td>
                            <td class="stat">{count}</td>
                        </tr>
                    [/foreach.sua]
                    </table>
                </div>
            </th>
        </tr>
        <tr>
            <th colspan="2">
                <div class="center">
                    <p><a href="#sip" onclick="document.getElementById('sip').style.display=ShowHide(document.getElementById('sip').style.display)">[__Spiders IP]</a></p>
                </div>
                <div id="sip" class="none">
                    <table class="std">
                    [foreach=sip.ip.count]
                        <tr class="odd">
                            <td class="stat left">{ip}</td>
                            <td class="stat">{count}</td>
                        </tr>
                    [/foreach.sip]
                    </table>
                </div>
            </th>
        </tr>
    </table>
    <form name="cleanspiders" method="post" action="">
        <p align="center">
            <input type="submit" name="cleanagents" value="[__Clean agents]" class="submit" />
            <input type="submit" name="cleansip" value="[__Clean ip]" class="submit" />
            <input type="submit" name="cleanspiders" value="[__Clean spiders]" class="submit" />
        </p>
    </form>
</fieldset>

