<?php
/**
 * @file      system/system.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>\n
 *            <https://github.com/Greenray/idxCMS/system/system.class.php>\n
 *            Reloadcms Team <http://reloadcms.com>
 * @copyright (c) 2011 - 2014 Victor Nabatov\n
 *            Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *            <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 */

/** @class SYSTEM
 * System, modules, users and templates initialization.
 */
class SYSTEM {

    /** Website URL.
     * This one may be configured by website administrator or detected automaticaly.
     * @param string
     */
    private static $url = '';

    /** User language.
     * @param string
     */
    private static $language = '';

    /** Available website translations.
     * @param string
     */
    private static $languages = array();

    /** Current locale.
     * @param string
     */
    private static $locale = '';

    /** Current skin.
     * @param string
     */
    private static $skin = 'Default';

    /** Website skins.
     * @param array
     */
    public static  $skins = array();

    /** CMS modules.
     * @param array
     */
    public static  $modules = array();
    public static  $current_point = '';
    public static  $output = array();

    /** Website RSS feeds
     * @param array
     */
    private static $feeds = array();
    private static $navigation = array();

    /** Website menu.
     * @param array
     */
    private static $menu = array();
    private static $pagename = '';

    /** Website map.
     * @param array
     */
    private static $sitemap = array();
    private static $search = array();

    /** Website meta.
     * @param array
     */
    private static $meta = array();

