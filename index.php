<?php
/**
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>\n
 *            <https://github.com/Greenray/idxCMS/index.php>
 * @copyright (c) 2011 - 2014 Victor Nabatov\n
 *            Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *            <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 */

ini_set('phar.readonly', 0);
ini_set('display_errors', 1);
mb_internal_encoding("UTF-8");
setlocale(LC_CTYPE, array('ru_RU.UTF-8', 'ru_UA.UTF-8', 'by_BY.UTF-8', 'en_US.UTF-8', 'en_GB.UTF-8'));
setlocale(LC_ALL, array('ru_RU.UTF-8', 'ru_UA.UTF-8', 'by_BY.UTF-8', 'en_US.UTF-8', 'en_GB.UTF-8'));
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

/** Set error handler.
 * @param  integer $num  Error number
 * @param  string  $msg  Error message
 * @param  string  $file Nameof the file where the error was generated
 * @param  integer $line Line number where the error was generated
 * @return nothing
 */
function idxErrorHandler($num, $msg, $file, $line) {
    $type = [
           1 => "Error",
           2 => "Warning",
           4 => "Parsing Error",
           8 => "Notice",
          16 => "Core Error",
          32 => "Core Warning",
          64 => "Compile Error",
         128 => "Compile Warning",
         256 => "User Error",
         512 => "User Warning",
        1024 => "User Notice",
        2048 => 'Runtime Notice',
        4096 => 'Catchable Fatal Error',
        8192 => 'Deprecated'
    ];
    $error = date("Y-m-d H:i:s (T)").' '.$type[$num].': '.$msg.' in '.$file.', line '.$line.PHP_EOL;
    file_put_contents('./content/logs/idxerror.log', $error, FILE_APPEND | LOCK_EX);
}
set_error_handler("idxErrorHandler", E_ALL | E_STRICT);

# Constants.
define('idxCMS', TRUE);
define('DS',     DIRECTORY_SEPARATOR);
define('LF',     PHP_EOL);

# Paths
define('ROOT',   '.'.DS);
define('CONTENT', ROOT.'content'.DS);
define('SKINS',   ROOT.'skins'.DS);
define('SYS',     ROOT.'system'.DS);
define('TOOLS',   ROOT.'tools'.DS);
define('MODULES', SYS.'modules'.DS);
define('TEMP',    CONTENT.'temp'.DS);
define('USERS',   CONTENT.'users'.DS);
define('IMAGES',  SKINS.'images'.DS);
define('ICONS',   IMAGES.'icons'.DS);
define('SMILES',  IMAGES.'smiles'.DS);

# The query elements
define('MODULE',    ROOT.'?module=');
define('SECTION',  '&amp;section=');
define('CATEGORY', '&amp;category=');
define('ITEM',     '&amp;item=');
define('COMMENT',  '&amp;comment=');
define('PAGE',     '&amp;page=');

#idxCMS version
define('IDX_VERSION', '2.3');
define('IDX_COPYRIGHT', '&copy; 2011 - 2014 Greenray');
define('IDX_POWERED', 'Powered by idxCMS - '.IDX_VERSION);

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
include_once(SYS.'uploader.class.php');

session_start();

global $LANG;   # Website localization

CMS::call('FILTER')->sanitaze();

/** @var $REQUEST = FILTER::getAll('REQUEST')
 * Array of request parametersC
 */
$REQUEST = FILTER::getAll('REQUEST');

CMS::call('SYSTEM');

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

$MODULE = empty($REQUEST['module']) ? 'index' : basename($REQUEST['module']);
switch($MODULE) {

    case 'editor':
        include(TOOLS.'editor.php');                        # Editor for posting
        break;

    case 'rate':
        if (CONFIG::getValue('enabled', 'rate')) {
            include(MODULES.'rate'.DS.'rate.php');          # Rate feauture
        }
        break;

    case 'aphorisms':
        include(MODULES.'aphorisms'.DS.'aphorisms.php');    # Aphorisms flipping
        break;

    case 'rss':
        include(MODULES.'rss'.DS.'rss.php');                # RSS feature
        break;

    case 'admin':                                           # Website administratin
        # Constants for administration section of the website
        define('ADMIN', ROOT.'admin'.DS);
        define('ADMINLIBS', ADMIN.'libs'.DS);
        define('TEMPLATES', ADMIN.'templates'.DS);

        require_once(ADMINLIBS.'functions.php');                            # Functions libruary
        include_once(ADMIN.'languages'.DS.SYSTEM::get('language').'.php');  # Localization

        if (CMS::call('USER')->checkRoot()) {
            $modules = AdvScanDir(ADMIN.'modules', '', 'dir');
            $MODULES = array();

            define('idxADMIN', TRUE);   # Prevent direct access to php files

            foreach ($modules as $module) {
                if ((substr($module, 0, 1) === '_') || CONFIG::getValue('enabled', $module)) {
                    include_once(ADMIN.'modules'.DS.$module.DS.'module.php');     # Initialize module
                }
            }

            $id = empty($REQUEST['id']) ? '' : basename($REQUEST['id']);
            switch ($id) {
                case 'main':
                    include(ADMIN.'frameset.php');      # Open administration panel or show frames error
                    break;

                case 'header':
                    include(ADMIN.'header.php');        # Header of the administration panel
                    break;

                case 'nav':
                    require(ADMIN.'navigation.php');    # Menu of the administration panel
                    break;

                case 'footer':
                    include(ADMIN.'footer.php');        # Footer of the administration panel
                    break;

                default:
                    if (empty($REQUEST['id'])) {
                        $id = 'index';
                        require(ADMIN.'module.php');    # Activate module 'index'
                    } else {
                        list($module, $action) = explode('.', $id);
                        $id = strtr($id, '.', DS);
                        require(ADMIN.'module.php');    # Activate module
                    }
                    break;
            }
        } else {
            $message[0] = __('Access denied');
            $message[1] = LoginForm();
            include(ADMIN.'error.php');
        }
        break;

    default:
        include_once(SYS.'statistic.php');
        define('TEMPLATES', SYS.'templates'.DS);            # System templates

        # Loading main module
        CMS::call('SYSTEM')->setCurrentPoint('__MAIN__');
        require_once(CURRENT_SKIN.'skin.php');              # Current skin definition

        $mod = explode('.', $MODULE, 2);
        if (empty(SYSTEM::$modules[$MODULE]) || !file_exists(MODULES.$mod[0].DS.end($mod).'.php')) {
            include_once(MODULES.'index'.DS.'index.php');
        } else {
            include_once(MODULES.$mod[0].DS.end($mod).'.php');   # Get active module
        }

        $_SESSION['request'] = $MODULE;
        # Load other modules
        $skin    = SYSTEM::get('skin');
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

        $TPL = new TEMPLATE('main.tpl');
        echo $TPL->parse($output);
        break;
}
