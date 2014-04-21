<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com
# MODULE POLLS - ARCHIVE

if (!defined('idxCMS')) die();

$POLLS = new POLLS();
$archived = array_reverse($POLLS->getArchivedPolls());

SYSTEM::set('pagename', __('Polls archive'));

if (!empty($archived)) {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'archive.tpl');
    ShowWindow(__('Poll'), $POLLS->showPolls($archived, $TPL));
} else {
    ShowWindow(__('Polls archive'), __('Database is empty'), 'center');
}
unset($POLLS);
?>