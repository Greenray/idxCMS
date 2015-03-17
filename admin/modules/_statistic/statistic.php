<?php
# idxCMS Flat Files Content Management Sysytem
# Administration - Statistic
# Version 2.3
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxADMIN') || !CMS::call('USER')->checkRoot()) die();

function StatisticClean($file, $field = '') {
    $stat = [];
    if (!empty($field)) {
        $stat = GetUnserialized($file);
        if (is_array($stat[$field])) {
             $stat[$field] = [];
        } else {
            $stat[$field] = 0;
        }
    }
    return file_put_contents($file, serialize($stat), LOCK_EX);
}

if (!empty($REQUEST['cleanrefs']))    StatisticClean(CONTENT.'stats', 'ref');
if (!empty($REQUEST['cleanua']))      StatisticClean(CONTENT.'stats', 'ua');
if (!empty($REQUEST['cleanstats']))   StatisticClean(CONTENT.'stats');
if (!empty($REQUEST['cleanagents']))  StatisticClean(CONTENT.'spiders', 'ua');
if (!empty($REQUEST['cleansip']))     StatisticClean(CONTENT.'spiders', 'ip');
if (!empty($REQUEST['cleanspiders'])) StatisticClean(CONTENT.'spiders');

$stats = GetUnserialized(CONTENT.'stats');
$output = [];

if (!empty($stats)) {
    $output['total_hosts'] = $stats['total'];
    $output['today_hosts'] = sizeof($stats['hosts']);
    if (!empty($stats['ref'])) {
        arsort($stats['ref']);
    }
    $output['ref'] = $stats['ref'];
    if (!empty($stats['ua'])) {
        arsort($stats['ua']);
    }
    $output['ua'] = $stats['ua'];
    foreach($stats['hosts'] as $host => $time) {
        $output['hosts'][$host] = FormatTime('d F Y H:i:s', $time);
    }
    $output['ip'] = $stats['ip'];
    $output['users'] = $stats['users'];
} else {
    $output['total_hosts'] = 0;
    $output['today_hosts'] = 0;
}

$spiders = GetUnserialized(CONTENT.'spiders');

if (!empty($spiders)) {
    $output['total'] = $spiders['total'];
    $output['today'] = $spiders['today'];
    if (!empty($spiders['ua'])) {
        arsort($spiders['ua']);
    }
    $output['sua'] = $spiders['ua'];
    if (!empty($spiders['ip'])) {
        arsort($spiders['ip']);
    }
    $output['sip'] = $spiders['ip'];
} else {
    $output['total'] = 0;
    $output['today'] = 0;
}

if (!empty($output)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'statistic.tpl');
    echo $TPL->parse($output);
}
