<?php
/**
 * Polls and polls archives.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/polls.class.php
 * @package   Polls
 * @overview  Polls and polls archives.
 */

class POLLS {

    /** @var array Current active poll */
    public  $active  = [];

    /** @var array Old active poll */
    public  $old     = [];

    /** @var string User cookie */
    private $_cookie = '';

    /** Class constructor */
    public function __construct() {
        $this->_cookie = CONFIG::getValue('main', 'cookie');
    }

    /**
     * Returns data of all active polls.
     *
     * @return array Active polls data
     */
    public function getActivePolls() {
        $this->active = json_decode(file_get_contents(CONTENT.'polls'), TRUE);
        return $this->getPolls($this->active);
    }

    /**
     * Returns data of all archived polls.
     *
     * @return array Data of archived polls
     */
    public function getArchivedPolls() {
        $this->old = json_decode(file_get_contents(CONTENT.'polls-archive'), TRUE);
        return $this->getPolls($this->old);
    }

    /**
     * Counts voices for each answer in poll.
     *
     * @param  array $polls Polls data
     * @return array Data of archived polls
     */
    private function getPolls($polls) {
        $result = [];
        if (!empty($polls)) {
            foreach ($polls as $id => $poll) {
                $poll['total'] = array_sum($poll['count']);
                if ($poll['total'] !== 0) {
                    foreach ($poll['count'] as $i => $count) {
                        $poll['voices'][$i] = round(($count / $poll['total']) * 100);
                    }
                } else {
                    foreach ($poll['count'] as $i => $count) {
                        $poll['voices'][$i] = 0;
                    }
                }
                $result[$id] = $poll;
            }
        }
        return $result;
    }

    /**
     * Starts active poll.
     * Assigns a random number as poll ID.
     * This ID will be used in a user cookie after voiting.
     *
     * @param  string  $id Poll ID
     * @return boolean     The result of operation
     */
    public function startPoll($question, $answers) {
        if (empty($question) || empty($answers)) {
            throw new Exception('Empty question or no variants');
        }
        $data = [];
        $data['question'] = $question;
        foreach (explode(LF, preg_replace("/[\n\r]+/", LF, $answers)) as $variant) {
            if (!empty($variant)) {
                $data['answers'][] = $variant;
                $data['count'][]   = 0;
            }
        }
        $data['ips'] = [];
        $this->active[RandomString(8)] = $data;
        return $this->savePolls(TRUE, FALSE);
    }

    /**
     * Stops active poll.
     *
     * @param  string  $id Poll ID
     * @return boolean     The result of operation
     */
    public function stopPoll($id) {
        $this->old[] = $this->active[$id];
        unset($this->active[$id]);
        return $this->savePolls(TRUE, TRUE);
    }

    /**
     * Removes active poll.
     *
     * @param  string  $id Poll ID
     * @return boolean     The result of operation
     */
    public function removePoll($id) {
        $new = [];
        foreach ($this->active as $key => $value) {
            if ($key != $id) {
                $new[$key] = $value;
            }
        }
        $this->active = $new;
        return $this->savePolls(TRUE, FALSE);
    }

    /**
     * Removes poll from the archive.
     *
     * @param  string  $id Poll ID
     * @return boolean     The result of operation
     */
    public function removePollFromArchive($id) {
        $new = [];
        foreach ($this->old as $key => $value) {
            if ($key != $id) {
                $new[$key] = $value;
            }
        }
        $this->old = $new;
        return $this->savePolls(FALSE, TRUE);
    }

    /**
     * Voiting.
     *
     * @param  string  $poll   Poll ID
     * @param  integer $answer Poll ID
     * @throws Exception "Invalid ID"
     * @throws Exception "This answer does not exists in this poll"
     * @throws Exception "You already voted in this poll"
     * @return boolean The result of operation
     */
    public function voteInPoll($poll, $answer) {
        if (empty($this->active[$poll])) {
            throw new Exception('Invalid ID');
        }
        if (!isset($this->active[$poll]['answers'][$answer])) {
            throw new Exception('This answer does not exists in this poll');
        }
        $user = USER::$logged_in ? USER::getUser('user') : $_SERVER['REMOTE_ADDR'];
        if ($this->isVotedInPoll($poll)) {
            throw new Exception('You already voted in this poll');
        }
        $this->active[$poll]['count'][$answer]++;
        $this->active[$poll]['ips'][] = $user;
        setcookie($this->_cookie.'_poll['.$poll.']', $poll, time() + 3600 * 24 * 365 * 5);
        return $this->savePolls(TRUE, FALSE);
    }

    /**
     * Checks already voted user.
     *
     * @param  string  $poll Poll ID
     * @return boolean       The result of operation
     */
    public function isVotedInPoll($poll) {
        $user = USER::$logged_in ? USER::getUser('user') : $_SERVER['REMOTE_ADDR'];
        if (in_array($user, $this->active[$poll]['ips'])) {
            return TRUE;
        }
        if (!empty($_COOKIE[$this->_cookie.'_poll']) && is_array($_COOKIE[$this->_cookie.'_poll']) && in_array($poll, $_COOKIE[$this->_cookie.'_poll'])) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Shows active polls.
     *
     * @param  array $polls Polls data
     * @return array        Formatted polls data
     */
    public function showPolls($polls) {
        $colors = ['red', 'yellow', 'blue', 'green', 'purple', 'aqua', 'gray', 'teal', 'white', 'black'];
        $result = [];
        $output = [];
        foreach ($polls as $id => $poll) {
            $result = $poll;
            $result['id'] = $id;
            if (!empty($this->active[$id])) {
                $result['voited'] = $this->isVotedInPoll($id);
            }
            $result['answers'] = [];
            foreach ($poll['answers'] as $i => $answer) {
                $result['answers'][$i]['id']     = $i;
                $result['answers'][$i]['answer'] = $answer;
                $result['answers'][$i]['count']  = $poll['count'][$i];
                $result['answers'][$i]['voices'] = $poll['voices'][$i];
                $result['answers'][$i]['color']  = ($poll['voices'][$i] === 0) ? 'transparent' : $colors[$i];
            }
            $output[] = $result;
        }
        return $output;
    }

    /**
     * Saves poll.
     *
     * @param  boolean $active Active poll
     * @param  boolean $old    Old poll
     * @return boolean         The result of operation
     */
    private function savePolls($active = TRUE, $old = TRUE) {
        if ($active) $a = file_put_contents(CONTENT.'polls', json_encode($this->active, JSON_UNESCAPED_UNICODE), LOCK_EX);
        if ($old)    $o = file_put_contents(CONTENT.'polls-archive', json_encode($this->old, JSON_UNESCAPED_UNICODE), LOCK_EX);

        if ($active && $old) return $a && $o;
        elseif ($old)        return $o;
        elseif ($active)     return $a;
        else return TRUE;
    }
}
