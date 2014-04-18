<?php
# idxCMS version 2.2 - Flat Files Content Management System
# Copyright (c) 2012 Greenray greenray.spb@gmail.com

# This project is based on the idea and experience of work in the ReloadCMS project
# Copyright (c) 2004 ReloadCMS Development Team http://reloadcms.com
# Copyright (c) 2009 Greenray greenray.spb@gmail.com

# This CMS is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License.
# To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or
# send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.

# Это произведение распространяется по лицензии Creative Commons Attribution-NonCommercial-ShareAlike
# (Атрибуция — Некоммерческое использование — С сохранением условий) 3.0 Непортированная.
# Чтобы ознакомиться с экземпляром этой лицензии, посетите http://creativecommons.org/licenses/by-nc-sa/3.0/ или
# отправьте письмо на адрес Creative Commons: 171 Second Street, Suite 300, San Francisco, California, 94105, USA.

# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

ini_set('display_errors', 0);
mb_internal_encoding("UTF-8");
if (date_default_timezone_set(date_default_timezone_get()) === FALSE)
    date_default_timezone_set('UTC');

# Unset any globals created by register_globals being turned ON
while (list($global) = each($GLOBALS)) {
    if (!preg_match('/^(_POST|_GET|_COOKIE|_SERVER|_FILES|GLOBALS|HTTP.*)$/', $global)) {
        unset($$global);
    }
}
unset($global);

error_reporting(0);

function idxErrorHandler ($errno, $errmsg, $filename, $linenum, $vars) {
    $errortype = array (1 => "Error",
                        2 => "Warning",
                        4 => "Parsing Error",
                        8 => "Notice",
                       16 => "Core Error",
                       32 => "Core Warning",
                       64 => "Compile Error",
                      128 => "Compile Warning",
                      256 => "User Error",
                      512 => "User Warning",
                     1024 => "User Notice");
    $error = date("Y-m-d H:i:s (T)").' '.$errortype[$errno].': '.$errmsg.' in '.$filename.', line '.$linenum.PHP_EOL;
    file_put_contents('./content/logs/idxerror.log', $error, FILE_APPEND | LOCK_EX);
}
set_error_handler("idxErrorHandler", E_ALL | E_STRICT);

# Constants
define('idxCMS', TRUE);
define('DS', DIRECTORY_SEPARATOR);
define('LF', PHP_EOL);
define('ROOT', '.'.DS);
define('CONTENT', ROOT.'content'.DS);
define('SKINS', ROOT.'skins'.DS);
define('SYS', ROOT.'system'.DS);
define('TOOLS', ROOT.'tools'.DS);
define('MODULES', SYS.'modules'.DS);
define('TEMP', CONTENT.'temp'.DS);
define('USERS', CONTENT.'users'.DS);
define('IMAGES', SKINS.'images'.DS);
define('ICONS', IMAGES.'icons'.DS);
define('SMILES', IMAGES.'smiles'.DS);

define('MODULE',    ROOT.'?module=');
define('SECTION',  '&amp;section=');
define('CATEGORY', '&amp;category=');
define('ITEM',     '&amp;item=');
define('COMMENT',  '&amp;comment=');
define('PAGE',     '&amp;page=');

umask(000);     # UMASK Must be 000!

# Loading system libraries
include_once(SYS.'cms.class.php');
include_once(SYS.'filter.class.php');
include_once(SYS.'log.class.php');
include_once(SYS.'config.class.php');
include_once(SYS.'system.class.php');
include_once(SYS.'user.class.php');
include_once(SYS.'functions.php');
include_once(SYS.'index.class.php');
include_once(SYS.'content.class.php');
include_once(SYS.'parser.class.php');
include_once(SYS.'template.class.php');
include_once(SYS.'uploads.class.php');

session_start();

CMS::call('FILTER')->sanitaze();
$REQUEST = FILTER::getAll('REQUEST');

global $LANG;
$CMS = CMS::call('SYSTEM');

define('IDX_VERSION',   '2.2');
define('IDX_COPYRIGHT', '&copy; 2012 '.__('Greenray'));
define('IDX_POWERED',   'Powered by idxCMS - '.IDX_VERSION);

# Send main headers
header('Last-Modified: '.gmdate('r'));
header('Content-Type: text/html; charset=UTF-8');
header('Content-Language: '.SYSTEM::get('locale'));
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

CMS::call('USER')->initUser();
CMS::call('SYSTEM')->initModules();

