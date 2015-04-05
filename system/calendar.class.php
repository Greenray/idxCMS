<?php
# idxCMS Flat Files Content Management Sysytem

/** Calendar of news and publications.
 * This calendar gives a possibility to search of news and posts by date.
 *
 * @file      system/calendar.class.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Posts
 */

class CALENDAR {

    /** Current date.
     * @var array
     */
    private $today = [];

    /** Events for specific date.
     * @var array
     */
    private $events = [];

    /** Auxiliary variable.
     * @var array
     */
    private $temp = [];

    /** Class initialization.
     * @param  integer $month    Month
     * @param  integer $year     Year
     * @param  integer $datetime Time
     * @return void
     */
    function __construct($month, $year, $datetime) {
        $this->temp['first_day']      = mktime(0, 0, 0, $month, 1, $year);
        $this->temp['first_day_week'] = date('w', $this->temp['first_day']);
        $this->temp['num_of_days']    = date('t', $this->temp['first_day']);
        $this->datetime = $datetime;
    }

    /** Assigns event to calendar.
     * @param  integer $day  Date
     * @param  string  $link Link to existing post
     * @return void
     */
    function event($day, $link) {
        $this->events[intval($day)] = $link;
    }

    /** Highlights current date.
     * @param  integer $day   Date
     * @param  string  $style Style for highlighting
     * @return void
     */
    function highlight($day, $style = '!') {
        $this->today[intval($day)] = $style;
    }

    /** Creates calendar.
     * @param  integer $current_year   Current year
     * @param  integer $selected_year  Selected year
     * @param  integer $selected_month Selected month
     * @return array                   Data for calendar
     */
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
        $calendar = [];
        $string   = 1;
        $showed   = 1;
        while ($showed <= $this->temp['num_of_days']) {
            if ($position > 1) {
                $calendar[$string]['span'] = $position - 1;
            }
            $inc = 0;
            for ($i = $showed; $i < ($showed + 7) && ($i <= $this->temp['num_of_days']) && ($position <= 7); $i++) {
                $class = 'event';
                if (empty($this->events[$i]))
                     $class = 'usual';
                else $calendar[$string]['dates'][$i]['events'] = $this->events[$i];

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
