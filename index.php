<?php
#   idxCMS Flat Files Content Management Sysytem
#   Version 2.3
#   copyright (c) 2011 - 2014 Victor Nabatov

/**
 * The core of the content management system.
 *
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2014 Victor Nabatov
 * @license   <http://creativecommons.org/licenses/by-nc-sa/3.0/> Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 */

/** @package core */

ini_set('phar.readonly', 0);
ini_set('display_errors', 1);
mb_internal_encoding('UTF-8');
setlocale(LC_CTYPE, ['ru_RU.UTF-8', 'ru_UA.UTF-8', 'by_BY.UTF-8', 'en_US.UTF-8', 'en_GB.UTF-8']);
setlocale(LC_ALL,   ['ru_RU.UTF-8', 'ru_UA.UTF-8', 'by_BY.UTF-8', 'en_US.UTF-8', 'en_GB.UTF-8']);
if (date_default_timezone_set(date_default_timezone_get()) === FALSE) {
    date_default_timezone_set('UTC');
}

# Unset any globals created by register_globals being turned ON
while (list($global) = each($GLOBALS)) {
    if (!preg_match('/^(_REQUEST|_COOKIE|_SERVER|_FILES|GLOBALS|HTTP.*)$/', $global)) {
        unset($$global);
    }
}
unset($global);

error_reporting(-1);

/**
 * Set error handler.
 * Handles php errors and writes info into the /content/logs/idxerror.log.
 *
 * @param  integer $num  Error number.
 * @param  string  $msg  Error message.
 * @param  string  $file Name of the file where the error was generated.
 * @param  integer $line Line number where the error was generated.
 * @return void
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
    $error = date('Y-m-d H:i:s (T)').' '.$type[$num].': '.$msg.' in '.$file.', line '.$line.PHP_EOL;
    file_put_contents('./content/logs/idxerror.log', $error, FILE_APPEND | LOCK_EX);
}
set_error_handler("idxErrorHandler", E_ALL | E_STRICT);

# Main conststants

/** Prevent direct access to php files. */
define('idxCMS', TRUE);
/** Alias for DIRECTORY_SEPARATOR. */
define('DS', DIRECTORY_SEPARATOR);
/** Line feed. */
define('LF', PHP_EOL);
/** System root directory. */
define('ROOT', '.'.DS);
/** Path to the CMS content. */
define('CONTENT', ROOT.'content'.DS);
/** CMS skins. */
define('SKINS',   ROOT.'skins'.DS);
/** Path to the CMS core. */
define('SYS',     ROOT.'system'.DS);
/** Path to the CMS tools. */
define('TOOLS',   ROOT.'tools'.DS);
/** Path to the CMS modules. */
define('MODULES', SYS.'modules'.DS);
/** Logs data store. */
define('LOGS',    CONTENT.'logs'.DS);
/** Temporary directory. */
define('TEMP',    CONTENT.'temp'.DS);
/** Path to the users profiles. */
define('USERS',   CONTENT.'users'.DS);
/** Path to the common images. */
define('IMAGES',  SKINS.'images'.DS);
/** Path to the icons. */
define('ICONS',   IMAGES.'icons'.DS);
/** Path to the smil. */
define('SMILES',  IMAGES.'smiles'.DS);

# Elements of the query website page

/** Query of module. */
define('MODULE',    ROOT.'?module=');
/** The part of query of section. */
define('SECTION',  '&amp;section=');
/** The part of query of category. */
define('CATEGORY', '&amp;category=');
/** The part of query of item. */
define('ITEM',     '&amp;item=');
/** The part of query of comment. */
define('COMMENT',  '&amp;comment=');
/** The part of query of page. */
define('PAGE',     '&amp;page=');

# idxCMS version

/** Version of the system. */
define('IDX_VERSION', '2.3');
/** Copyright. */
define('IDX_COPYRIGHT', '&copy; 2011 - 2014 Greenray');
/** Message about system generator. */
define('IDX_POWERED', 'Powered by idxCMS - '.IDX_VERSION);

umask(000);     # UMASK Must be 000!

# Loading system libraries.
include_once(SYS.'cms.class.php');
include_once(SYS.'filter.class.php');
include_once(SYS.'log.class.php');
include_once(SYS.'config.class.php');
include_once(SYS.'system.class.php');
include_once(SYS.'user.class.php');
include_once(SYS.'functions.php');
include_once(SYS.'forms.class.php');
include_once(SYS.'index.class.php');
include_once(SYS.'content.class.php');
include_once(SYS.'parser.class.php');
include_once(SYS.'template.class.php');
include_once(SYS.'uploader.class.php');