if (!empty($REQUEST['login'])) {
    CMS::call('USER')->logInUser();
}
if (!empty($REQUEST['logout'])) {
    CMS::call('USER')->logOutUser();
    session_destroy();
    Redirect('index');
}

# If requested page is not set or is not exists, the site index page will be shown.
$MODULE = empty($REQUEST['module']) ? 'index' : basename($REQUEST['module']);
switch($MODULE) {
    case 'editor':
        include(TOOLS.'editor.php');
        break;
    case 'rate':
        if (CONFIG::getValue('enabled', 'rate')) {
            include(MODULES.'rate'.DS.'rate.php');
        }
        break;
    case 'aphorisms':
        include(MODULES.'aphorisms'.DS.'aphorisms.php');
        break;
    case 'rss':
        include(MODULES.'rss'.DS.'rss.php');
        break;
    case 'admin':
        define('ADMIN', ROOT.'admin'.DS);
        define('ADMINLIBS', ADMIN.'libs'.DS);
        define('TEMPLATES', ADMIN.'templates'.DS);
        require_once(ADMINLIBS.'functions.php');
        include_once(ADMIN.'languages'.DS.SYSTEM::get('language').'.php');
        if (CMS::call('USER')->checkRoot()) {
            $modules = AdvScanDir(ADMIN.'modules', '', 'dir');
            $MODULES = array();
            define('idxADMIN', TRUE);
            foreach ($modules as $module) {
                if ((substr($module, 0, 1) === '_') || CONFIG::getValue('enabled', $module)) {
                    include_once(ADMIN.'modules'.DS.$module.DS.'module.php');     # Initialize module
                }
            }
            $id = empty($REQUEST['id']) ? '' : basename($REQUEST['id']);
            switch ($id) {
                case 'header':
                    include(ADMIN.'header.php');
                    break;
                case 'main':
                    include(ADMIN.'frameset.php');      # Open administration panel or show frames error
                    break;
                case 'nav':
                    require(ADMIN.'navigation.php');
                    break;
                case 'footer':
                    include(ADMIN.'footer.php');
                    break;
                default:
                    if (empty($REQUEST['id'])) {
                        $id = 'index';
                        require(ADMIN.'module.php');    # Activate module
                    } else {
                        list($module, $action) = explode('.', $id);
                        $id = strtr($id, '.', DS);
                        require(ADMIN.'module.php');    # Activate module
                    }
                    break;
            }
        } else {
            $message  = __('Access denied');
            $message .= LoginForm();
            include(ADMIN.'error.php');
        }
        break;
    default:
        include_once(SYS.'statistic.php');
        define('TEMPLATES', SYS.'templates'.DS);
        # Loading main module
        CMS::call('SYSTEM')->setCurrentPoint('__MAIN__');
        require_once(CURRENT_SKIN.'skin.php');       # Current skin definition
        $mod = explode('.', $MODULE, 2);
        if (empty(SYSTEM::$modules[$MODULE]) || !file_exists(MODULES.$mod[0].DS.end($mod).'.php')) {
            include_once(MODULES.'index'.DS.'index.php');
        } else {
        	include_once(MODULES.$mod[0].DS.end($mod).'.php');       # Get active module
        }
        $_SESSION['request'] = $MODULE;
        # Load other modules
        $modules = array();
        if (in_array($MODULE, array_keys(CONFIG::getSection('output')))) {
            $modules[$MODULE] = CONFIG::getValue('output', $MODULE);
        }
        $modules['left']  = CONFIG::getValue('output', 'left');
        $modules['right'] = CONFIG::getValue('output', 'right');
        $modules['boxes'] = CONFIG::getValue('output', 'boxes');
        if (!empty($modules[$MODULE])) unset($modules['left']);
        foreach ($modules as $point => $boxes) {
            CMS::call('SYSTEM')->setCurrentPoint($point);
            if (!empty($boxes)) {
                foreach ($boxes as $active) {
                    if (CONFIG::getValue('enabled', $active)) {
                        $mod = explode('.', $active, 2);
                        if (!empty(SYSTEM::$modules[$active])) {
                            include(MODULES.$mod[0].DS.end($mod).'.php');
                        }
                    }
                }
            }
        }
        $output = array();
        if ($MODULE === 'index') {
            $output['index'] = TRUE;
            $output['tabs']  = $_SESSION['tabs'];
        }
        $output['locale'] = SYSTEM::get('locale');
        $output['slogan'] = CONFIG::getValue('main', 'slogan');
        $output['module'] = $MODULE;

        $TPL = new TEMPLATE('main.tpl');
        echo $TPL->parse($output);
        break;
}
?>