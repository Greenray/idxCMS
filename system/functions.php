<?php
/**
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/functions.php
 * @package   Core
 */

# FILES and DIRECTORIES

/**
 * Gets list of files from directory.
 *
 * @param  string  $directory Directory for parsing
 * @param  string  $mask      Files mask
 * @param  string  $type      Files type: file or directory
 * @param  boolean $filter    Files filter
 * @param  array   $except    List if files which will be excluded from result
 * @return array              List of files
 */
function AdvScanDir($directory, $mask = '', $type = 'all', $filter = FALSE, $except = []) {
    $exc     = ['.', '..', '.htaccess', 'index.html'];
    $exclude = array_unique(array_merge($exc, $except));
    $dir     = [];

    if (!empty($mask)) {
        $mask = '/^'.str_replace('*', '(.*)', str_replace('.', '\\.', $mask)).DS;
    }
    if (!empty($type) && $type !== 'all') {
        $func = 'is_'.$type;
    }
    if (is_dir($directory)) {
        $fh = opendir($directory);
        while (($filename = readdir($fh)) !== FALSE) {
            if (substr($filename, 0, 1) !== '.' || $filter) {
                if (!in_array($filename, $exclude)) {
                    if ((empty($type) || ($type === 'all') || $func($directory.DS.$filename)) &&
                        (empty($mask) || preg_match($mask, $filename))) {
                            $dir[] = $filename;
                    }
                }
            }
        }
        closedir($fh);
        sort($dir);
    }
    return $dir;
}

/**
 * Removes files and directories recursively.
 *
 * @param  string  $object    Directory to remove
 * @param  boolean $recursive Remove recursively? (Default : TRUE)
 * @return boolean            The result of operation
 */
function DeleteTree($object, $recursive = TRUE) {
    if ($recursive && is_dir($object)) {
        $els = GetFilesList($object);
        foreach ($els as $el) {
            DeleteTree($object.DS.$el, $recursive);
        }
    }
    return (is_dir($object)) ? rmdir($object) : unlink($object);
}

/**
 * Gets the list of files from the specified directory.
 *
 * @param  string $directory Name of the directory
 * @param  array  $except	 The list of files to exclude from the result
 * @return array             The list of files from the specified directory
 */
function GetFilesList($directory, $except = []) {
    $exclude = array_unique(array_merge(['.', '..', '.htaccess', 'index.html'], $except));
    $result  = [];
    $list    = scandir($directory);
    foreach ($list as $filename) {
        if (!in_array($filename, $exclude)) {
            $result[] = $filename;
        }
    }
    return $result;
}

/**
 * Gets content of gziped file.
 *
 * @param  string $file Name of the file
 * @return mixed        The content of file or FALSE
 */
function gzfile_get_contents($file) {
    if (!$file = gzfile($file)) {
        return FALSE;
    }
    if (!$file = implode('', $file)) {
        return FALSE;
    }
    return $file;
}

/**
 * Writes data to gziped file.
 *
 * @param  string  $file Filename
 * @param  string  $text Data to gzip
 * @param  string  $mode Write mode (Default : 'w+')
 * @return boolean       The result of operation
 */
function gzfile_put_contents($file, $text, $mode = 'w+') {
    if (($fp = @fopen($file.'.lock', 'w+')) === FALSE) {
        return FALSE;
    }
    fwrite($fp, 'lock');
    fclose($fp);
    if (($fp = gzopen($file, $mode)) === FALSE) {
        return FALSE;
    }
    if (!empty($text) && !gzwrite($fp, $text)) {
        gzclose($fp);
        return FALSE;
    }
    gzclose($fp);
    unlink($file.'.lock');
    return TRUE;
}

# TEXT

/**
 * String localization.
 * Currently, the system supports foure languages: English, Russian, Belarusian, and Ukrainian.
 *
 * @global array  $LANG   Array of language strings
 * @param  string $string String to be translated
 * @return string         Nhfyslated string
 */
function __($string) {
    global $LANG;
    return empty($LANG['def'][$string]) ? $string : $LANG['def'][$string];
}

/**
 * Checks if the string has onle latin symbols.
 *
 * @param  string $string String to check
 * @return string|boolean Checked string or FALSE if it has not only latin symbols
 */
function OnlyLatin($string) {
    return (empty($string) || preg_replace("/[\d\w]+/i", '', $string) != '') ? FALSE : $string;
}

