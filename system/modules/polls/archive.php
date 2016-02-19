<?php
# idxCMS Flat Files Content Management System v3.2
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Module POLLS: Polls archive

if (!defined('idxCMS')) die();

$POLLS    = new POLLS();
$archived = array_reverse($POLLS->getArchivedPolls());

SYSTEM::set('pagename', __('Polls archive'));

if (!empty($archived)) {
    $TEMPLATE = new TEMPLATE(__DIR__.DS.'archive.tpl');
    $TEMPLATE->set($POLLS->showPolls($archived, $TEMPLATE));
    SYSTEM::defineWindow('Poll', $TEMPLATE->parse());

} else SYSTEM::showMessage('Database is empty', MODULE.'index');

unset($POLLS);
