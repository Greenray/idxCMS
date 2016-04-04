<?php
/**
 * The core of the content management system.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   4.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @package   Core
 * @overview  The core of the system.
 *
 *            This program is distributed in the hope that it will be useful,
 *            but WITHOUT ANY WARRANTY; without even the implied warranty of
 *            MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

ini_set('phar.readonly', 0);            # Allow phar to work with phars
ini_set('display_errors', 1);           # Allow to log php errors
ini_set('default_charset', 'UTF-8');    # PHP >= 5.6.0, empty for PHP < 5.6.0

mb_internal_encoding('UTF-8');

setlocale(LC_CTYPE, ['ru_RU.UTF-8', 'ru_UA.UTF-8', 'by_BY.UTF-8', 'en_US.UTF-8', 'en_GB.UTF-8']);
setlocale(LC_ALL,   ['ru_RU.UTF-8', 'ru_UA.UTF-8', 'by_BY.UTF-8', 'en_US.UTF-8', 'en_GB.UTF-8']);
#
# Set your preferred zone
#
date_default_timezone_set('Europe/Moscow');
#
# Unset any globals created by register_globals being turned ON
#
while (list($global) = each($GLOBALS)) {
    if (!preg_match('/^(_REQUEST|_COOKIE|_SERVER|_FILES|GLOBALS|HTTP.*)$/', $global)) {
        unset($global);
    }
}
unset($global);
#
# Main conststants
#
/** Prevent direct access to php files */
define('idxCMS', TRUE);
/** Alias for DIRECTORY_SEPARATOR */
define('DS', DIRECTORY_SEPARATOR);
/** Line feed */
define('LF', PHP_EOL);
/** System root directory */
define('ROOT', '.'.DS);
/** Path to CMS content */
define('CONTENT', ROOT.'content'.DS);
/** CMS skins */
define('SKINS',   ROOT.'skins'.DS);
/** Path to CMS core */
define('SYS',     ROOT.'system'.DS);
/** Path to CMS tools */
define('TOOLS',   ROOT.'tools'.DS);
/** Path to CMS modules */
define('MODULES', SYS.'modules'.DS);
/** Data storage for logs */
define('LOGS',    CONTENT.'logs'.DS);
/** Temporary directory */
define('TEMP',    CONTENT.'temp'.DS);
/** Path to users profiles */
define('USERS',   CONTENT.'users'.DS);
/** Path to common images */
define('IMAGES',  SKINS.'images'.DS);
/** Path to smiles */
define('SMILES',  IMAGES.'smiles'.DS);
/** Path to cache */
define('CACHE',   CONTENT.'temp'.DS);
#
# Elements of the query website page
#
/** Query of module */
define('MODULE',    ROOT.'?module=');
/** The part of query of section */
define('SECTION',  '&amp;section=');
/** The part of query of category */
define('CATEGORY', '&amp;category=');
/** The part of query of item */
define('ITEM',     '&amp;item=');
/** The part of query of comment */
define('COMMENT',  '&amp;comment=');
/** The part of query of page */
define('PAGE',     '&amp;page=');
#
# idxCMS version
#
/** Version of the system */
define('IDX_VERSION', '4.1');
/** Copyright */
define('IDX_COPYRIGHT', '&copy; 2011-2016 Greenray');
/** Message about system generator */
define('IDX_POWERED', 'Powered by idxCMS v'.IDX_VERSION);

umask(000);     # UMASK Must be 000!

error_reporting(E_ALL & ~E_DEPRECATED);

/**
 * Set error handler.
 * Handles php errors and writes info into the /content/logs/idxerror.log.
 *
 * @param  integer $num   Error number
 * @param  string  $msg   Error message
 * @param  string  $file  Name of the file where the error was generated
 * @param  integer $line  Line number where the error was generated
 * @return string  $error Error message
 */
