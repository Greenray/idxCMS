<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE COUNTER

if (!defined('idxCMS')) die();

$stats = GetUnserialized(CONTENT.'stats');
$stats['registered'] = sizeof(GetFilesList(USERS));
$stats['visitors'] = sizeof($stats['online']);
$guests = 0;
$stats['loggedin'] = '';
foreach ($stats['online'] as $ip => $data) {
    if ($data['name'] === 'guest')
         ++$guests;
    else $stats['loggedin'] .= CreateUserLink($data['name'], $data['nick']).' ';
}
$stats['todayusers'] = empty($stats['users']) ? 0 : sizeof($stats['users']);
$stats['todayhosts'] = sizeof($stats['hosts']);
$stats['regonline']  = $stats['visitors'] - $guests;

$TPL = new TEMPLATE(dirname(__FILE__).DS.'counter.tpl');
ShowWindow(__('Counter'), $TPL->parse($stats));
?>