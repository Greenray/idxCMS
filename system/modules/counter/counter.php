<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module COUNTER

if (!defined('idxCMS')) die();

$stats = json_decode(file_get_contents(CONTENT.'stats'), TRUE); # Statistics datafile
$stats['registered'] = sizeof(GetFilesList(USERS));             # Number of regisered users
$stats['visitors']   = sizeof($stats['online']);                # Nubmer of visitors online
$stats['logged_in']  = '';                                      # Names of registered users online
$guests = 0;                                                    # Number of guests online

foreach ($stats['online'] as $ip => $data) {
    if ($data['name'] === 'guest') {
        ++$guests;
    } else $stats['logged_in'] .= CreateUserLink($data['name'], $data['nick']).' ';
}

$stats['todayusers'] = empty($stats['users']) ? 0 : sizeof($stats['users']);
$stats['todayhosts'] = sizeof($stats['hosts']);
$stats['regonline']  = $stats['visitors'] - $guests; # Number of registered users online

$TPL = new TEMPLATE(__DIR__.DS.'counter.tpl');
$TPL->set($stats);
SYSTEM::defineWindow('Counter', $TPL->parse());