session_start();

/** Website localization. */
global $LANG;

CMS::call('FILTER')->sanitaze();

/** Filtered globals $_GET, $_POST, $_FILES and $_COOKIE. */
$REQUEST = FILTER::getAll('REQUEST');

CMS::call('SYSTEM');

# Send main headers.
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

/** Requested module. */
$MODULE = empty($REQUEST['module']) ? 'index' : basename($REQUEST['module']);
switch($MODULE) {

    # Editor for posting.
    case 'editor':
        include(TOOLS.'editor.php');
        break;

    # Rate feauture.
    case 'rate':
        if (CONFIG::getValue('enabled', 'rate')) {
            include(MODULES.'rate'.DS.'rate.php');
        }
        break;

    # Aphorisms flipping.
    case 'aphorisms':
        include(MODULES.'aphorisms'.DS.'aphorisms.php');
        break;

    # RSS feature.
    case 'rss':
        include(MODULES.'rss'.DS.'rss.php');
        break;

    # Website administration.
    case 'admin':

        # Constants for administration section of the website.

        /** Root directory for administrative part of CMS. */
        define('ADMIN', ROOT.'admin'.DS);
        /** Libruries for administration panel. */
        define('ADMINLIBS', ADMIN.'libs'.DS);
        /** Templates for administration panel. */
        define('TEMPLATES', ADMIN.'templates'.DS);

        require_once(ADMINLIBS.'functions.php');
        include_once(ADMIN.'languages'.DS.SYSTEM::get('language').'.php');  # Localization.

        if (CMS::call('USER')->checkRoot()) {
            $modules = AdvScanDir(ADMIN.'modules', '', 'dir');
            $MODULES = array();

            /** Prevent direct access to php files. */
            define('idxADMIN', TRUE);

            # Initialize enabled modules.
            foreach ($modules as $module) {
                if ((substr($module, 0, 1) === '_') || CONFIG::getValue('enabled', $module)) {
                    include_once(ADMIN.'modules'.DS.$module.DS.'module.php');
                }
            }

            $id = empty($REQUEST['id']) ? '' : basename($REQUEST['id']);
            switch ($id) {

                # Open administration panel or show frames error.
                case 'main':
                    include(ADMIN.'frameset.php');
                    break;

                case 'header':
                    include(ADMIN.'header.php');        # Header of the administration panel.
                    break;

                case 'nav':
                    require(ADMIN.'navigation.php');    # Menu of the administration panel.
                    break;

                case 'footer':
                    include(ADMIN.'footer.php');        # Footer of the administration panel.
                    break;

                default:
                    if (empty($REQUEST['id'])) {
                        $id = 'index';
                        require(ADMIN.'module.php');    # Activate default module of admin panel
                    } else {
                        list($module, $action) = explode('.', $id);
                        $id = strtr($id, '.', DS);
                        require(ADMIN.'module.php');    # Activate requested module
                    }
                    break;
            }
        } else {

            # User is not admin or has no access rights.
            $message[0] = __('Access denied');
            $message[1] = LoginForm();
            include(ADMIN.'error.php');
        }
        break;

    default:
        include_once(SYS.'statistic.php');

        /** System templates. */
        define('TEMPLATES', SYS.'templates'.DS);

        # Loading main module.
        CMS::call('SYSTEM')->setCurrentPoint('__MAIN__');
        # Current skin definition
        require_once(CURRENT_SKIN.'skin.php');

        # Get requested or default module.
        $mod = explode('.', $MODULE, 2);
        if (empty(SYSTEM::$modules[$MODULE]) || !file_exists(MODULES.$mod[0].DS.end($mod).'.php')) {
            include_once(MODULES.'index'.DS.'index.php');
        } else {
            include_once(MODULES.$mod[0].DS.end($mod).'.php');
        }

        $skin = SYSTEM::get('skin');

        # Load other modules and organize them according output settings.
        $modules = array();
        if (in_array($MODULE, array_keys(CONFIG::getSection('output.'.$skin)))) {
            $modules[$MODULE] = CONFIG::getValue('output.'.$skin, $MODULE);
        }
        $modules['left']  = CONFIG::getValue('output.'.$skin, 'left');
        $modules['right'] = CONFIG::getValue('output.'.$skin, 'right');
        $modules['boxes'] = CONFIG::getValue('output.'.$skin, 'boxes');
        if (!empty($modules[$MODULE])) {
            unset($modules['left']);
        }
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

        # The page is generated, it is possible to show it.
        $TPL = new TEMPLATE('main.tpl');
        echo $TPL->parse($output);
        break;
}
