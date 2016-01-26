<?php
/**
 * System, modules, users and templates initialization.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2016 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      system/system.class.php
 * @package   Core
 */

class SYSTEM {

    /**
     * Array of the output points.
     * Currently there are six points:
     * - 'point' may be left or right panel of the website page as well as upper or lawer than main module
     * - 'main'  data for the website main window
     * - 'box'   data for website boxes
     * - 'title' name of the current page will be added to the website title, if the last has been configured
     * - 'meta'  configured meta tags will be added to the head of the generating page
     * - 'error' an error message will be shown
     *
     * @param string
     */
    public static  $current_point = '';

    /** @var array Website RSS feeds */
    private static $feeds = [];

    /** @var string User language */
    private static $language = '';

    /** @var string Available website translations */
    private static $languages = [];

    /** @var string Current locale */
    private static $locale = '';

    /** @var array Website menu */
    private static $menu = [];

    /** @var array Website meta */
    private static $meta = [];

    /** @var array CMS modules */
    public static  $modules = [];

    /** @var array Array of generated output data */
    public static  $output = [];

    /** @var string Name of the current page */
    private static $pagename = '';

    /** @var array Search words from user request */
    private static $search = [];

    /** @var array Website map */
    private static $sitemap = [];

    /** @var string Current skin */
    private static $skin = 'Default';

    /** @var array Website skins */
    public static  $skins = [];

    /**
     * Website URL.
     * This one may be configured by website administrator or detected automaticaly.
     *
     * @var string
     */
    private static $url = '';

    /**
     * Initialization of the system.
     *
     * @uses $LANG Website translations
     */
    public function __construct() {
        global $LANG;
        #
        # Detect website url
        #
        self::$url = CMS::call('CONFIG')->getValue('main', 'url');
        if (empty(self::$url)) {
            self::$url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'].basename($_SERVER['SCRIPT_NAME']));
        }
        #
        # Check if it is allowed to change website language and set user or default language
        #
        $COOKIE = CMS::call('FILTER')->getAll('COOKIE');
        $cookie_lang    = CONFIG::getValue('main', 'cookie').'_lang';
        self::$language = CONFIG::getValue('main', 'lang');
        if (CONFIG::getValue('main', 'allow_language')) {
            $language = FILTER::get('REQUEST', 'language');
            if (!empty($language)) {
                self::$language = $language;

            } else {
                if (!empty($COOKIE[$cookie_lang])) {
                    self::$language = $COOKIE[$cookie_lang];
                }
            }
        }
        #
        # Avaible localizations list
        #
        $langs = GetFilesList(SYS.'languages');
        foreach ($langs as $lng) {
            self::$languages[] = basename($lng, '.php');
        }

        include_once SYS.'languages'.DS.self::$language.'.php';

        self::$language = $LANG['language'];
        self::$locale   = $LANG['locale'];

        $cookie = time() + 3600 * 24 * 365 * 5;
        setcookie($cookie_lang, self::$language, $cookie);
        #
        # Check if it is allowed to change website skin and set user or default skin
        #
        $cookie_skin = CONFIG::getValue('main', 'cookie').'_skin';
        self::$skin  = CONFIG::getValue('main', 'skin');
        if (CONFIG::getValue('main', 'allow_skin')) {
            $skin = FILTER::get('REQUEST', 'skin');
            if (!empty($skin)) {
                self::$skin = $skin;

            } else {
                if (!empty($COOKIE[$cookie_skin])) {
                    self::$skin = $COOKIE[$cookie_skin];
                }
            }
        }

        setcookie($cookie_skin, self::$skin, $cookie);
        if (is_dir(SKINS.self::$skin)) {
            /** User defined skin */
            define('CURRENT_SKIN', SKINS.self::$skin.DS);
        }
    }

