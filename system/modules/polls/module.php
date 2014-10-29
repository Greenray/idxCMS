<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE POLLS - INITIALIZATION

if (!defined('idxCMS')) die();

class POLLS {

    public  $active = array();       # Current poll
    public  $old    = array();       # Old poll
    private $cookie = '';

    public function __construct() {
        $this->cookie = CONFIG::getValue('main', 'cookie');
    }

    public function getActivePolls() {
        $this->active = GetUnserialized(CONTENT.'polls');
        return $this->getPolls($this->active);
    }

    public function getArchivedPolls() {
        $this->old = GetUnserialized(CONTENT.'polls-archive');
        return $this->getPolls($this->old);
    }

    private function getPolls($polls) {
        $result = array();
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

    public function startPoll($question, $answers) {
        if (empty($question) || empty($answers)) {
            throw new Exception('Empty question or no variants');
        }
        $data = array();
        $data['question'] = $question;
        foreach (explode(LF, preg_replace("/[\n\r]+/", LF, $answers)) as $variant) {
            if (!empty($variant)) {
                $data['answers'][] = $variant;
                $data['count'][] = 0;
            }
        }
        $data['ips'] = array();
        $this->active[RandomString(8)] = $data;
        return $this->savePolls(TRUE, FALSE);
    }

    public function stopPoll($id) {
        $this->old[] = $this->active[$id];
        unset($this->active[$id]);
        return $this->savePolls(TRUE, TRUE);
    }

    public function removePoll($id) {
        $new = array();
        foreach ($this->active as $key => $value) {
            if ($key != $id) {
                $new[$key] = $value;
            }
        }
        $this->active = $new;
        return $this->savePolls(TRUE, FALSE);
    }

    public function removePollFromArchive($id) {
        $new = array();
        foreach ($this->old as $key => $value) {
            if ($key != $id) {
                $new[$key] = $value;
            }
        }
        $this->old = $new;
        return $this->savePolls(FALSE, TRUE);
    }

    public function voteInPoll($poll, $answer) {
        if (empty($this->active[$poll])) {
            throw new Exception('Invalid ID');
        }
        if (!isset($this->active[$poll]['answers'][$answer])) {
            throw new Exception('This answer does not exists in this poll');
        }
        $user = USER::loggedIn() ? USER::getUser('username') : $_SERVER['REMOTE_ADDR'];
        if ($this->isVotedInPoll($poll)) {
            throw new Exception('You already voted in this poll');
        }
        $this->active[$poll]['count'][$answer]++;
        $this->active[$poll]['ips'][] = $user;
        setcookie($this->cookie.'_poll['.$poll.']', $poll, time() + 3600 * 24 * 365 * 5);
        return $this->savePolls(TRUE, FALSE);
    }

    public function isVotedInPoll($poll) {
        $user = USER::loggedIn() ? USER::getUser('username') : $_SERVER['REMOTE_ADDR'];
        if (in_array($user, $this->active[$poll]['ips'])) {
            return TRUE;
        }
        if (!empty($_COOKIE[$this->cookie.'_poll']) && is_array($_COOKIE[$this->cookie.'_poll']) && in_array($poll, $_COOKIE[$this->cookie.'_poll'])) {
            return TRUE;
        }
        return FALSE;
    }

    public function showPolls($polls, $tpl) {
        $colors = array('red', 'yellow', 'blue', 'green', 'purple', 'aqua', 'gray', 'teal', 'white', 'black');
        $result = array();
        $output = '';
        foreach ($polls as $id => $poll) {
            $result = $poll;
            $result['id'] = $id;
            if (!empty($this->active[$id])) {
                $result['voited'] = $this->isVotedInPoll($id);
            }
            $result['answers'] = array();
            foreach ($poll['answers'] as $i => $answer) {
                $result['answers'][$i]['id'] = $i;
                $result['answers'][$i]['answer'] = $answer;
                $result['answers'][$i]['count']  = $poll['count'][$i];
                $result['answers'][$i]['voices'] = $poll['voices'][$i];
                if ($poll['voices'][$i] == 0) {
                    $result['answers'][$i]['color'] = 'transparent';
                } else {
                    $result['answers'][$i]['color'] = $colors[$i];
                }
            }

            $output .= $tpl->parse($result);
        }
        return $output;
    }

    private function savePolls($active = TRUE, $old = TRUE) {
        if ($active) {
            $a = file_put_contents(CONTENT.'polls', serialize($this->active), LOCK_EX);
        }
        if ($old) {
            $b = file_put_contents(CONTENT.'polls-archive', serialize($this->old), LOCK_EX);
        }
        if ($active && $old) return $a && $b;
        elseif ($old)        return $b;
        elseif ($active)     return $a;
        else return TRUE;
    }

    public function getError() {
        return $this->error;
    }
}

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['Poll'] = 'Голосование';
        $LANG['def']['Polls'] = 'Голосования';
        $LANG['def']['Polls archive'] = 'Архив голосований';
        $LANG['def']['Total votes'] = 'Всего голосов';
        break;
    case 'ua':
        $LANG['def']['Poll'] = 'Голосування';
        $LANG['def']['Polls'] = 'Голосування';
        $LANG['def']['Polls archive'] = 'Архів голосувань';
        $LANG['def']['Total votes'] = 'Всього голосів';
        break;
    case 'by':
        $LANG['def']['Poll'] = 'Галасаванне';
        $LANG['def']['Polls'] = 'Галасаванні';
        $LANG['def']['Polls archive'] = 'Архіў галасаванняў';
        $LANG['def']['Total votes'] = 'Усяго галасоў';
        break;
}

SYSTEM::registerModule('polls.archive', 'Polls archive', 'main');
SYSTEM::registerModule('polls', 'Poll', 'box');
?>