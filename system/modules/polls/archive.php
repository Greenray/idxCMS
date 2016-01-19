<?php
# idxCMS Flat Files Content Management System v3.0
# Copyright (c) 2016 Victor Nabatov
# Module POLLS: Polls archive

if (!defined('idxCMS')) die();

$POLLS    = new POLLS();
$archived = array_reverse($POLLS->getArchivedPolls());

SYSTEM::set('pagename', __('Polls archive'));

if (!empty($archived)) {
    $TPL = new TEMPLATE(__DIR__.DS.'archive.tpl');
    $TPL->set($POLLS->showPolls($archived, $TPL));
    SYSTEM::defineWindow('Poll', $TPL->parse());

} else echo SYSTEM::showMessage('Database is empty', MODULE.'index');

unset($POLLS);
