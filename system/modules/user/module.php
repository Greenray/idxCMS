<?php
# idxCMS version 2.2
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# MODULE USERS - INITIALIZATION

if (!defined('idxCMS')) die();

define('PM_DATA', CONTENT.'pm'.DS);
define('AVATARS', CONTENT.'avatars'.DS);

# MESSAGE class
class MESSAGE extends INDEX {

    private $path     = '';         #Path to datafile
    private $messages = array();
    private $config   = array();

    public function __construct($path, $file) {
        $this->path = $path;
        $this->setIndex($file);
        if ($path === CONTENT)
             $this->config = CONFIG::getSection($file);
        else $this->config = CONFIG::getSection('pm');
        $this->messages = GetUnserialized($path.$file);
    }

    public function getMessages($from = '') {
        if (empty($from))
            return $this->messages;
        return $this->messages[$from];
    }

    public function checkNewMessages() {
        $new  = 0;
        $hint = __('No new messages');
        if (!empty($this->messages['inbox'])) {
            foreach ($this->messages['inbox'] as $id => $msg) {
                if (!empty($msg['new'])) {
                    ++$new;
                    $hint = __('Last new message from').' '.$msg['author'].' ('.$msg['nick'].') - '.FormatTime('d.m.Y H:i:s', $msg['time']);
                }
            }
        }
        return array($new, $hint);
    }

    private function checkText($text) {
        $text = trim($text);
        $text = (mb_strlen($text, 'UTF-8') > $this->config['message-length']) ? mb_substr($text, 0, $this->config['message-length'], 'UTF-8') : $text;
        if (empty($text))
            throw new Exception('Text is empty');

        return $text;
    }

    public function sendMessage($text) {
        $text = self::checkText($text);
        $message = array();
        $message['author'] = USER::getUser('username');
        $message['nick']   = USER::getUser('nickname');
        $message['text'] = $text;
        $message['time'] = time();
        $message['ip']   = CMS::call('USER')->checkRoot() ? '' : $_SERVER['REMOTE_ADDR'];
        if (empty($this->messages))
             $this->messages[1] = $message;
        else $this->messages[]  = $message;
        # Correct database size
        $this->messages = array_slice($this->messages, -$this->config['db-size'] + 1, $this->config['db-size'], TRUE);
        return $this->saveIndex($this->path, $this->messages);
    }

    public function sendPrivateMessage($for) {
        $text = self::checkText(FILTER::get('REQUEST', 'text'));
        if (!file_exists(USERS.$for))
            return FALSE;

        $message = array();
        $message['author'] = USER::getUser('username');
        if ($for === $message['author'])
            throw new Exception('Cannot send message');

        $message['nick'] = USER::getUser('nickname');
        if (empty($email)) {
            $message['mail'] = '';
        } else {
            if (!CMS::call('FILTER')->validEmail($email))
                 throw new Exception('Invalid email');
            else $message['mail'] = $email;
        }
        $message['text'] = $text;
        $message['time'] = time();
        $message['ip']   = $_SERVER['REMOTE_ADDR'];
        $message['new']  = TRUE;

        $data = GetUnserialized(PM_DATA.$for);
        if (empty($data['inbox'])) {
            $data['inbox'][1] = $message;
        } else {
            $data['inbox'][] = $message;
            $data['inbox'] = array_slice($data['inbox'], -$this->config['db-size'] + 1, $this->config['db-size'], TRUE);    # Correct database size
        }
        if (!file_put_contents(PM_DATA.$for, serialize($data), LOCK_EX))
            throw new Exception('Cannot send message');

        # Save message in Outbox
        unset($message['new']);
        if (empty($this->messages['outbox']))
             $this->messages['outbox'][1] = $message;
        else $this->messages['outbox'][]  = $message;
        # Correct database size
        $this->messages['outbox'] = array_slice($this->messages['outbox'], -$this->config['db-size'] + 1, $this->config['db-size'], TRUE);
        return $this->saveIndex($this->path, $this->messages);
    }

    public function sendFeedback($text, $email = '') {
        $text = self::checkText($text);
        $message = array();
        $message['author'] = USER::getUser('username');
        $message['nick']   = USER::getUser('nickname');
        if (empty($email)) {
            $message['mail'] = '';
        } else {
            if (!CMS::call('FILTER')->validEmail($email))
                 throw new Exception('Invalid email');
            else $message['mail'] = $email;
        }
        $message['text'] = $text;
        $message['time'] = time();
        $message['ip']   = $_SERVER['REMOTE_ADDR'];
        if (empty($this->messages))
            $this->messages[1] = $message;
        else $this->messages[]  = $message;
        # Correct database size
        $this->messages = array_slice($this->messages, -$this->config['db-size'] + 1, $this->config['db-size'], TRUE);
        return $this->saveIndex($this->path, $this->messages);
    }

    public function saveMessage($id, $text) {
        if (empty($this->messages[$id]))
            throw new Exception('Invalid ID');

        $text = self::checkText($text);
        $this->messages[$id]['text'] = $text;
        return $this->saveIndex($this->path, $this->messages);
    }

    public function removeMessage($id, $from = '') {
        if (empty($from)) {
            if (!empty($this->messages[$id])) {
                unset($this->messages[$id]);
            }
        } else {
            if (!empty($this->messages[$from][$id])) {
                unset($this->messages[$from][$id]);
            }
        }
        return $this->saveIndex($this->path, $this->messages);
    }

    public function setAllNoNew() {
        if (!empty($this->messages['inbox'])) {
            foreach ($this->messages['inbox'] as $id => $msg) {
                $this->messages['inbox'][$id]['new'] = FALSE;
            }
            return $this->saveIndex($this->path, $this->messages);
        }
    }
}

function CreateUserLink($user, $nick) {
    if ($user === 'guest') return __('Guest');
    return '<a href="'.MODULE.'user&amp;user='.$user.'">'.$nick.'</a>';
}

function GetAvatar($user) {
    if (file_exists(AVATARS.$user.'.png'))
         return AVATARS.$user.'.png';
    else return AVATARS.'noavatar.gif';
}

SYSTEM::registerModule('user', 'User', 'main', 'system');
SYSTEM::registerModule('user.panel', 'User panel', 'box', 'system');
SYSTEM::registerModule('user.pm', 'Private messages', 'main', 'system');
SYSTEM::registerModule('user.feedback', 'Feedback', 'main', 'system');
?>