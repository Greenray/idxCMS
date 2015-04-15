<?php
/** Statistics: view and manage.
 *
 * @program   idxCMS: Flat Files Content Management Sysytem
 * @file      admin/modules/_statistics/statistics.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-Share Alike 4.0 Unported License
 * @package   Statistics
 */

if (!defined('idxADMIN') || !USER::$root) die();

/** Cleans website statistics.
 *
 * @param  string  $file  Name of the file with data
 * @param  string  $field Name of the datafield
 * @return boolean The result of operation
 */
function StatisticsClean($file, $field = '') {
    $stat = [];
    if (!empty($field)) {
        $stat = GetUnserialized($file);
        if (is_array($stat[$field]))
             $stat[$field] = [];
        else $stat[$field] = 0;
    }
    return file_put_contents($file, serialize($stat), LOCK_EX);
}

if (!empty($REQUEST['cleanrefs']))    StatisticsClean(CONTENT.'stats', 'ref');
if (!empty($REQUEST['cleanua']))      StatisticsClean(CONTENT.'stats', 'ua');
if (!empty($REQUEST['cleanstats']))   StatisticsClean(CONTENT.'stats');
if (!empty($REQUEST['cleanagents']))  StatisticsClean(CONTENT.'spiders', 'ua');
if (!empty($REQUEST['cleansip']))     StatisticsClean(CONTENT.'spiders', 'ip');
if (!empty($REQUEST['cleanspiders'])) StatisticsClean(CONTENT.'spiders');

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
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'statistics.tpl');
    echo $TPL->parse($output);
}
