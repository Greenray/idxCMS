<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module POSTS: Posts calendar

if (!defined('idxCMS')) die();

$today = time();
$current_year  = FormatTime('Y', $today);
$current_month = FormatTime('n', $today);

if (!empty($REQUEST['from'])) {
    $selected_year  = FormatTime('Y', $REQUEST['from']);
    $selected_month = FormatTime('n', $REQUEST['from']);
}
if (!empty($REQUEST['cal-year'])) {
    if (($REQUEST['cal-year'] >= 2000) && ($REQUEST['cal-year'] <= $current_year)) {
        $selected_year = $REQUEST['cal-year'];
    }
} else  $selected_year = $current_year;

if (!empty($REQUEST['cal-month'])) {
    if (($REQUEST['cal-month'] >= 1) && ($REQUEST['cal-month'] <= 12)) {
        $selected_month = $REQUEST['cal-month'];
    }
} else  $selected_month = FormatTime('n', $today);

$CALENDAR = new CALENDAR($selected_month, $selected_year, $LANG['datetime']);
$sections = CMS::call('POSTS')->getSections();

unset($sections['drafts']);

foreach ($sections as $section => $data) {
    CMS::call('POSTS')->getCategories($section);
    $list = CMS::call('POSTS')->getStat('time');

    if (!empty($list)) {
        foreach($list as $id => $time) {

            if ((FormatTime('Y', $time) == $selected_year) && (FormatTime('n', $time) == $selected_month)) {
                $date = FormatTime('d', $time);
                $CALENDAR->event(
                    $date,
                    MODULE.'posts&from='.
                    mktime(0, 0, 0, $selected_month, $date, $selected_year).
                    '&until='.
                    mktime(23, 59, 59, $selected_month, $date, $selected_year)
                );
            }
        }
    }
}
if (($selected_year === $current_year) && ($selected_month === $current_month)) {
    $CALENDAR->highlight(FormatTime('d', $today));
}

$TEMPLATE = new TEMPLATE(__DIR__.DS.'calendar.tpl');
$TEMPLATE->set($CALENDAR->create($current_year, $selected_year, $selected_month));
SYSTEM::defineWindow('Posts calendar', $TEMPLATE->parse());
unset($CALENDAR);