/**
 * Generates random string.
 *
 * @param  integer $num_chars The lenght of string to generate
 * @return string             Generated string
 */
function RandomString($num_chars) {
    $chars = [
        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
        '0','1','2','3','4','5','6','7','8','9','_'
    ];
    $max_chars = sizeof($chars) - 1;
    $result = '';
    for ($i = 0; $i < $num_chars; $i++) {
        $result .= $chars[mt_rand(0, $max_chars)];
    }
    return $result;
}

/**
 * Converts line endings.
 *
 * @param  string $text Text to parse
 * @return string       Parsed text
 */
function UnifyBr($text) {
    return str_replace(["\r\n", "\n\r", "\r"], LF, $text);
}

# DATE and TIME

/**
 * Formats date and time according to user's timezone.
 *
 * @param  string $format Format
 * @param  string $date	  Date
 * @return string         Formatted date/time
 */
function FormatTime($format, $date) {
    global $LANG;
    $translate = [];
    $locale = SYSTEM::get('locale');
    if ($locale !== 'en') {
        $datetime = 'datetime'.$locale;
        foreach($LANG[$datetime] as $match => $replace) {
            $translate[$match] = $replace;
        }
    }
    $tz = USER::getUser('tz');
    return empty($translate) ? gmdate($format, $date + (3600 * $tz)) : strtr(gmdate($format, $date + (3600 * $tz)), $translate);
}

# MAIL

/**
 * Sends email.
 *
 * @param  string  $to     The recipient
 * @param  string  $from   Sender address
 * @param  string  $sender The sender
 * @param  string  $subj   Subject
 * @param  string  $text   Text
 * @return boolean         The result of email sending
 */
function SendMail($to, $from, $sender, $subj, $text) {
    $headers  = 'From: '.$sender.' <'.$from.'>'.LF;
    $headers .= "MIME-Version: 1.0\n";
    $headers .= 'Message-ID: <'.md5(uniqid(time()))."@".$sender.'>'.LF;
    $headers .= 'Date: '.gmdate('D, d M Y H:i:s T', time()).LF;
    $headers .= "Content-type: text/plain; charset=UTF-8".LF;
    $headers .= "Content-transfer-encoding: 8bit".LF;
    $headers .= "X-Mailer: idxCMS".LF;
    $headers .= "X-MimeOLE: idxCMS".LF;
    return mail($to, $subj, $text, $headers);
}

# PAGINATION

/**
 * Formats data for pagination.
 *
 * @param  integer $total   Pages amount
 * @param  integer $current Current page
 * @param  integer $last    Last page
 * @return array            Formatted data for pagination
 */
function AdvancedPagination($total, $current, $last) {
    $pages = [];
    if ($current < 1) {
        $current = 1;
    } elseif ($current > $last) {
        $current = $last;
    }
    $pages['current']  = $current;
    $pages['previous'] = ($current == 1)     ? $current : $current - 1;
    $pages['next']     = ($current == $last) ? $last    : $current + 1;
    $pages['last']     = $last;
    $pages['pages']    = [];
    $show = 5;                  # Number of page links to show
    #
    # At the beginning
    #
    if ($current == 1) {
        if ($pages['next'] == $current) {
            return $pages;      # if one page only
        }
        for ($i = 0; $i < $show; $i++) {
            if ($i == $last) {
                break;
            }
            array_push($pages['pages'], $i + 1);
        }
        return $pages;
    }
    #
    # At the end
    #
    if ($current == $last) {
        $start = $last - $show;
        if ($start < 1) {
            $start = 0;
        }
        for ($i = $start; $i < $last; $i++) {
            array_push($pages['pages'], $i + 1);
        }
        return $pages;
    }
    #
    # In the middle
    #
    $start = $current - $show;
    if (($total > 5) && ($current > 3)) $start = $current - 3;
    if (($last - $current) < 2)         $start = $current - 4;
    if ($start < 1)                     $start = 0;
    for ($i = $start; $i < $current; $i++) {
        array_push($pages['pages'], $i + 1);
    }
    for ($i = ($current + 1); $i < ($current + $show); $i++) {
        if ($i == ($last + 1)) {
            break;
        }
        array_push($pages['pages'], $i);
    }
    return $pages;
}

/**
 * Creates html block for pages navigation.
 *
 * @param  integer $total Total items
 * @param  integer $perpage Items per page
 * @param  integer $current Current page number
 * @param  string  $link    Link to item
 * @return string           html block for pages navigation
 * @todo   Crate the template for pagination
 */
