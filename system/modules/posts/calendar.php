<?php
# idxCMS Flat Files Content Management Sysytem
# Module Posts
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$today = time();
$current_year  = (int) FormatTime('Y', $today);
$current_month = (int) FormatTime('n', $today);

if (!empty($REQUEST['from'])) {
    $selected_year  = (int) FormatTime('Y', $REQUEST['from']);
    $selected_month = (int) FormatTime('n', $REQUEST['from']);
}

if (!empty($REQUEST['cal-year'])) {
    $year = (int) $REQUEST['cal-year'];
    if (($year >= 2000) && ($year <= $current_year)) {
        $selected_year = $year;
    }
} else  $selected_year = $current_year;

if (!empty($REQUEST['cal-month'])) {
    $month = (int) $REQUEST['cal-month'];
    if (($month >= 1) && ($month <= 12)) {
        $selected_month = $month;
    }
} else  $selected_month = (int) FormatTime('n', $today);

$CALENDAR = new CALENDAR($selected_month, $selected_year, $LANG['datetime']);
$sections = CMS::call('POSTS')->getSections();

if (!empty($sections['drafts'])) unset($sections['drafts']);
foreach ($sections as $section => $data) {
    CMS::call('POSTS')->getCategories($section);
    $list = CMS::call('POSTS')->getStat('time');
    if (!empty($list)) {
        foreach($list as $id => $time) {
            if ((FormatTime('Y', $time) == $selected_year) && (FormatTime('n', $time) == $selected_month)) {
                $date = FormatTime('d', $time);
                $CALENDAR->event(
                    $date,
                    MODULE.'posts&amp;from='.
                    mktime(0, 0, 0, $selected_month, $date, $selected_year).
                    '&amp;until='.
                    mktime(23, 59, 59, $selected_month, $date, $selected_year)
                );
            }
        }
    }
}

if (($selected_year === $current_year) && ($selected_month === $current_month)) {
    $CALENDAR->highlight(FormatTime('d', $today));
}

$TPL = new TEMPLATE(dirname(__FILE__).DS.'calendar.tpl');
ShowWindow(__('Posts calendar'), $TPL->parse($CALENDAR->create($current_year, $selected_year, $selected_month)));
unset($CALENDAR);
