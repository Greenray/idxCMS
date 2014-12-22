<?php
/**
 * @file      system/system.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2014 Victor Nabatov
 * @license   <http://creativecommons.org/licenses/by-nc-sa/3.0/> Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Core
 */

/** Class SYSTEM - System, modules, users and templates initialization. */

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
    private static $languages = [];

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
    public static  $skins = [];

    /** CMS modules.
     * @param array
     */
    public static  $modules = [];

    /** Array of the output points.
     * Currently there are six points:
     * - 'point' - may be left or right panel of the website page as well as upper or lawer than main module;
     * - 'main'  - data for the website main window;
     * - 'box'   - data for website boxes;
     * - 'title' - name of the current page will be added to the website title, if the last has been configured;
     * - 'meta'  - configured meta tags will be added to the head of the generating page;
     * - 'error' - an error message will be shown.
     *
     * @param string
     */
    public static  $current_point = '';

    /** Array of generated output data.
     * @param array
     */
    public static  $output = [];

    /** Website RSS feeds.
     * @param array
     */
    private static $feeds = [];

    /** Array of navigation pint of the website.
     * @param array
     */
    private static $navigation = [];

    /** Website menu.
     * @param array
     */
    private static $menu = [];

    /** Name of the current page.
     * @param string
     */
    private static $pagename = '';

    /** Website map.
     * @param array
     */
    private static $sitemap = [];

    /** Search words from user request.
     * @param array
     */
    private static $search = [];

    /** Website meta.
     * @param array
     */
    private static $meta = [];

    /** Class initialization.
     * @return void
     * @uses $LANG Website translations.
     */
    public function __construct() {
        global $LANG;
        # Detect website url.
        self::$url = CMS::call('CONFIG')->getValue('main', 'url');
        if (empty(self::$url)) {
            self::$url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'].basename($_SERVER['SCRIPT_NAME'])).DS;
        }
        # Check if it is allowed to change website language and set user or default language.
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
        # Avaible localizations list.
        $langs = GetFilesList(SYS.'languages');
        foreach ($langs as $lng) {
            self::$languages[] = basename($lng, '.php');
        }
        include_once(SYS.'languages'.DS.self::$language.'.php');
        self::$language = $LANG['language'];
        self::$locale   = $LANG['locale'];
        $cookie = time() + 3600 * 24 * 365 * 5;
        setcookie($cookie_lang, self::$language, $cookie);
        # Check if it is allowed to change website skin and set user or default skin.
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
            /** User defined skin. */
            define('CURRENT_SKIN', SKINS.self::$skin.DS);
        }
    }

    /** Set system parameter.
     * @param  string $param System parameter.
     * @param  string $value Value of the system parameter.
     * @return void
     */
    public static function set($param, $value) {
        self::$$param = $value;
    }

    /** Get system parameter.
     * @param  string $param System parameter.
     * @return string        Value of the requested parameter.
     */
    public static function get($param) {
        return self::$$param;
    }

    /** Modules initialization.
     * @param  boolean $ignore_disabled Init all existing modules.
     * @return void
     * @uses array $LANG Website translations.
     */
    public function initModules($ignore_disabled = FALSE) {
        global $LANG;
        $enabled = CONFIG::getSection('enabled');
        if (empty($enabled) || $ignore_disabled) {
            $enabled = array_flip(GetFilesList(MODULES));
        }
        $included = [];
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
     * - box (for panels or boxes);
     * - plugin (cannot be showed on any page).
     * @param  string $module Module name.
     * @param  string $title  Module title.
     * @param  string $type   Module type.
     * @param  string $system
     * @return void
     */
    public static function registerModule($module, $title, $type, $system = '') {
        self::$modules[$module]['title']  = __($title);
        self::$modules[$module]['type']   = $type;
        self::$modules[$module]['system'] = $system;
    }

    /** Register RSS feed for module.
     * RSS feed ID is looks like "module@section".
     * @param  string $section RSS feed ID.
     * @param  string $title   RSS feed title.
     * @param  string $desc    RSS feed description.
     * @param  string $module  Module name.
     * @return void
     */
    public static function registerFeed($section, $title, $desc, $module = '') {
        self::$feeds[$section] = array(__($title), __($desc), $module);
    }

    /** Register module for menu link.
     * @param  string $module Module name.
     * @return void
     */
    public static function registerMainMenu($module) {
        self::$menu[] = $module;
    }

    /** Register module for use in sitemap.
     * @param  string $module Module name.
     * @return void
     */
    public static function registerSiteMap($module) {
        self::$sitemap[] = $module;
    }

    /** Register module for search requests.
     * @param  string $module Module name.
     * @return void
     */
    public static function registerSearch($module) {
        self::$search[] = $module;
    }

    /** Register module for menu link.
     * @param  string $name Skin name.
     * @param  string $skin Skin template.
     * @return void
     */
    public static function registerSkin($name, $skin) {
        self::$skins[$name] = $skin;
    }

    /** Set the point for output.
     * @param type $point Output point name.
     * @return void
     */
    public static function setCurrentPoint($point) {
        self::$current_point = $point;
    }

    /**
    * @todo Comment
    * @param string $title	...
    * @param string $content	...
    * @param string $align	... (défaut : 'left')
    * @return 
    */
    public static function defineWindow($title, $content, $align = 'left') {
        if (self::$current_point == '__MAIN__') {
            self::$output['main'][] = [$title, $content, $align];
        } else {
            self::$output[self::$current_point][] = [$title, $content, $align];
        }
    }

    /** Show window.
     * @param  string $title    The title of the output data.
     * @param  string $content  The content for use in output.
     * @param  string $align    Page data alignment.
     * @param  string $template Name of the template for use in output.
     * @return string           Website page.
     */
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
     * If some words has been set in website configuration (global keywords) the $keywords will be added to them.
     * @param  string $keywords Page keywords.
     * @return void
     */
    public static function setPageKeywords($keywords) {
        self::$meta['keywords'] = empty(self::$meta['keywords']) ? $keywords : self::$meta['keywords'].','.$keywords;
    }

    /** Set description for requested website page.
     * This description will be used in meta tag.
     * @param  string $desc Page description.
     * @return void
     */
    public static function setPageDescription($desc) {
        self::$meta['desc'] = $desc;
    }

    /** Get website navigation points.
     * If navigation is not exists it will be created.
     * @return array Website navigation points.
     */
    public function getNavigations() {
        if (empty(self::$navigation)) {
            return self::createNavigation();
        }
        return self::$navigation;
    }

    /** Create site navigation.
     * @return array Website navigation points.
     */
    public static function getNavigation() {
        if (empty(self::$navigation)) {
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
                                        self::$navigation[] = [
                                            'module' => $module,
                                            'link'   => $sections[$data[0]]['link'],
                                            'name'   => empty($link[1]) ? $sections[$data[0]]['title'] : __($link[1]),
                                            'desc'   => empty($link[2]) ? (empty($sections[$data[0]]['desc']) ? '' : $sections[$data[0]]['desc']) : __($link[2]),
                                            'icon'   => ICONS.$link[3]
                                        ];
                                    }
                                    break;

                                case 2:
                                    $categories = CMS::call($obj)->getCategories($data[0]);
                                    if (!empty($categories[$data[1]])) {
                                        self::$navigation[] = [
                                            'module' => $module,
                                            'link'   => MODULE.$module.SECTION.$data[0].CATEGORY.$data[1],
                                            'name'   => $categories[$data[1]]['title'],
                                            'desc'   => empty($link[2]) ? (empty($categories[$data[1]]['desc']) ? '' : $categories[$data[1]]['desc']) : __($link[2]),
                                            'icon'   => ICONS.$link[3]
                                        ];
                                        }
                                        break;

                                case 3:
                                    $categories = CMS::call($obj)->getCategories($data[0]);
                                    if (!empty($categories)) {
                                        $content = CMS::call($obj)->getContent($data[1]);
                                        if (!empty($content[$data[2]])) {
                                            self::$navigation[] = [
                                                'module' => $module,
                                                'link'   => MODULE.$module.SECTION.$data[0].CATEGORY.$data[1].ITEM.$data[2],
                                                'name'   => $content[$data[2]]['title'],
                                                'desc'   => empty($link[2]) ? '' : __($link[2]),
                                                'icon'   => ICONS.$link[3]
                                            ];
                                        }
                                    }
                                break;
                            }
                        }
                    }
                } else {
                    self::$navigation[] = [
                        'link' => MODULE.$module,
                        'name' => empty($link[1]) ? self::$modules[$module]['title'] : __($link[1]),
                        'desc' => empty($link[2]) ? '' : __($link[2]),
                        'icon' => ICONS.$link[3]
                    ];
                }
            }
        }
        return self::$navigation;
    }

    /** Create main menu for website.
     * The menu will be created only for registered and enabled modules.
     * @return boolean The result of operation.
     */
    public function createMainMenu() {
        $enabled = CONFIG::getSection('enabled');
        $menu  = [];
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
                $point = [];
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
