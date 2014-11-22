<?php
# idxCMS version 2.3
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE COUNTER

if (!defined('idxCMS')) die();

$stats = GetUnserialized(CONTENT.'stats');          # Statistic datafile 
$stats['registered'] = sizeof(GetFilesList(USERS)); # Number of regisered users
$stats['visitors'] = sizeof($stats['online']);      # Nubmer of visitors online
$guests = 0;                                        # Number of guests online
$stats['loggedin'] = '';                            # Names of registered users online

foreach ($stats['online'] as $ip => $data) {
    if ($data['name'] === 'guest') {
        ++$guests;
    } else {
        $stats['loggedin'] .= CreateUserLink($data['name'], $data['nick']).' ';
    }
}

$stats['todayusers'] = empty($stats['users']) ? 0 : sizeof($stats['users']);
$stats['todayhosts'] = sizeof($stats['hosts']);
$stats['regonline']  = $stats['visitors'] - $guests;                         # Number of registered users online

$TPL = new TEMPLATE(dirname(__FILE__).DS.'counter.tpl');
ShowWindow(__('Counter'), $TPL->parse($stats));
?>