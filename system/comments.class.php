<?php
/**
 * Processing comments.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      system/comments.class.php
 * @package   Core
 */

class COMMENTS extends ITEMS {

    /** Class initialization */
    public function __construct() {}

    /**
     * Gets comments.
     *
     * @param  integer $item Item ID
     * @return array         All comments to current article or topic
     */
    public function getComments($item) {
        $this->item = $item;
        if (!empty($this->comments)) {
            return $this->comments;
        }

        $this->comments = $this->getIndex($this->sections[$this->section]['categories'][$this->category]['path'].$item.DS);
        return $this->comments;
    }

    /**
     * Gets comment.
     *
     * @param  string $id    Comment ID
     * @param  string $page  Page number
     * @return array|boolean Comment data or FALSE
     */
    public function getComment($id, $page) {
        if (empty($this->comments[$id])) {
            return FALSE;
        }

        $comment = $this->comments[$id];
        $comment['text']   = CMS::call('PARSER')->parseText($comment['text'], $this->sections[$this->section]['categories'][$this->category]['path'].$this->item.DS);
        $comment['date']   = FormatTime('d F Y H:i:s', $comment['time']);
        $comment['avatar'] = GetAvatar($comment['author']);
        $author = USER::getUserData($comment['author']);
        $comment['status']  = __($author['status']);
        $comment['stars']   = $author['stars'];
        $comment['country'] = $author['country'];
        $comment['city']    = $author['city'];

        $user = USER::getUser('user');
        #
        # Do not show user IP for admin and comment author
        #
        if (($author['rights'] === '*') || ($user === $comment['author'])) {
            unset($comment['ip']);
        }
        if ($this->content[$this->item]['opened']) {
            if (($user !== 'guest') && ($user !== $comment['author'])) {
                $comment['opened'] = TRUE;
                if (CONFIG::getValue('enabled', 'rate') && $user !== 'guest') {
                    $comment['rateid'] = $this->module.'.'.$this->section.'.'.$this->category.'.'.$this->item.'.'.$id;
                }
            }
            if (USER::$root || (($author['rights'] !== '*') && USER::moderator($this->module, $this->comments[$id]))) {
                $comment['moderator'] = TRUE;
                $comment['link'] = $this->sections[$this->section]['categories'][$this->category]['link'].ITEM.$this->item;
                if (!empty($comment['ip'])) {
                    if ($page < 2)
                         $comment['ban'] = $comment['link'];
                    else $comment['ban'] = $comment['link'].PAGE.$page;
                }
            }
        }

        if     ($comment['rate'] < 0)   $comment['rate_color'] = 'red';
        elseif ($comment['rate'] === 0) $comment['rate_color'] = 'black';
        else                            $comment['rate_color'] = 'green';

        return $comment;
    }

    /**
     * Gets last comment or reply.
     *
     * @return integer ID of the last comment or reply
     */
    public function getLastComment() {
        $last = array_pop($this->comments);
        array_push($this->comments, $last);
        return $last;
    }

    /**
     * Shows comments.
     *
     * @param integer $item    ID of the item
     * @param integer $page    Number of page
     * @param integer $perpage Elements on the page
     * @param stringe $path    Path to the template of the page
     */
    public function showComments($item, $page, $perpage, $path) {
        $comments = $this->getComments($item['id']);
        if (!empty($comments)) {
            $TPL = new TEMPLATE($path.'comment.tpl');
            $count  = sizeof($comments);
            $ids    = array_keys($comments);
            $output = '';
            $pagination = GetPagination($page, $perpage, $count);
            for ($i = $pagination['start']; $i < $pagination['last']; $i++) {
                $TPL->set($this->getComment($ids[$i], $page));
                $output .= $TPL->parse();
            }

            SYSTEM::defineWindow('Comments', $output);
            if ($count > $perpage) {
                SYSTEM::defineWindow('', Pagination($count, $perpage, $page, $item['link']));
            }
        }
        if (USER::$logged_in) {
            if (!empty($item['opened'])) {
                #
                # Form to post comment
                #
                $this->showCommentForm($item['link']);
            }
        }
    }

