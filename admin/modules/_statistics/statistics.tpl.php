<?php
# idxCMS Flat Files Content Management System v4.1
# Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Dtatistics nemplate.

die();?>

<div class="module">__Site statistics__</div>
<fieldset>
    <table class="std">
        <tr class="light"><td>__Total hosts__</td><td>$total_hosts</td></tr>
        <tr class="light"><td>__Today hosts__</td><td>$today_hosts</td></tr>
<!-- IF $total_hosts > 0 -->
        <tr>
            <th colspan="2">
                <div class="center">
                    <p>
                    <!-- IF !empty($refs) -->
                        <a href="#ref" onclick="document.getElementById('ref').style.display=ShowHide(document.getElementById('ref').style.display)">__Referers__</a>
                    <!-- ELSE -->
                        __Referers__
                    <!-- ENDIF -->
                    </p>
                </div>
                <div id="ref" style="display:none;">
                    <table class="std">
                    <!-- FOREACH ref = $refs -->
                        <tr class="light">
                            <td class="stat left">$ref.host</td>
                            <td class="stat">$ref.count</td>
                        </tr>
                    <!-- ENDFOREACH -->
                    </table>
                </div>
            </th>
        </tr>
        <tr>
            <th colspan="2">
                <div class="center">
                    <p>
                    <!-- IF !empty($uas) -->
                        <a href="#ua" onclick="document.getElementById('ua').style.display=ShowHide(document.getElementById('ua').style.display)">__Browsers__</a>
                    <!-- ELSE -->
                        __Browsers__
                    <!-- ENDIF -->
                    </p>
                </div>
                <!-- IF !empty($uas) -->
                    <div id="ua" style="display:none;">
                        <table class="std">
                        <!-- FOREACH ua = $uas -->
                            <tr class="light">
                                <td class="stat left">$ua.agent</td>
                                <td class="stat">$ua.count</td>
                            </tr>
                        <!-- ENDFOREACH -->
                        </table>
                    </div>
                <!-- ENDIF -->
            </th>
        </tr>
        <tr><th colspan="2"><p>__Today hosts__</p></th></tr>
        <!-- FOREACH host = $hosts -->
            <tr class="light">
                <td>$host.host</td>
                <td>$host.time</td>
            </tr>
        <!-- ENDFOREACH -->
        <tr class="light"><td colspan="2"><!-- FOREACH user = $users -->$user.user <!-- ENDFOREACH --></td></tr>
        <tr>
            <th colspan="2">
                <div class="center">
                    <p>
                    <!-- IF !empty($ips) -->
                        <a href="#ip" onclick="document.getElementById('ip').style.display=ShowHide(document.getElementById('ip').style.display)">IP</a>
                    <!-- ELSE -->
                        IP
                    <!-- ENDIF -->
                    </p>
                </div>
                <div id="ip" style="display:none;">
                    <table class="std">
                    <!-- FOREACH ip = $ips -->
                        <tr class="light">
                            <td class="stat left">$ip.host</td>
                            <td class="stat">$ip.count</td>
                        </tr>
                    <!-- ENDFOREACH -->
                    </table>
                </div>
            </th>
        </tr>
    <!-- ENDIF -->
    </table>
    <form name="clean" method="post" >
        <p align="center">
            <input type="submit" name="cleanrefs" value="__Clean referers__" />
            <input type="submit" name="cleanua" value="__Clean agents__" />
            <input type="submit" name="cleanstats" value="__Clean stats__" />
        </p>
    </form>
</fieldset>
<div class="module">__Spiders__</div>
<fieldset>
    <table class="std">
        <tr class="light"><td>__Total__</td><td>$total</td></tr>
        <tr class="light"><td>__Today__</td><td>$today</td></tr>
    <!-- IF $total > 0 -->
        <tr>
            <th colspan="2">
                <div class="center">
                    <p>
                    <!-- IF !empty($sua) -->
                        <a href="#sua" onclick="document.getElementById('sua').style.display=ShowHide(document.getElementById('sua').style.display)">__User agents__</a>
                    <!-- ELSE -->
                        __Agents__
                    <!-- ENDIF -->
                    </p>
                </div>
                <div id="sua" style="display:none;">
                    <table class="std">
                    <!-- FOREACH sua = $suas -->
                        <tr class="light">
                            <td class="stat left">$sua.agent</td>
                            <td class="stat">$sua.count</td>
                        </tr>
                    <!-- ENDFOREACH -->
                    </table>
                </div>
            </th>
        </tr>
        <tr>
            <th colspan="2">
                <div class="center">
                    <p>
                    <!-- IF !empty($sip) -->
                        <a href="#sip" onclick="document.getElementById('sip').style.display=ShowHide(document.getElementById('sip').style.display)">IP</a>
                    <!-- ELSE -->
                        IP
                    <!-- ENDIF -->
                    </p>
                </div>
                <div id="sip" style="display:none;">
                    <table class="std">
                    <!-- FOREACH sip = $sips -->
                        <tr class="light">
                            <td class="stat left">$sip.ip</td>
                            <td class="stat">$sip.count</td>
                        </tr>
                    <!-- ENDFOREACH -->
                    </table>
                </div>
            </th>
        </tr>
    <!-- ENDIF -->
    </table>
    <form name="cleanspiders" method="post" >
        <p align="center">
            <input type="submit" name="cleanagents" value="__Clean agents__" />
            <input type="submit" name="cleansip" value="__Clean ip__" />
        <!-- IF $total > 0 -->
            <input type="submit" name="cleanspiders" value="__Clean spiders__" />
        <!-- ENDIF -->
        </p>
    </form>
</fieldset>

