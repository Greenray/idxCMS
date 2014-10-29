<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE POSTS - MODULE INITIALIZATION

if (!defined('idxCMS')) die();

define('POSTS', CONTENT.'posts'.DS);

# POSTS class
class POSTS extends CONTENT {

    function __construct() {
        $this->module = 'posts';
        $this->container = POSTS;
    }
}

# CALENDAR class
# Creates calendar with the possibility to search of posts.
class CALENDAR {

    private $today = array();
    private $events = array();
    private $temp = array();

    function __construct($month, $year, $datetime) {
        $this->temp['first_day']      = mktime(0, 0, 0, $month, 1, $year);
        $this->temp['first_day_week'] = date('w', $this->temp['first_day']);
        $this->temp['num_of_days']    = date('t', $this->temp['first_day']);
        $this->datetime = $datetime;
    }

    # Assign event to calendar.
    function event($day, $link) {
        $this->events[intval($day)] = $link;
    }

    function highlight($day, $style = '!') {
        $this->today[intval($day)] = $style;
    }

    function create($current_year, $selected_year, $selected_month) {
        foreach (array(1 => 'January',
                       2 => 'February',
                       3 => 'March',
                       4 => 'April',
                       5 => 'May',
                       6 => 'June',
                       7 => 'July',
                       8 => 'August',
                       9 => 'September',
                      10 => 'October',
                      11 => 'November',
                      12 => 'December') as $num => $month) {
            $months[$num]['name'] = $this->datetime[$month];
            $months[$num]['num']  = $num;
            if ($num == $selected_month) {
                 $months[$num]['selected'] = TRUE;
            }
        }
        for ($num = 2000; $num <= $current_year; $num++) {
            $years[$num]['year'] = $num;
            if ($num == $selected_year) {
                 $years[$num]['selected'] = TRUE;
            }
        }
        $position = ($this->temp['first_day_week'] == 0) ? 7 : $this->temp['first_day_week'];
        $calendar = array();
        $string   = 1;
        $showed   = 1;
        while ($showed <= $this->temp['num_of_days']) {
            if ($position > 1) {
                $calendar[$string]['span'] = $position - 1;
            }
            $inc = 0;
            for ($i = $showed; $i < ($showed + 7) && ($i <= $this->temp['num_of_days']) && ($position <= 7); $i++) {
                $class = 'event';
                if (empty($this->events[$i])) {
                    $class = 'usual';
                } else {
                    $calendar[$string]['dates'][$i]['events'] = $this->events[$i];
                }
                if (!empty($this->today[$i]) && ($current_year === $selected_year)) {
                    $class .= ' special';
                }
                $calendar[$string]['dates'][$i]['class'] = $class;
                $calendar[$string]['dates'][$i]['date']  = $i;
                $position++;
                $inc++;
            }
            $showed = $showed + $inc;
            $position = 0;
            ++$string;
        }
        return array(
            'month'  => LocaliseDate(date('F Y', $this->temp['first_day'])),
            'header' => LocaliseDate(
                '<th>Mon</th>
                 <th>Tue</th>
                 <th>Wed</th>
                 <th>Thu</th>
                 <th>Fri</th>
                 <th>Sat</th>
                 <th>Sun</th>'
            ),
            'calendar'       => $calendar,
            'months'         => $months,
            'years'          => $years,
            'selected_year'  => $selected_year,
            'selected_month' => $selected_month
        );
    }
}

SYSTEM::registerModule('posts', 'Posts', 'main', 'system');
SYSTEM::registerModule('posts.post', 'Posting form', 'main', 'system');
SYSTEM::registerModule('posts.calendar', 'Posts calendar', 'box', 'system');
SYSTEM::registerModule('posts.last', 'Last posts', 'box');
SYSTEM::registerModule('posts.news', 'Last news', 'box');
SYSTEM::registerModule('posts.print', 'Version for printer', 'plugin');
USER::setSystemRights(array('posts' => __('Posts').': '.__('Moderator')));
SYSTEM::registerMainMenu('posts');
SYSTEM::registerSiteMap('posts');
SYSTEM::registerSearch('posts');

$sections =  CMS::call('POSTS')->getSections();

if (!empty($sections)) {
    # Register RSS feeds for posts sections (ex. drafts)
    if (!empty($sections['drafts'])) {
        unset($sections['drafts']);
    }
    if (!empty($sections['archive'])) {
        unset($sections['archive']);
    }
    foreach ($sections as $id => $section) {
        if ($section['access'] === 0) {
            SYSTEM::registerFeed(
                'posts@'.$id,
                 $section['title'],
                __('RSS for section').' '.$section['title'],
                'posts'
            );
        }
    }
}
?>