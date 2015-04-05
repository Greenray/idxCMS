<?php
# idxCMS Flat Files Content Management Sysytem
# Module Polls
# Version   2.4
# Copyright (c) 2011 - 2015 Victor Nabatov

if (!defined('idxCMS')) die();

$POLLS    = new POLLS();
$archived = array_reverse($POLLS->getArchivedPolls());

SYSTEM::set('pagename', __('Polls archive'));

if (!empty($archived)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'archive.tpl');
    ShowWindow(__('Poll'), $POLLS->showPolls($archived, $TPL));
} else {
    ShowWindow(__('Polls archive'), __('Database is empty'), 'center');
}
unset($POLLS);