    /** Class initialization.
     * @global array $LANG - website translations
     */
    public function __construct() {
        global $LANG;
        # Detect website url
        self::$url = CMS::call('CONFIG')->getValue('main', 'url');
        if (empty(self::$url)) {
            self::$url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'].basename($_SERVER['SCRIPT_NAME'])).DS;
        }
        # Check if it is allowed to change website language and set user or default language
        $COOKIE = CMS::call('FILTER')->getAll('COOKIE');
        $cookie_lang    = CONFIG::getValue('main', 'cookie').'_lang';
        self::$language = CONFIG::getValue('main', 'lang');
        if (CONFIG::getValue('main', 'allow-lang')) {
            $language = FILTER::get('REQUEST', 'language');
            if (!empty($language)) {
                self::$language = $language;
            } else {
                if (!empty($COOKIE[$cookie_lang])) {
                    self::$language = $COOKIE[$cookie_lang];
                }
            }
        }
        # Avaible localizations list
        $langs = GetFilesList(SYS.'languages');
        foreach ($langs as $lng) {
            self::$languages[] = basename($lng, '.php');
        }
        include_once(SYS.'languages'.DS.self::$language.'.php');
        self::$language = $LANG['language'];
        self::$locale   = $LANG['locale'];
        $cookie = time() + 3600 * 24 * 365 * 5;
        setcookie($cookie_lang, self::$language, $cookie);
        # Check if it is allowed to change website skin and set user or default skin
        $cookie_skin = CONFIG::getValue('main', 'cookie').'_skin';
        self::$skin  = CONFIG::getValue('main', 'skin');
        if (CONFIG::getValue('main', 'allow-skin')) {
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

    /** Set system parameter.
     * @param  string $param System parameter
     * @param  string $value Value of the system parameter
     * @return nothing
     */
    public static function set($param, $value) {
        self::$$param = $value;
    }

    /** Get system parameter.
     * @param  string $param System parameter
     * @return string - Value of the requested parameter
     */
    public static function get($param) {
        return self::$$param;
    }

    /** Modules initialization.
     * @global array $LANG - website translations
     * @param  boolean $ignore_disabled Init all existing modules
     * @return nothing
     */
    public function initModules($ignore_disabled = FALSE) {
        global $LANG;
        $enabled = CONFIG::getSection('enabled');
        if (empty($enabled) || $ignore_disabled) {
            $enabled = array_flip(GetFilesList(MODULES));
        }
        $included = array();
        foreach ($enabled as $module => $null) {
            $mod = explode('.', $module, 2);
            if (!in_array($mod[0], $included)) {
                include_once(MODULES.$mod[0].DS.'module.php');
                $included[] = $mod[0];
            }
        }
    }

    /** Register module.
     * Used for classify modules by type. There are three types:
     * - system (cannot be excluded);
     * - main (for full pages);
     * - box (for panels or boxxes);
     * - plugin (cannot be showed on any page);
     * @param  string $module Module name
     * @param  string $title  Module title
     * @param  string $type   Module type
     * @param  string $system
     * @return nothing
     */
    public static function registerModule($module, $title, $type, $system = '') {
        self::$modules[$module]['title']  = __($title);
        self::$modules[$module]['type']   = $type;
        self::$modules[$module]['system'] = $system;
    }

    /** Register RSS feed for module.
     * RSS feed ID is looks like "module@section"
     * @param  string $section RSS feed ID
     * @param  string $title   RSS feed title
     * @param  string $desc    RSS feed description
     * @param  string $module  Module name
     * @return nothing
     */
    public static function registerFeed($section, $title, $desc, $module = '') {
        self::$feeds[$section] = array(__($title), __($desc), $module);
    }

    /** Register module for menu link.
     * @param  string $module Module name
     * @return nothing
     */
    public static function registerMainMenu($module) {
        self::$menu[] = $module;
    }

    /** Register module for use in sitemap.
     * @param  string $module Module name
     * @return nothing
     */
    public static function registerSiteMap($module) {
        self::$sitemap[] = $module;
    }

    /** Register module for search requests.
     * @param  string $module Module name
     * @return nothing
     */
    public static function registerSearch($module) {
        self::$search[] = $module;
    }

    /** Register module for menu link.
     * @param  string $name Skin name
     * @param  string $skin Skin template
     * @return nothing
     */
    public static function registerSkin($name, $skin) {
        self::$skins[$name] = $skin;
    }

    public static function setCurrentPoint($point) {
        self::$current_point = $point;
    }

    public static function defineWindow($title, $content, $align = 'left') {
        if (self::$current_point == '__MAIN__') {
            self::$output['main'][] = array($title, $content, $align);
        } else {
            self::$output[self::$current_point][] = array($title, $content, $align);
        }
    }

    public static function showWindow($title, $content, $align, $template) {
        if (($title === '__NOWINDOW__') || ($template === 'empty')) {
            return $content;
        } elseif ($title === 'Error') {
            /** @todo SYSTEM::$skins['error'] - ? */
            $TPL = new TEMPLATE(SYSTEM::$skins['error']);
            return $TPL->parse(
                array(
                    'title'   => __('Error'),
                    'content' => $content,
                    'align'   => 'center',
                    'class'   => 'error'
                )
            );
        } else {
            $TPL = new TEMPLATE(SYSTEM::$skins[$template]);
            return $TPL->parse(
                array(
                    'title'   => $title,
                    'content' => $content,
                    'align'   => $align,
                    'class'   => $template
                )
            );
        }
    }

    /** Set keywords for requested website page.
     * This description will be used in meta tag.
     * If some words has been set in website configuration (global keywords) the $keywords will be added to them
     * @param  string $keywords Page keywords
     * @return nothing
     */
    public static function setPageKeywords($keywords) {
        self::$meta['keywords'] = empty(self::$meta['keywords']) ? $keywords : self::$meta['keywords'].','.$keywords;
    }

    /** Set description for requested website page.
     * This description will be used in meta tag.
     * @param  string $desc Page description
     * @return nothing
     */
    public static function setPageDescription($desc) {
        self::$meta['desc'] = $desc;
    }

    # Get site navigation. If navigation is not exists creates it.
    public function getNavigation() {
        if (empty(self::$navigation)) {
            return self::createNavigation();
        }
        return self::$navigation;
    }

    # Create site navigation.
    public static function createNavigation() {
        $config = CONFIG::getSection('navigation');
        foreach ($config as $link) {
            $data   = explode(':', $link[0], 2);
            $module = trim($data[0]);
            if (!empty($data[1])) {
                $path = trim($data[1]);
                $obj = strtoupper($module);
                if (class_exists($obj)) {
                    $sections = CMS::call($obj)->getSections();
                    if (!empty($sections['drafts'])) {
                        unset($sections['drafts']);
                    }
                    if (!empty($sections)) {
                        $data = explode(DS, $path, 3);
                        switch (sizeof($data)) {

                            case 1:
                                if (!empty($sections[$data[0]])) {
                                    self::$navigation[] = array(
                                        'module' => $module,
                                        'link'   => $sections[$data[0]]['link'],
                                        'name'   => empty($link[1]) ? $sections[$data[0]]['title'] : __($link[1]),
                                        'desc'   => empty($link[2]) ? (empty($sections[$data[0]]['desc']) ? '' : $sections[$data[0]]['desc']) : __($link[2]),
                                        'icon'   => ICONS.$link[3]
                                    );
                                }
                                break;

                            case 2:
                                $categories = CMS::call($obj)->getCategories($data[0]);
                                if (!empty($categories[$data[1]])) {
                                    self::$navigation[] = array(
                                        'module' => $module,
                                        'link'   => MODULE.$module.SECTION.$data[0].CATEGORY.$data[1],
                                        'name'   => $categories[$data[1]]['title'],
                                        'desc'   => empty($link[2]) ? (empty($categories[$data[1]]['desc']) ? '' : $categories[$data[1]]['desc']) : __($link[2]),
                                        'icon'   => ICONS.$link[3]
                                    );
                                }
                                break;

                            case 3:
                                $categories = CMS::call($obj)->getCategories($data[0]);
                                if (!empty($categories)) {
                                    $content = CMS::call($obj)->getContent($data[1]);
                                    if (!empty($content[$data[2]])) {
                                        self::$navigation[] = array(
                                            'module' => $module,
                                            'link'   => MODULE.$module.SECTION.$data[0].CATEGORY.$data[1].ITEM.$data[2],
                                            'name'   => $content[$data[2]]['title'],
                                            'desc'   => empty($link[2]) ? '' : __($link[2]),
                                            'icon'   => ICONS.$link[3]
                                        );
                                    }
                                }
                            break;
                        }
                    }
                }
            } else {
                self::$navigation[] = array(
                    'link' => MODULE.$module,
                    'name' => empty($link[1]) ? self::$modules[$module]['title'] : __($link[1]),
                    'desc' => empty($link[2]) ? '' : __($link[2]),
                    'icon' => ICONS.$link[3]
                );
            }
        }
        return self::$navigation;
    }

    /** Create main menu for website.
     * The menu will be created only for registered and enabled modules.
     * @return boolean - The result of operation
     */
    public function createMainMenu() {
        $enabled = CONFIG::getSection('enabled');
        $menu  = array();
        $menu['index']['module'] = 'index';
        $menu['index']['link']   = MODULE.'index';
        $menu['index']['name']   = __('Index');
        $menu['index']['desc']   = '';
        $menu['index']['icon']   = ICONS.'index.png';
        $menu['index']['width']  = mb_strlen($menu['index']['name'], 'UTF-8') * 7;
        $width = mb_strlen($menu['index']['desc'], 'UTF-8') * 7;
        $menu['index']['width'] = ($menu['index']['width'] > $width) ? $menu['index']['width'] : $width;

        foreach (self::$modules as $module => $data) {
            if (in_array($module, self::$menu) && array_key_exists($module, $enabled)) {
                $obj = strtoupper($module);
                $point = array();
                $point[$module]['module'] = $module;
                $point[$module]['link']   = MODULE.$module;
                $point[$module]['name']   = SYSTEM::$modules[$module]['title'];
                $point[$module]['desc']   = '';
                $point[$module]['icon']   = ICONS.$module.'.png';

                if (class_exists($obj)) {
                    $point[$module]['sections'] = CMS::call($obj)->getSections();
                    if (!empty($point[$module]['sections']['drafts'])) unset($point[$module]['sections']['drafts']);
                    $point[$module]['width']  = mb_strlen($point[$module]['name'], 'UTF-8') * 7;
                    $width = mb_strlen($point[$module]['desc'], 'UTF-8') * 7;
                    $point[$module]['width'] = ($point[$module]['width'] > $width) ? $point[$module]['width'] : $width;
                    foreach ($point[$module]['sections'] as $id => $section) {
                        $point[$module]['sections'][$id]['desc'] = CMS::call('PARSER')->parseText($section['desc']);
                        foreach ($section['categories'] as $key => $category) {
                            $point[$module]['sections'][$id]['categories'][$key]['desc'] = CMS::call('PARSER')->parseText($category['desc']);
                        }
                    }
                }

                if (!empty($point)) {
                    $menu = array_merge($menu, $point);
                    if (!empty($point[$module]['name'])) {
                        $menu[$module]['width'] = mb_strlen($menu[$module]['name'], 'UTF-8') * 7;
                        $width = mb_strlen($menu[$module]['desc'], 'UTF-8') * 7;
                        $menu[$module]['width'] = ($menu[$module]['width'] > $width) ? $menu[$module]['width'] : $width;
                        if (!empty($point[$module]['sections'])) {
                            foreach($point[$module]['sections'] as $id => $section) {
                                $width = mb_strlen($section['title'], 'UTF-8') * 7 + 55;
                                $menu[$module]['sections'][$id]['width'] = $width;
                                $menu[$module]['width'] = ($menu[$module]['width'] > $width) ? $menu[$module]['width'] : $width;
                                $width = mb_strlen($section['desc'], 'UTF-8') * 7 + 55;
                                $menu[$module]['width'] = ($menu[$module]['width'] > $width) ? $menu[$module]['width'] : $width;
                                if (!empty($section['categories'])) {
                                    foreach($section['categories'] as $key => $category) {
                                        $width = mb_strlen($category['title'], 'UTF-8') * 7 + 55;
                                        $menu[$module]['categories'][$id]['width'] = $width;
                                        $menu[$module]['width'] = ($menu[$module]['width'] > $width) ? $menu[$module]['width'] : $width;
                                        $width = mb_strlen($category['desc'], 'UTF-8') * 7 + 55;
                                        $menu[$module]['width'] = ($menu[$module]['width'] > $width) ? $menu[$module]['width'] : $width;
                                    }
                                }
                            }
                        }

                        if (!empty($point[$module]['categories'])) {
                            foreach($point[$module]['categories'] as $id => $category) {
                                $width = mb_strlen($category['title'], 'UTF-8') * 7 + 55;
                                $menu[$module]['categories'][$id]['width'] = $width;
                                $menu[$module]['width'] = ($menu[$module]['width'] > $width) ? $menu[$module]['width'] : $width;
                                $width = mb_strlen($category['desc'], 'UTF-8') * 7 + 55;
                                $menu[$module]['width'] = ($menu[$module]['width'] > $width) ? $menu[$module]['width'] : $width;
                            }
                        }
                    }

                }
            }
        }
        file_put_contents(CONTENT.'menu', serialize($menu));
    }
}