    /**
     * Creates main menu for website.
     * The menu will be created only for registered and enabled modules.
     *
     *  @return boolean The result of operation
     */
    public function createMainMenu() {
        $enabled = CONFIG::getSection('enabled');
        $menu    = [];
        $menu['index']['module'] = 'index';
        $menu['index']['link']   = MODULE.'index';
        $menu['index']['name']   = __('Index');
        $menu['index']['desc']   = '';
        $menu['index']['class']  = 'index';
        $menu['index']['width']  = mb_strlen($menu['index']['name'], 'UTF-8') * 7 * 2;

        foreach (self::$modules as $module => $data) {
            if (in_array($module, self::$menu) && array_key_exists($module, $enabled)) {
                $obj = strtoupper($module);
                $point = [];
                $point[$module]['module'] = $module;
                $point[$module]['link']   = MODULE.$module;
                $point[$module]['name']   = SYSTEM::$modules[$module]['title'];
                $point[$module]['desc']   = '';
                $point[$module]['class']  = $module;

                if (class_exists($obj)) {
                    $point[$module]['sections'] = CMS::call($obj)->getSections();
                    unset($point[$module]['sections']['drafts']);
                    $point[$module]['width']  = mb_strlen($point[$module]['name'], 'UTF-8') * 7 * 2;

                    foreach ($point[$module]['sections'] as $id => $section) {
                        $point[$module]['sections'][$id]['desc'] = CMS::call('PARSER')->parseText($section['desc']);
                        if (!empty($section['categories'])) {
                            foreach ($section['categories'] as $key => $category) {
                                $point[$module]['sections'][$id]['categories'][$key]['desc'] = CMS::call('PARSER')->parseText($category['desc']);
                            }
                        }
                    }
                }

                $menu = array_merge($menu, $point);
                if (!empty($point[$module]['name'])) {
                    $menu[$module]['width'] = mb_strlen($menu[$module]['name'], 'UTF-8') * 7 * 2;

                    if (!empty($point[$module]['sections'])) {
                        foreach($point[$module]['sections'] as $id => $section) {
                            $width = mb_strlen($section['title'], 'UTF-8') * 7 + 55;
                            $menu[$module]['sections'][$id]['width'] = $width;

                            if (!empty($section['categories'])) {
                                foreach($section['categories'] as $key => $category) {
                                    $width = mb_strlen($category['title'], 'UTF-8') * 7 + 55;
                                    $menu[$module]['categories'][$id]['width'] = $width;
                                }
                            }
                        }
                    }
                }
            }
        }
        return file_put_contents(CONTENT.'menu', json_encode($menu, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
    }

    /**
     * Returns data for main menu or sitemap.
     * Only registered and enabled modules will be used.
     *
     *  @return array Data for main menu or sitemap
     */
    public function getMainMenu() {
        $data   = json_decode(file_get_contents(CONTENT.'menu'), TRUE);
        $access = USER::getUser('access');
        $output = [];

        foreach($data as $module => $point) {
            if (!empty($point['sections'])) {
                foreach ($point['sections'] as $id => $section) {
                    if ($section['access'] > $access) {
                        unset($point['sections'][$id]);

                    } else {
                        if (!empty($section['categories'])) {
                            foreach ($section['categories'] as $key => $category) {
                                if ($category['access'] > $access) {
                                    unset($point['sections'][$id]['categories'][$key]);
                                }
                            }
                        }
                    }
                }
            }
            $output[$module] = $point;
        }
        return $output;
    }

    /**
     * Gets system parameter.
     *
     * @param  string $param System parameter
     * @return string        Value of the requested parameter
     */
    public static function get($param) {
        return self::$$param;
    }

    /**
     * Modules initialization.
     *
     * @param boolean $ignore_disabled Init all existing modules
     * @uses array    $LANG Website translations
     */
    public function initModules($ignore_disabled = FALSE) {
        global $LANG;
        $enabled = CONFIG::getSection('enabled');
        if (empty($enabled) || $ignore_disabled) {
            $enabled = array_flip(GetFilesList(MODULES));
        }

        $included = [];
        foreach ($enabled as $module => $NULL) {
            $mod = explode('.', $module, 2);
            if (!in_array($mod[0], $included)) {
                include_once MODULES.$mod[0].DS.'module.php';
                $included[] = $mod[0];
            }
        }
    }

    /**
     * Registers RSS feed for module.
     * RSS feed ID is looks like "module@section".
     *
     * @param string $section RSS feed ID
     * @param string $title   RSS feed title
     * @param string $desc    RSS feed description
     * @param string $module  Module name
     */
    public static function registerFeed($section, $title, $desc, $module = '') {
        self::$feeds[$section] = [__($title), __($desc), $module];
    }

    /**
     * Registers module for menu link.
     *
     * @param string $module Module name
     */
    public static function registerMainMenu($module) {
        self::$menu[] = $module;
    }

    /**
     * Registers module.
     * Used for classify modules by type. There are three types:
     * - system (cannot be excluded);
     * - main (for full pages);
     * - box (for panels or boxes);
     * - plugin (cannot be showed on any page).
     *
     * @param string $module Module name
     * @param string $title  Module title
     * @param string $type   Module type
     * @param string $system
     */
    public static function registerModule($module, $title, $type, $system = '') {
        self::$modules[$module]['title']  = __($title);
        self::$modules[$module]['type']   = $type;
        self::$modules[$module]['system'] = $system;
    }

    /**
     * Registers module for search requests.
     *
     * @param string $module Module name
     */
    public static function registerSearch($module) {
        self::$search[] = $module;
    }

    /**
     * Registers module for use in sitemap.
     *
     * @param string $module Module name
     */
    public static function registerSiteMap($module) {
        self::$sitemap[] = $module;
    }

    /**
     * Registers module for menu link.
     *
     * @param string $name Skin name
     * @param string $skin Skin template
     */
    public static function registerSkin($name, $skin) {
        self::$skins[$name] = $skin;
    }

    /**
     * Sets system parameter.
     *
     * @param string $param System parameter
     * @param string $value Value of the system parameter
     */
    public static function set($param, $value) {
        self::$$param = $value;
    }

    /**
     * Sets the point for output.
     *
     * @param type $point Output point name
     */
    public static function setCurrentPoint($point) {
        self::$current_point = $point;
    }

    /**
     * Sets keywords for requested website page.
     * This description will be used in meta tag.
     * If some words has been set in website configuration (global keywords) the $keywords will be added to them.
     *
     * @param string $keywords Page keywords
     */
    public static function setPageKeywords($keywords) {
        self::$meta['keywords'] = empty(self::$meta['keywords']) ? $keywords : self::$meta['keywords'].','.$keywords;
    }

    /**
     * Sets description for requested website page.
     * This description will be used in meta tag.
     *
     * @param string $desc Page description
     */
    public static function setPageDescription($desc) {
        self::$meta['desc'] = $desc;
    }

    /**
     * Prepare data for output.
     *
     * @param  string $title   Window title
     * @param  string $content Content for output
     */
    public static function defineWindow($title, $content) {
        if (self::$current_point == '__MAIN__')
             self::$output['main'][] = [__($title), $content];
        else self::$output[self::$current_point][] = [__($title), $content];
    }

    /**
     * Shows window.
     *
     * @param  string $title    The title of the output data
     * @param  string $content  The content for use in output
     * @param  string $template Name of the template as a name of class to use in output
     * @return string           Website page
     */
    public static function showWindow($title, $content, $template) {
        if (($title === '__NOWINDOW__') || ($template === 'empty')) {
            return $content;

        } else {

            $TPL = new TEMPLATE(SYSTEM::$skins[$template]);
            $TPL->set(
                [
                    'title'   => $title,
                    'content' => $content,
                    'class'   => $template
                ]
            );
            return $TPL->parse();
        }
    }

    /**
     * Shows message.
     *
     * @param  string  $message  Message
     * @param  string  $url      Url for redirection
     */
    public static function showMessage($message, $url = '') {
        $TPL = new TEMPLATE(TEMPLATES.'message.tpl');
        $TPL->set('message', __($message));
        $TPL->set('url', $url);
        self::defineWindow('Message', $TPL->parse());
    }

    /**
     * Shows error.
     *
     * @param  string  $message  Message
     * @param  string  $url      Url for redirection
     */
    public static function showError($message, $url = '') {
        $TPL = new TEMPLATE(TEMPLATES.'error.tpl');
        $TPL->set('message', __($message));
        $TPL->set('url', $url);
        self::defineWindow('Error', $TPL->parse());
    }
}
