<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
# COMMON FUNCTONS

# FILES and DIRECTORIES

function AdvScanDir($directory, $mask = '', $type = 'all', $filter = FALSE, $except = array()) {
    $exc = array('.', '..', '.htaccess', 'index.html');
    $exclude = array_unique(array_merge($exc, $except));
    $dir = array();
    if (!empty($mask)) {
        $mask = '/^'.str_replace('*', '(.*)', str_replace('.', '\\.', $mask)).DS;
    }
    if (!empty($type) && $type !== 'all') {
        $func = 'is_'.$type;
    }
    if (is_dir($directory)) {
        $fh = opendir($directory);
        while (FALSE !== ($filename = readdir($fh))) {
            if (substr($filename, 0, 1) != '.' || $filter) {
                if (!in_array($filename, $exclude)) {
                    if ((empty($type) || ($type == 'all') || $func($directory.DS.$filename)) &&
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

function GetFilesList($directory, $except = array()) {
    $exclude = array_unique(array_merge(array('.', '..', '.htaccess', 'index.html'), $except));
    $result  = array();
    $list = scandir($directory);
    foreach ($list as $filename) {
        if (!in_array($filename, $exclude)) {
            $result[] = $filename;
        }
    }
    return $result;
}

# Recursively copy a folder and its contents
function CopyTree($source, $dest) {
    if (is_file($source)) return copy($source, $dest);
    if (!is_dir($dest)) {
        mkdir($dest, 0777);
        chmod($dest, 0777);
    }
    $dir = dir($source);
    while (($element = $dir->read()) !== FALSE) {
        if (($element == '.') || ($element == '..'))
            continue;

        CopyTree($source.DS.$element, $dest.DS.$element);
    }
    $dir->close();
    return TRUE;
}

# Remove files and directories
function DeleteTree($object, $recursive = TRUE) {
    if ($recursive && is_dir($object)) {
        $els = GetFilesList($object);
        foreach ($els as $el) {
            DeleteTree($object.DS.$el, TRUE);
        }
    }
    return (is_dir($object)) ? rmdir($object) : unlink($object);
}

# Get content of gziped file
function gzfile_get_contents($file) {
    if (!$file = gzfile($file))      return FALSE;
    if (!$file = implode('', $file)) return FALSE;
    return $file;
}

# Write data to gziped file
function gzfile_put_contents($file, $text, $mode = 'w+') {
    if (($fp = @fopen($file.'.lock', 'w+')) === FALSE) return FALSE;
    fwrite($fp, 'lock');
    fclose($fp);
    if (($fp = gzopen($file, $mode)) === FALSE) return FALSE;
    if (!empty($text) && !gzwrite($fp, $text)) {
        gzclose($fp);
        return FALSE;
    }
    gzclose($fp);
    unlink($file.'.lock');
    return TRUE;
}

# Get unserialized data
function GetUnserialized($file) {
    $data = array();
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if ($content !== FALSE) {
            $data = @unserialize($content);
            if ($data === FALSE) {
                $data = UnifyBr($content);
                $data = preg_replace("!s:(\d+):\"(.*?)\";!se", "'s:'.strlen('$2').':\"$2\";'", $data);
                $data = @unserialize($data);
                if ($data === FALSE)
                     $data = array();
                else file_put_contents($file, serialize($data), LOCK_EX);
            }
        }
    }
    return $data;
}

# ARRAY

# Recursive search of the needed key value in a multidimensional array.
function SearchInArray($needle, $haystack) {
    $result = '';
    foreach ($haystack as $key => $value) {
        if ($needle == $key) {
            $result = $value;
        }
        if (is_array($value)) {
            $result = SearchInArray($needle, $value);
        }
    }
    return $result;
}

# ArraySort callback
function ArraySortFunc($a, $b = NULL) {
    static $keys;
    if ($b === NULL) {
        return $keys = $a;
    }
    foreach ($keys as $key) {
        if (@$key[0] == '!') {
            $key = substr($key, 1);
            if (@$a[$key] !== @$b[$key])
                return strcmp(@$b[$key], @$a[$key]);

        } elseif (@$a[$key] !== @$b[$key])
            return strcmp(@$a[$key], @$b[$key]);

    }
    return FALSE;
}

function ArraySort(&$array) {
    $keys = array();
    if (!$array) return $keys;
    $keys = func_get_args();
    array_shift($keys);
    ArraySortFunc($keys);
    uasort($array, "ArraySortFunc");
}

function RandomString($num_chars) {
    $chars = array(
        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
        '0','1','2','3','4','5','6','7','8','9','_'
    );
    $max_chars = sizeof($chars) - 1;
    $result = '';
    for ($i = 0; $i < $num_chars; $i++) {
        $result .= $chars[mt_rand(0, $max_chars)];
    }
    return $result;
}

# TEXT

# String localization
function __($string) {
    global $LANG;
    return empty($LANG['def'][$string]) ? $string : $LANG['def'][$string];
}

function CutText($text, $length) {
    if ((mb_strlen($text, 'UTF-8') - 1) < $length)
        return $text;

    if (mb_strpos($text, '.', $length))
        return mb_substr($text, 0, $length, 'UTF-8').'...';
}

function UnifyBr($text) {
    return str_replace(array("\r\n", "\n\r", "\r"), LF, $text);
}

function OnlyLatin($string) {
    if (empty($string) || preg_replace("/[\d\w]+/i", '', $string) != '')
        return FALSE;

    return $string;
}

# DATE and TIME

function FormatTime($format, $gmepoch) {
    global $LANG;
    $translate = array();
    $locale = SYSTEM::get('locale');
    if ($locale !== 'en') {
        $datetime = 'datetime'.$locale;
        foreach($LANG[$datetime] as $match => $replace) {
            $translate[$match] = $replace;
        }
    }
    $tz = USER::getUser('tz');
    return empty($translate) ? gmdate($format, $gmepoch + (3600 * $tz)) : strtr(gmdate($format, $gmepoch + (3600 * $tz)), $translate);
}

# Return localised date from string generated by date()
function LocaliseDate($string) {
    global $LANG;
    $translate = array();
    if ($LANG['language'] !== 'english') {
        foreach($LANG['datetime'] as $match => $replace) {
            $translate[$match] = $replace;
        }
    }
    return empty($translate) ? $string : strtr($string, $translate);
}

# MAIL

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

function AdvancedPagination($total, $current, $last) {
    $pages   = array();
    if     ($current < 1)     $current = 1;
    elseif ($current > $last) $current = $last;
    $pages['current']  = $current;
    $pages['previous'] = ($current == 1)     ? $current : $current - 1;
    $pages['next']     = ($current == $last) ? $last    : $current + 1;
    $pages['last']     = $last;
    $pages['pages']    = array();
    $show = 5;                        # Number of page links to show
    # At the beginning
    if ($current == 1) {
        if ($pages['next'] == $current)
            return $pages;   # if one page only

        for ($i = 0; $i < $show; $i++) {
            if ($i == $last)
                break;

            array_push($pages['pages'], $i + 1);
        }
        return $pages;
    }
    # At the end
    if ($current == $last) {
        $start = $last - $show;
        if ($start < 1) $start = 0;
        for ($i = $start; $i < $last; $i++) {
            array_push($pages['pages'], $i + 1);
        }
        return $pages;
    }
    # In the middle
    $start = $current - $show;
    if (($total > 5) && ($current > 3)) $start = $current - 3;
    if (($last - $current) < 2)         $start = $current - 4;
    if ($start < 1)                     $start = 0;
    for ($i = $start; $i < $current; $i++) {
        array_push($pages['pages'], $i + 1);
    }
    for ($i = ($current + 1); $i < ($current + $show); $i++) {
        if ($i == ($last + 1))
            break;

        array_push($pages['pages'], $i);
    }
    return $pages;
}

function Pagination($total, $perpage, $current, $link) {
    $result   = '';
    $numpages = ceil($total / $perpage);
    $pages    = AdvancedPagination($total, $current, $numpages);
    if ($numpages > 1) {
        $result .= '<div id="pagination">
                        <span class="pages">'.__('Pages').': </span>';
        if ($pages['current'] != $pages['previous'])
             $result .= '<a href="'.$link.'&amp;page=1"> &lt;</a>';
        else $result .= '<span class="pages"> &lt; </span>';
        $count = ($numpages > 5) ? 5 : $numpages;
        for ($i = 0; $i < $count; $i++) {
            if ($pages['pages'][$i] != $pages['current'])
                 $result .= '<a href="'.$link.'&amp;page='.$pages['pages'][$i].'">'.$pages['pages'][$i].'</a>';
            else $result .= '<span class="active"> '.$pages['current'].'</span>';
        }
        if ($pages['current'] != $pages['next'])
             $result .= '<a href="'.$link.'&amp;page='.$pages['last'].'"> &gt; </a><span class="pages">['.__('Total').': '.$pages['last'].']</span>';
        else $result .= '<span class="pages"> &gt; ['.__('Total').': '.$pages['last'].']</span>';
        $result .= '</div>
                    <div class="clear"></div>';
    }
    return $result;
}

function GetPagination($page, $perpage, $count) {
    $result = array();
    $result['page']  = $page > 0 ? $page - 1 : 0;
    $result['total'] = $count > $perpage ? $perpage : $count;
    $result['start'] = $result['page'] * $perpage;
    $last = $result['total'] + $result['start'];
    $result['last']  = $last > $count ? $count : $last;
    return $result;
}

function SelectTimeZone($name, $points, $default) {
    $result = '<select name="'.$name.'">';
    foreach ($points as $id => $point) {
        $result .= '<option value="'.$id.'"'.(($default == $id) ? ' selected="selected">' : '>').$point.'</option>';
    }
    $result .= '</select>';
    return $result;
}

function ShowCaptcha($param = '') {
    if (USER::loggedIn()) return '';
    $captcha = empty($param) ? CONFIG::getValue('main', 'captcha') : $param;
    $code = mt_rand(0, 666);
    if ($captcha === 'Random') {
        $types   = array('Original', 'Color');
        $captcha = $types[mt_rand(0, 1)]; # Change system CAPTCHA
        $result  = ShowCaptcha($captcha);
        $captcha = 'Random';              # Restore system CAPTCHA
        return $result;
    }
    return '<img src="'.TOOLS.'captcha.php?code='.$code.'" hspace="5" vspace="5" width="90" height="30" alt="CAPTCHA" /><br />
            <input type="hidden" name="antispam" value="'.$code.'" />
            <input type="text" name="captcheckout" id="captcheckout" value="" size="10" />';
}

function CheckCaptcha() {
    if (USER::loggedIn()) return TRUE;
    if (!empty($_SESSION['code-length'])) {
        $antispam = substr(md5(FILTER::get('REQUEST', 'antispam')), 0, $_SESSION['code-length']);
        unset($_SESSION['code-length']);
        if ($antispam === FILTER::get('REQUEST', 'captcheckout'))
            return TRUE;
    }
    throw new Exception('Invalid captcha code');
}

function ShowElement($element, $parameters = '') {
    $output = '';
    switch($element) {
        case 'point':
            list($point, $template) = explode('@', $parameters);
            if (!empty(SYSTEM::$output[$point])) {
                foreach (SYSTEM::$output[$point] as $i => $module) {
                    $output .= CMS::call('SYSTEM')->showWindow(
                        $module[0],
                        $module[1],
                        $module[2],
                        $template
                    );
                }
            }
            if (empty(SYSTEM::$output[$point]) && ($point !== 'up-center') && ($point !== 'down-center')) {
                if (!empty(SYSTEM::$output['left'])) {
                    foreach (SYSTEM::$output['left'] as $i => $module) {
                        $output .= CMS::call('SYSTEM')->showWindow(
                            $module[0],
                            $module[1],
                            $module[2],
                            $template
                        );
                    }
                }
            }
            return $output;
            break;
        case 'box':
            list($module, $template) = explode('@', $parameters);
            if (!empty(SYSTEM::$output['boxes'])) {
                foreach (SYSTEM::$output['boxes'] as $i => $box) {
                    if ($box[0] === SYSTEM::$modules[$module]['title']) {
                        $output .= CMS::call('SYSTEM')->showWindow(
                            $box[0],
                            $box[1],
                            $box[2],
                            $template
                        );
                    }
                }
            }
            return $output;
            break;
        case 'main':
            if (!empty(SYSTEM::$output['main'])) {
                foreach (SYSTEM::$output['main'] as $module) {
                    $output .= CMS::call('SYSTEM')->showWindow(
                        $module[0],
                        $module[1],
                        $module[2],
                        substr(strstr($parameters, '@'), 1)
                    );
                }
            }
            return $output;
            break;
        case 'title':
            $title = CONFIG::getValue('main', 'title');
            $pagename = SYSTEM::get('pagename');
            if (!empty($pagename)) {
                $title = $title.' - '.$pagename;
            }
            return $title;
        break;
        case 'meta':
            $output = '';
            $meta = SYSTEM::get('meta');
            $desc = CONFIG::getValue('main', 'description');
            if (!empty($meta['desc'])) {
                $desc .= ' - '.$meta['desc'];
            }
            $output .= '<meta name="description" content="'.$desc.'" />'.LF;
            $keywords = CONFIG::getValue('main', 'keywords');
            if (!empty($meta['keywords'])) {
                $words    = $keywords.','.$meta['keywords'];
                $keywords = explode(',', $words);
                $words    = array_unique($keywords);
                $keywords = implode(',', $words);
            }
            $output .= '<meta name="keywords" content="'.$keywords.'" />'.LF;
            $output .= file_get_contents(CONTENT.'meta');
            if (CONFIG::getValue('enabled', 'rss')) {
                $feeds = SYSTEM::get('feeds');
                foreach ($feeds as $module => $d) {
                    $output .= '<link href="'.MODULE.'rss&m='.$module.'" rel="alternate" type="application/xml" title="RSS '.$d[0].'" />'.LF;
                }
            }
            return $output;
            break;
        case 'copyright':
            if ($parameters) {
                $TPL = new TEMPLATE('copyright.tpl');
                return CMS::call('SYSTEM')->showWindow('__NOWINDOW__', $TPL->parse());
            } else return IDX_POWERED.'<br />'.IDX_COPYRIGHT;
            break;
        case 'error':
            $error = '';
            if (file_exists(LOGS.'error.log')) {
                $errors = file(LOGS.'error.log', FILE_IGNORE_NEW_LINES);
                foreach($errors as $message) {
                    $error .= $message.'<br />';
                }
                unlink(LOGS.'error.log');
            }
            if (!empty($error)) {
                return CMS::call('SYSTEM')->showWindow(__('Error'), $error, 'center', 'error');
            }
        break;
    }
}

function Redirect($module, $section = '', $category = '', $post = '', $comment = '') {
    $url = MODULE.$module;
    if (!empty($section)) {
        $url = $url.'&section='.$section;
        if (!empty($category)) {
            $url = $url.'&category='.$category;
            if (!empty($post)) {
                $url = $url.'&post='.$post;
                if (!empty($comment)) {
                    $url = $url.'&comment='.$comment;
                }
            }
        }
    }
    header('Location: '.$url);
    die();
}

# bbCodes panel code for selected textarea
function ShowBbcodesPanel($textarea, $moderator = FALSE, $dir = '') {
    $area = explode('.', $textarea);
    $smiles = GetFilesList(SMILES);
    foreach ($smiles as $smile) {
        $names[] = basename($smile, '.gif');
    }
    $clrs = array('00', '33', '66', '99', 'cc', 'ff');
    $colors = array();
    $n = 0;
    for ($i = 0; $i < 6; $i++) {
        for ($j = 0; $j < 6; $j++) {
            for ($k = 0; $k < 6; $k++) {
                $colors[$i]['colors'][$n]['color'] = $clrs[$i].$clrs[$j].$clrs[$k];
                ++$n;
            }
        }
    }
    $TPL = new TEMPLATE(SYS.'templates'.DS.'bbcodes-panel.tpl');
    return $TPL->parse(
        array(
            'moderator' => $moderator,
            'full'   => USER::loggedIn(),
            'bbimg'  => IMAGES.'bbcodes'.DS,
            'form'   => $area[0],
            'area'   => $area[1],
            'smile'  => $names,
            'colors' => $colors,
            'path'   => MODULE.'editor&amp;dir='.$dir
        )
    );
}

function ShowError($message) {
    return CMS::call('SYSTEM')->defineWindow('Error', $message, 'center');
}

function ShowWindow($title, $content, $align = 'left') {
    return CMS::call('SYSTEM')->defineWindow($title, $content, $align);
}

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
            foreach ($sections as $id => $section) {
                $categories = CMS::call($obj)->getCategories($id);
                if (!empty($categories)) {
                    $gsm .=
                    "\t<url>".LF.
                    "\t\t<loc>".$url."?module=".$module.SECTION.$id."</loc>".LF.
                    "\t\t<lastmod>".$time."</lastmod>".LF.
                    "\t</url>".LF;
                    foreach ($categories as $key => $category) {
                        $category = CMS::call($obj)->getCategory($key);
                        $content  = CMS::call($obj)->getContent($key);
                        if (!empty($content)) {
                            $gsm .=
                            "\t<url>".LF.
                            "\t\t<loc>".$url."?module=".$module.SECTION.$id.CATEGORY.$key."</loc>".LF.
                            "\t\t<lastmod>".$time."</lastmod>".LF.
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
    $site_map .=
"</urlset>";
    if (!file_put_contents(ROOT.'sitemap.xml', $site_map, LOCK_EX)) {
        CMS::call('LOG')->logPut('Error', '', 'Cannot save file sitemap.xml');
    }
    CMS::call('SYSTEM')->createMainMenu();
}
?>