function Pagination($total, $perpage, $current, $link) {
    $result   = '';
    $numpages = ceil($total / $perpage);
    $pages    = AdvancedPagination($total, $current, $numpages);
    if ($numpages > 1) {
        $result .= '<div id="pagination">
                        <span class="pages">'.__('Pages').': </span>';
        if ($pages['current'] != $pages['previous'])
             $result .= '<a href="'.$link.'&page=1"> &lt;</a>';
        else $result .= '<span class="pages"> &lt; </span>';
        $count = ($numpages > 5) ? 5 : $numpages;
        for ($i = 0; $i < $count; $i++) {
            if ($pages['pages'][$i] != $pages['current']) {
                $result .= '<a href="'.$link.'&page='.$pages['pages'][$i].'">'.$pages['pages'][$i].'</a>';
            } else {
                $result .= '<span class="active"> '.$pages['current'].'</span>';
            }
        }
        if ($pages['current'] != $pages['next']) {
            $result .= '<a href="'.$link.'&page='.$pages['last'].'"> &gt; </a><span class="pages">['.__('Total').': '.$pages['last'].']</span>';
        } else {
            $result .= '<span class="pages"> &gt; ['.__('Total').': '.$pages['last'].']</span>';
        }
        $result .= '</div>
                    <div class="clear"></div>';
    }
    return $result;
}

/**
 * Culculates parameters for the pagination
 *
 * @param  integer $page    Number of the current page
 * @param  integer $perpage Items per page
 * @param  integer $count   Number of items
 * @return array            Parameters for the pagination
 */
function GetPagination($page, $perpage, $count) {
    $result = [];
    $result['page']  = $page > 0 ? $page - 1 : 0;
    $result['total'] = $count > $perpage ? $perpage : $count;
    $result['start'] = $result['page'] * $perpage;
    $last = $result['total'] + $result['start'];
    $result['last']  = $last > $count ? $count : $last;
    return $result;
}

/**
 * Selects the time zone.
 *
 * @param  string $name    Time zone
 * @param  array  $points  List of time zones
 * @param  string $default The default time zone
 * @return string          Form to select time zone
 */
function SelectTimeZone($name, $points, $default) {
    $result = '<select name="'.$name.'">';
    foreach ($points as $id => $point) {
        $result .= '<option value="'.$id.'"'.(($default == $id) ? ' selected>' : '>').$point.'</option>';
    }
    $result .= '</select>';
    return $result;
}

/**
 * Shows captcha.
 * There are three different options:
 * - original: black an white;
 * - color: with colored background;
 * - random selection.
 * The length of the captcha code also varies randomly from five to eight letters and numbers.
 * Captcha is displayed only for unregistered users.
 *
 * @param  string $param Type of captha
 * @return string        Captcha image and input field for captcha code
 */
function ShowCaptcha($param = '') {
    if (USER::$logged_in) {
        return '';
    }
    $captcha = empty($param) ? CONFIG::getValue('main', 'captcha') : $param;
    $code    = mt_rand(0, 666);
    if ($captcha === 'Random') {
        $types   = ['Original', 'Color'];
        $captcha = $types[mt_rand(0, 1)]; # Change system CAPTCHA
        $result  = ShowCaptcha($captcha);
        $captcha = 'Random';              # Restore system CAPTCHA
        return $result;
    }
    return '
        <img src="'.TOOLS.'captcha.php?code='.$code.'" width="90" height="30" alt="CAPTCHA" /><br />
        <input type="hidden" name="antispam" value="'.$code.'" />
        <input type="text" name="captcheckout" id="captcheckout" value="" size="10" class="required" />
    ';
}

/**
 * Checks captcha code.
 *
 * @throws Exeption "Invalid captcha code"
 * @return boolean The result of operation
 */
function CheckCaptcha() {
    if (USER::$logged_in) {
        return TRUE;
    }
    if (!empty($_SESSION['code_length'])) {
        $antispam = substr(md5(FILTER::get('REQUEST', 'antispam')), 0, $_SESSION['code_length']);
        unset($_SESSION['code_length']);
        if ($antispam === FILTER::get('REQUEST', 'captcheckout')) {
            return TRUE;
        }
    }
    throw new Exception('Invalid captcha code');
}

/**
 * Redirects to the specified page.
 *
 * @param  string  $module   Module name
 * @param  string  $section  Section name (Default : NULL)
 * @param  integer $category Category ID  (Default : NULL)
 * @param  integer $item     Item ID      (Default : NULL)
 * @param  integer $page     Page         (Default : NULL)
 * @param  integer $comment  Comment ID   (Default : NULL)
 */
