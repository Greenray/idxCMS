<?php
/**
 * Statistics.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @Copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      admin/modules/_statistics/statistics.php
 * @package   Administration
 */

if (!defined('idxADMIN') || !USER::$root) die();

/**
 * Cleans website statistics.
 *
 * @param  string  $file  Name of the file with data
 * @param  string  $field Name of the datafield
 * @return boolean The result of operation
 */
function StatisticsClean($file, $field = '') {
    $stat = [];
    if (!empty($field)) {
        $stat = json_decode(file_get_contents($file), TRUE);
        if (is_array($stat[$field]))
             $stat[$field] = [];
        else $stat[$field] = 0;
    }
    return file_put_contents($file, json_encode($stat, JSON_UNESCAPED_UNICODE), LOCK_EX);
}

if (!empty($REQUEST['cleanrefs']))    StatisticsClean(CONTENT.'stats', 'ref');
if (!empty($REQUEST['cleanua']))      StatisticsClean(CONTENT.'stats', 'ua');
if (!empty($REQUEST['cleanstats']))   StatisticsClean(CONTENT.'stats');
if (!empty($REQUEST['cleanagents']))  StatisticsClean(CONTENT.'spiders', 'ua');
if (!empty($REQUEST['cleansip']))     StatisticsClean(CONTENT.'spiders', 'ip');
if (!empty($REQUEST['cleanspiders'])) StatisticsClean(CONTENT.'spiders');

$stats = json_decode(file_get_contents(CONTENT.'stats'), TRUE);
$output = [];

if (!empty($stats)) {
    $output['total_hosts'] = $stats['total'];
    $output['today_hosts'] = sizeof($stats['hosts']);
    if (!empty($stats['ref'])) {
        arsort($stats['ref']);

        foreach($stats['ref'] as $host => $ref) {
            $output['refs'][$host]['host']  = $host;
            $output['refs'][$host]['count'] = $ref;
        }
    }

    if (!empty($stats['ua'])) {
        arsort($stats['ua']);

        foreach($stats['ua'] as $agent => $count) {
            $output['uas'][$agent]['agent'] = $agent;
            $output['uas'][$agent]['count'] = $count;
        }
    }

    foreach($stats['hosts'] as $host => $time) {
        $output['hosts'][$host]['host'] = $host;
        $output['hosts'][$host]['time'] = FormatTime('d F Y H:i:s', $time);
    }

    foreach($stats['ip'] as $ip => $count) {
        $output['ips'][$ip]['host']  = $ip;
        $output['ips'][$ip]['count'] = $count;
    }

    foreach($stats['users'] as $i => $user) {
        $output['users'][$i]['user'] = $user;
    }

} else {
    $output['total_hosts'] = 0;
    $output['today_hosts'] = 0;
}

$spiders = json_decode(file_get_contents(CONTENT.'spiders'), TRUE);

if (!empty($spiders)) {
    $output['total'] = $spiders['total'];
    $output['today'] = $spiders['today'];
    if (!empty($spiders['ua'])) {
        arsort($spiders['ua']);

        foreach($spiders['ua'] as $agent => $ua) {
            $output['suas'][$agent]['agent'] = $i;
            $output['suas'][$agent]['count'] = $count;
        }
    }

    if (!empty($spiders['ip'])) {
        arsort($spiders['ip']);

        foreach($spiders['ip'] as $ip => $count) {
            $output['sips'][$ip]['ip']    = $ip;
            $output['sips'][$ip]['count'] = $count;
        }
    }

} else {
    $output['total'] = 0;
    $output['today'] = 0;
}

if (!empty($output)) {
    $TEMPLATE = new TEMPLATE(__DIR__.DS.'statistics.tpl');
    $TEMPLATE->set($output);
    echo $TEMPLATE->parse();
} else {
    ShowMessage('Statistics is off', MODULE.'admin');
}