function idxErrorHandler($num, $msg, $file, $line) {
    $type = [
           1 => 'Error',
           2 => 'Warning',
           4 => 'Parsing Error',
           8 => 'Notice',
          16 => 'Core Error',
          32 => 'Core Warning',
          64 => 'Compile Error',
         128 => 'Compile Warning',
         256 => 'User Error',
         512 => 'User Warning',
        1024 => 'User Notice',
        2048 => 'Runtime Notice',
        4096 => 'Catchable Fatal Error',
        8192 => 'Deprecated'
    ];

    $error = date('Y-m-d H:i:s (T)').' '.$type[$num].': '.$msg.' in '.$file.', line '.$line.LF;
    file_put_contents(LOGS.'idxerror.log', $error, FILE_APPEND | LOCK_EX);
}
set_error_handler("idxErrorHandler", E_ALL & ~E_DEPRECATED);
#
# Loading system libraries
#
include_once SYS.'cms.class.php';
include_once SYS.'functions.php';
include_once SYS.'filter.class.php';
include_once SYS.'log.class.php';
include_once SYS.'config.class.php';
include_once SYS.'user.class.php';
include_once SYS.'system.class.php';
include_once SYS.'dbase.class.php';
include_once SYS.'sections.class.php';
include_once SYS.'categories.class.php';
include_once SYS.'items.class.php';
include_once SYS.'comments.class.php';
include_once SYS.'content.class.php';
include_once SYS.'parser.class.php';
include_once SYS.'highlighter.class.php';
include_once SYS.'uploader.class.php';
include_once SYS.'image.class.php';
include_once SYS.'template.class.php';
include_once SYS.'css.class.php';

session_start();
$_SESSION['file'] = 0;

/** Website localization */
global $LANG;
#
# Send main headers
#
header('Last-Modified: '.gmdate('r'));
header('Content-Type: text/html; charset=UTF-8');
header('Content-Language: '.SYSTEM::get('locale'));
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

CMS::call('FILTER')->sanitate();
#
# System initialization
#
CMS::call('SYSTEM');
#
# Filtered globals $_GET, $_POST, $_FILES and $_COOKIE
#
$REQUEST = FILTER::getAll('REQUEST');

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
$MODULE = empty($REQUEST['module']) ? 'index' : basename($REQUEST['module']);
/** Requested module */
define('CURRENT_MODULE', $MODULE);