function Redirect($module, $section = NULL, $category = NULL, $post = NULL, $comment = NULL, $page = NULL) {
    $url = CreateUrl($module, $section, $category, $post, $comment, $page);
    header('Location: '.$url);
    die();
}

/**
 * Creates url to the specified page.
 *
 * @param  string  $module   Module name
 * @param  string  $section  Section name
 * @param  integer $category Category ID
 * @param  integer $item     Item ID
 * @param  integer $comment  Comment ID
 * @param  integer $page     Page
 */
function CreateUrl($module, $section = '', $category = '', $item = '', $comment = '', $page = '') {
    $url = MODULE.$module;
    if (!empty($section)) {
        $url = $url.SECTION.$section;
        if (!empty($category)) {
            $url = $url.CATEGORY.$category;
            if (!empty($item)) {
                $url = $url.ITEM.$item;
                if (!empty($comment)) {
                    $url = $url.COMMENT.$comment;
                    if (!empty($page)) {
                        $url = $url.PAGE.$comment;
                    }
                }
            }
        }
    }
    return $url;
}

/**
 * Creates sitemap.
 *
 * return boolean The result of operation
 */
function Sitemap() {
    $time     = FormatTime('Y-m-d', time());
    $url      = SYSTEM::get('url');
    $modules  = SYSTEM::get('modules');
    $enabled  = CONFIG::getSection('enabled');
    $sitemap  = SYSTEM::get('sitemap');
    $site_map =
    "<\x3Fxml version=\"1.0\" encoding=\"UTF-8\"\x3F>".LF.
    "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">".LF.
    "\t<url>".LF.
    "\t\t<loc>".$url."?module=index</loc>".LF.
    "\t\t<lastmod>".$time."</lastmod>".LF.
    "\t\t<changefreq>weekly</changefreq>".LF.
    "\t\t<priority>0.8</priority>".LF.
    "\t</url>".LF;
    foreach ($modules as $module => $data) {
        if (in_array($module, $sitemap) && array_key_exists($module, $enabled)) {
            $gsm = '';
            $obj = strtoupper($module);
            $sections = CMS::call($obj)->getSections();
            unset($sections['drafts']);

            foreach ($sections as $id => $section) {
                $categories = CMS::call($obj)->getCategories($id);
                if (!empty($categories)) {
                    $gsm .=
                    "\t<url>".LF.
                    "\t\t<loc>".$url."?module=".$module.SECTION.$id."</loc>".LF.
                    "\t\t<lastmod>".$time."</lastmod>".LF.
                    "\t\t<changefreq>weekly</changefreq>".LF.
                    "\t</url>".LF;
                    foreach ($categories as $key => $category) {
                        $category = CMS::call($obj)->getCategory($key);
                        $content  = CMS::call($obj)->getContent($key);
                        if (!empty($content)) {
                            $gsm .=
                            "\t<url>".LF.
                            "\t\t<loc>".$url."?module=".$module.SECTION.$id.CATEGORY.$key."</loc>".LF.
                            "\t\t<lastmod>".$time."</lastmod>".LF.
                            "\t\t<changefreq>weekly</changefreq>".LF.
                            "\t</url>".LF;
                            foreach ($content as $i => $item) {
                                $gsm .=
                                "\t<url>".LF.
                                "\t\t<loc>".$url."?module=".$module.SECTION.$id.CATEGORY.$key.ITEM.$i."</loc>".LF.
                                "\t\t<lastmod>".FormatTime('Y-m-d', $item['time'])."</lastmod>".LF.
                                "\t</url>".LF;
                            }
                        }
                    }
                }
            }
            if (!empty($gsm)) {
                $site_map .=
                "\t<url>".LF.
                "\t\t<loc>".$url."?module=".$module."</loc>".LF.
                "\t\t<lastmod>".$time."</lastmod>".LF.
                "\t\t<changefreq>weekly</changefreq>".LF.
                "\t</url>".LF.$gsm;
            }
        }
    }
    $site_map .= "</urlset>";
    if (!file_put_contents(ROOT.'sitemap.xml', $site_map, LOCK_EX)) {
        CMS::call('LOG')->logPut('Error', '', 'Cannot save file sitemap.xml');
    }
    return CMS::call('SYSTEM')->createMainMenu();
}
