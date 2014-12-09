<?php
/**
 * Aphorisms database is a simple text file.
 * One string is one aphorism.
 * Aphorisms are displayed randomly.
 * Each locale has its file named as "locale".txt
 *
 * @package    idxCMS
 * @subpackage MODULES
 * @file       system/modules/aphorisms/aphorisms.php
 * @version    2.3
 * @author     Victor Nabatov <greenray.spb@gmail.com>\n
 * @license    Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *             http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @copyright  (c) 2011 - 2014 Victor Nabatov\n
 * @see        https://github.com/Greenray/idxCMS/system/modules/aphorisms/aphorisms.php
 */

/** Module APHORISMS */
if (!defined('idxCMS')) die();

$aph = file(APHORISMS.SYSTEM::get('locale').'.txt'); # Get file with aphorisms according to user`s locale

 # Show random string
if (!empty(FILTER::get('REQUEST', 'flip'))) {
    echo $aph[array_rand($aph, 1)].'$'; # Processing of command "flip"
} else {
    $TPL = new TEMPLATE(dirname(__FILE__).DS.'aphorisms.tpl');
    ShowWindow(__('Aphorisms'), $TPL->parse(array('text' => $aph[array_rand($aph, 1)])));
}
