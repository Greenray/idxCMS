<?php
/**
 * Private messages.
 * Module registration and internal functions.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   4.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/message.class.php
 * @package   User
 */

class MESSAGE extends DBASE {

    /** @var array Messages configuration */
    private $config = [];

    /** @var array Messsages */
    private $messages = [];

    /** @var string Path to datafile */
    private $path = '';

    /**
     * Class initialization.
     *
     * @param string $path Path to messages file
     * @param string $file Name of messges file
     */
    public function __construct($path, $file) {
        parent::__construct();
        $this->path = $path;
        parent::setIndex($file);
        if ($this->path === CONTENT)
             $this->config = CONFIG::getSection($file);
        else $this->config = CONFIG::getSection('pm');
        $this->messages = parent::getIndex($path);
    }

    /**
     * Checks for new messages.
     *
     * @return array Number of new messages and info about last new message
     */
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
        return [$new, $hint];
    }

    /**
     * Checks that the user does not send an empty message.
     *
     * @param  string    $text Message text
     * @throws Exception "Text is empty"
     * @return string    $text Message text with allowed length
     */
    private function checkText($text) {
        $text = trim($text);
        $text = (mb_strlen($text, 'UTF-8') > $this->config['message_length']) ? mb_substr($text, 0, $this->config['message_length'], 'UTF-8') : $text;
        if (empty($text)) {
            throw new Exception('Text is empty');
        }
        return $text;
    }

    /**
     * Gets messages.
     *
     * @param  string $from Sender
     * @return array Messages
     */
    public function getMessages($from = '') {
        return (empty($from)) ? $this->messages : $this->messages[$from];
    }

    /**
     * Removes message.
     *
     * @param  integer $id   ID of the message
     * @param  string  $from Sender
     * @return boolean The result of operation
     */
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

    /**
     * Saves message.
     *
     * @param  integer   $id   Message ID
     * @param  string    $text Message text
     * @throws Exception "Invalid ID"
     * @return boolean The result of operation
     */
    public function saveMessage($id, $text) {
        if (empty($this->messages[$id])) {
            throw new Exception('Invalid ID');
        }
        $text = self::checkText($text);
        $this->messages[$id]['text'] = $text;
        return $this->saveIndex($this->path, $this->messages);
    }

    /**
     * Sends message to administrator.
     *
     * @param  string    $text  Message text
     * @param  string    $email User's email address
     * @throws Exception "Invalid email"
     * @return type
     */
    public function sendFeedback($text, $email = '') {
        $text = self::checkText($text);
        $message = [];
        $message['author'] = USER::getUser('user');
        $message['nick']   = USER::getUser('nick');
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

        # Correct database size to allowed value
        $this->messages = array_slice($this->messages, -$this->config['db_size'] + 1, $this->config['db_size'], TRUE);
        return $this->saveIndex($this->path, $this->messages);
    }

    /**
     * Sends message.
     *
     * @param  string $text Message text
     * @return boolean The result of operation
     */
    public function sendMessage($text) {
        $text    = self::checkText($text);
        $message = [];
        $message['author'] = USER::getUser('user');
        $message['nick']   = USER::getUser('nick');
        $message['text']   = $text;
        $message['time']   = time();
        $message['ip']     = USER::$root ? '' : $_SERVER['REMOTE_ADDR'];
        if (empty($this->messages))
             $this->messages[1] = $message;
        else $this->messages[]  = $message;
        #
        # Correct database size
        #
        $this->messages = array_slice($this->messages, -$this->config['db_size'] + 1, $this->config['db_size'], TRUE);

        return $this->saveIndex($this->path, $this->messages);
    }

    /**
     * Sends private message.
     *
     * @param  string $for  Addressee
     * @param  string $text Message
     * @throws Exception "Invalid user"
     * @throws Exception "Invalid email"
     * @throws Exception "Cannot send message"
     * @return boolean The result of operation
     */
    public function sendPrivateMessage($for, $text) {
        $text = self::checkText($text);
        if (!file_exists(USERS.$for)) {
            throw new Exception('Invalid user');
        }
        $message = [];
        $message['author'] = USER::getUser('user');
        if ($for === $message['author']) {
            throw new Exception('Cannot send message');
        }
        $message['nick'] = USER::getUser('nick');
        if (empty($email)) {
            $message['mail'] = '';
        } else {
            if (!CMS::call('FILTER')->validEmail($email)) {
                throw new Exception('Invalid email');
            } else {
                $message['mail'] = $email;
            }
        }
        $message['text'] = $text;
        $message['time'] = time();
        $message['ip']   = $_SERVER['REMOTE_ADDR'];
        $message['new']  = TRUE;

        $data = json_decode(file_get_contents(PM_DATA.$for) , TRUE);
        if (empty($data['inbox'])) {
            $data['inbox'][1] = $message;
        } else {
            $data['inbox'][] = $message;
            #
            # Correct database size
            #
            $data['inbox'] = array_slice($data['inbox'], -$this->config['db_size'] + 1, $this->config['db_size'], TRUE);
        }
        if (!file_put_contents(PM_DATA.$for, json_encode($data, JSON_UNESCAPED_UNICODE), LOCK_EX)) {
            throw new Exception('Cannot send message');
        }
        #
        # Save message in Outbox
        #
        unset($message['new']);
        $message['to'] = $for;
        empty($this->messages['outbox']) ? $this->messages['outbox'][1] = $message : $this->messages['outbox'][] = $message;
        #
        # Correct database size
        #
        $this->messages['outbox'] = array_slice($this->messages['outbox'], -$this->config['db_size'] + 1, $this->config['db_size'], TRUE);
        return $this->saveIndex($this->path, $this->messages);
    }

    /**
     * Marks all messages as read.
     *
     * @return boolean The result of operation
     */
    public function setAllNoNew() {
        if (!empty($this->messages['inbox'])) {
            foreach ($this->messages['inbox'] as $id => $msg) {
                $this->messages['inbox'][$id]['new'] = FALSE;
            }
            return $this->saveIndex($this->path, $this->messages);
        }
    }
}