switch($MODULE) {
    #
    # Editor for posting
    #
    case 'editor':
        include TOOLS.'editor.php';
        break;
    #
    # Rate
    #
    case 'rate':
        if (CONFIG::getValue('enabled', 'rate')) {
            include MODULES.'rate'.DS.'rate.php';
        }
        break;
    #
    # Aphorisms flipping
    #
    case 'aphorisms':
        include MODULES.'aphorisms'.DS.'aphorisms.php';
        break;
    #
    # RSS
    #
    case 'rss':
        include MODULES.'rss'.DS.'rss.php';
        break;
    #
    # Website administration
    #
    case 'admin':
        #
        # Constants for administration section of the website
        #
        /** Root directory for administrative part of CMS */
        define('ADMIN', ROOT.'admin'.DS);
        /** Libruaries for administration panel */
        define('ADMINLIBS', ADMIN.'libs'.DS);
        /** Templates for administration panel */
        define('TEMPLATES', ADMIN.'templates'.DS);

        require_once ADMINLIBS.'functions.php';
        include_once ADMIN.'languages'.DS.SYSTEM::get('language').'.php';  # Localization

        if (USER::$root) {
            $modules = AdvScanDir(ADMIN.'modules', '', 'dir');
            $MODULES = [];

            /** Prevent direct access to php files */
            define('idxADMIN', TRUE);
            #
            # Initialize enabled modules
            #
            foreach ($modules as $module) {
                if ((substr($module, 0, 1) === '_') || CONFIG::getValue('enabled', $module)) {
                    include_once ADMIN.'modules'.DS.$module.DS.'module.php';
                }
            }

            $id = empty($REQUEST['id']) ? '' : basename($REQUEST['id']);
            ob_start();
            if (empty($REQUEST['id'])) {
                #
                # Activate default module of admin panel
                #
                require ADMIN.'modules'.DS.'index.php';
            } else {
                list($module, $action) = explode('.', $REQUEST['id']);
                if (file_exists(ADMIN.'modules'.DS.$module.DS.$action.'.php')) {
                    include ADMIN.'modules'.DS.$module.DS.$action.'.php';
                } elseif (file_exists(ADMINLIBS.$action.'.php')) {
                    include ADMINLIBS.$action.'.php';
                } else {
                    #
                    # User is not admin or has no access rights
                    #
                    $TEMPLATE = new TEMPLATE(TEMPLATES.'error_full.tpl');
                    $TEMPLATE->set('url', MODULE.'admin');
                    $TEMPLATE->set('message', 'Module not found');
                    echo $TEMPLATE->parse();
                    $result = ob_get_contents();
                    ob_end_clean();
                    echo $result;
                    break;
                }
            }

            $result = ob_get_contents();
            ob_end_clean();
            $menu = [];
            $navigation = json_decode(file_get_contents(CONTENT.'menu'), TRUE);
            foreach ($navigation as $k => $item) {
                $menu[$k]['link']  = $item['link'];
                $menu[$k]['name']  = $item['name'];
                $menu[$k]['class'] = $item['class'];
            }

            $output = [];
            foreach($MODULES as $module => $data) {
                if (!empty($data[1])) {
                    if (is_array($data[1])) {
                        $output[$module]['name'] = __($data[0]);
                        $output[$module]['id']   = $module;
                        foreach($data[1] as $category => $title) {
                            $output[$module]['module'][$category]['category'] = $category;
                            $output[$module]['module'][$category]['module']   = $module;
                            $output[$module]['module'][$category]['title']    = __($title);
                        }
                    }
                }
            }

            /* @todo Auromatic skin selection */
            $TEMPLATE = new TEMPLATE(ADMIN.'skins/Default/default.tpl');
            $TEMPLATE->set('locale', SYSTEM::get('locale'));
            $TEMPLATE->set('menu',    $menu);
            $TEMPLATE->set('page',    $result);
            $TEMPLATE->set('modules', $output);
            echo $TEMPLATE->parse();

        } else {
            #
            # User is not admin or has no access rights
            #
            $TEMPLATE = new TEMPLATE(TEMPLATES.'login.tpl');
            $TEMPLATE->set('locale', SYSTEM::get('locale'));
            echo $TEMPLATE->parse();
        }
        break;

    default:
        include_once SYS.'statistics.class.php';

        /** System templates */
        define('TEMPLATES', SYS.'templates'.DS);
        #
        # Loading main module
        #
        SYSTEM::setCurrentPoint('__MAIN__');
        #
        # Current skin definition
        #
        require_once CURRENT_SKIN.'skin.php';
        #
        # Get requested or default module
        #
        $mod = explode('.', $MODULE, 2);
        if (empty(SYSTEM::$modules[$MODULE]) || !file_exists(MODULES.$mod[0].DS.end($mod).'.php'))
             include_once MODULES.'index'.DS.'index.php';
        else include_once MODULES.$mod[0].DS.end($mod).'.php';

        $skin = SYSTEM::get('skin');
        #
        # Load other modules and organize them according output settings
        #
        $modules = [];
        if (in_array($MODULE, array_keys(CONFIG::getSection('output.'.$skin)))) {
            $modules[$MODULE] = CONFIG::getValue('output.'.$skin, $MODULE);
        }
        $modules['right'] = CONFIG::getValue('output.'.$skin, 'right');
        $modules['boxes'] = CONFIG::getValue('output.'.$skin, 'boxes');
        if (empty($modules[$MODULE])) {
            $modules['left'] = CONFIG::getValue('output.'.$skin, 'left');
        }
        foreach ($modules as $point => $boxes) {
            SYSTEM::setCurrentPoint($point);
            if (!empty($boxes)) {
                foreach ($boxes as $active) {
                    if (CONFIG::getValue('enabled', $active)) {
                        $mod = explode('.', $active, 2);
                        if (!empty(SYSTEM::$modules[$active])) {
                            include MODULES.$mod[0].DS.end($mod).'.php';
                        }
                    }
                }
            }
        }

        $output = [];
        if ($MODULE === 'index') {
            $output['index'] = TRUE;
            $output['tabs']  = $_SESSION['tabs'];
        }
        $output['locale'] = SYSTEM::get('locale');
        $output['slogan'] = CONFIG::getValue('main', 'slogan');
        if (!empty($output['slogan'])) {
            if (CONFIG::getValue('main', 'random_slogan')) {
                $output['slogan'] = $aphorisms[array_rand($aphorisms, 1)];
            }
        }
        #
        # The page is generated, it is possible to show it
        #
        $TEMPLATE = new TEMPLATE(CURRENT_SKIN.'main.tpl');
        $TEMPLATE->set($output);
        echo $TEMPLATE->parse();
        break;
}

restore_error_handler();