    /**
     * Form to post comment.
     *
     * @param  obj    $obj  Current object
     * @param  array  $item Item to comment
     * @return string Form to post comment
     */
    public function showCommentForm($action = '', $for ='') {
        preg_match("#\module=(.*?)&#is", $action, $obj);
        $TPL = new TEMPLATE(TEMPLATES.'comment-post.tpl');
        $TPL->set('action',         $action);
        $TPL->set('nick',           USER::getUser('nick'));
        $TPL->set('admin',          USER::$root);
        $TPL->set('text',           FILTER::get('REQUEST', 'text'));
        $TPL->set('bbcodes',        CMS::call('PARSER')->showBbcodesPanel('comment.text'));
        $TPL->set('message_length', USER::$root ? NULL : CONFIG::getValue($obj[1], 'message_length'));
        $TPL->set('for',            $for);
        SYSTEM::defineWindow('Comment', $TPL->parse());
    }

    /**
     * Saves new comment or reply.
     *
     * @param  integer   $item                 ID of the article or reply
     * @param  string    $text                 Comment text
     * @throws Exception "Invalid ID"          - Invalid ID of the article or topic
     * @throws Exception "Text is empty"       - An attempt to write an empty article or topic
     * @throws Exception "Cannot save comment" - File system error or user have no rights to post
     * @return array                           List of comments related to article or topic
     */
    public function newComment($item, $text) {
        if (empty($this->content[$item])) {
            throw new Exception('Invalid ID');
        }
        $text = trim($text);
        if (empty($text)) {
            throw new Exception('Text is empty');
        }
        $path = $this->sections[$this->section]['categories'][$this->category]['path'];
        $id = $this->newId($this->comments);

        $this->comments[$id]['id']     = $id;
        $this->comments[$id]['author'] = USER::getUser('user');
        $this->comments[$id]['nick']   = USER::getUser('nick');
        $this->comments[$id]['time']   = time();
        $this->comments[$id]['text']   = $text;
        $this->comments[$id]['ip']     = $_SERVER['REMOTE_ADDR'];
        $this->comments[$id]['rate']   = 0;
        $this->saveIndex($path.$item.DS, $this->comments);
        $this->content[$item]['comments']++;

        if (!$this->saveIndex($path, $this->content)) {
            throw new Exception('Cannot save comment');
        }
        return $this->content[$item]['comments'];
    }

    /**
     * Saves comment.
     *
     * @param  string $id   Comment ID
     * @param  array  $item Comment item ID
     * @throws Exception "You are not logged in!"
     * @throws Exception "Invalid ID"
     * @throws Exception "Comments are not allowed"
     * @throws Exception "Text is empty"
     * @throws Exception "Cannot save comment"
     * @return array        Comment data
     */
    public function saveComment($id, $item) {
        if (!USER::$logged_in)                      throw new Exception('You are not logged in!');
        if (empty($this->content[$item]))           throw new Exception('Invalid ID');
        if (empty($this->content[$item]['opened'])) throw new Exception('Comments are not allowed');

        $text = FILTER::get('REQUEST', 'text');
        if (empty($text)) {
            throw new Exception('Text is empty');
        }
        if (empty($id)) {
            $this->newComment($item, $text);
        } else {
            if (empty($this->comments[$id])) {
                throw new Exception('Invalid ID');
            }

            $this->comments[$id]['text'] = $text;
            if (!$this->saveIndex($this->sections[$this->section]['categories'][$this->category]['path'].$item.DS, $this->comments)) {
                throw new Exception('Cannot save comment');
            }

            USER::changeProfileField(USER::getUser('user'), 'comments', '+');
        }
        FILTER::remove('REQUEST', 'text');
        return $this->content[$item]['comments'];
    }

    /**
     * Removes comment.
     *
     * @param  integer $id Comment ID
     * @throws Exception "Cannot remove comment"
     * @return array       Comments for current post
     */
    public function removeComment($id) {
        if (empty($this->comments[$id])) throw new Exception('Invalid ID');
        if (!USER::moderator($this->module, $this->comments[$id])) {
            throw new Exception('Cannot remove comment');
        }
        unset($this->comments[$id]);

        $path = $this->sections[$this->section]['categories'][$this->category]['path'];
        if (!empty($this->comments)) {
            $this->content[$this->item]['comments']--;
            if (!$this->saveIndex($path.$this->item.DS, $this->comments)) {
                throw new Exception('Cannot remove comment');
            }

        } else {
            $this->content[$this->item]['comments'] = 0;
            unlink($path.$this->item.DS.$this->index);
        }
        if (!$this->saveIndex($path, $this->content)) {
            throw new Exception('Cannot remove comment');
        }
        return $this->content[$this->item]['comments'];
    }
}
