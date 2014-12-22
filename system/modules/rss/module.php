<?php
/**
 * @file      system/modules/rss/module.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>\n
 *            <https://github.com/Greenray/idxCMS/system/modules/rss/module.php>
 * @copyright (c) 2011 - 2014 Victor Nabatov\n
 *            Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *            <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 */

if (!defined('idxCMS')) die();

/** Class RSS_FEED - RSS feeds */
class RSS_FEED {

    /** RSS feed title
     * @param string
     */
    public $title = '';

    /** RSS feed URL
     * @param string
     */
    public $url = '';

    /** RSS feed description
     * @param string
     */
    public $description = '';

    /** RSS feed language
     * @param string
     */
    public $language = '';

    /** RSS feed copyright
     * @param string
     */
    public $copyright = '';

    /** RSS feed generator
     * @param string
     */
    public $generator = '';

    /** RSS feed items
     * @param array
     */
    public $items = [];

    /**Class initialization
     * @param  string $title       RSS feed title
     * @param  string $description RSS feed description
     * @return void
     */
    public function __construct($title, $description) {
        $this->title       = $title;
        $this->url         = SYSTEM::get('url');
        $this->description = $description;
        $this->language    = SYSTEM::get('locale');
        $this->copyright   = CONFIG::getValue('main', 'copyright');
        $this->generator   = 'idxCMS '.IDX_VERSION;
    }

    /** Add item to the RSS feed
     * @param  string $item Item text fo the RSS feed
     * @return void
     */
    public function addItem($item) {
        if (empty($item['desc'])) {
            $item['desc'] = $item['text'];
        }
        $this->items[] = array($item['title'], $item['desc'], $item['link'], $item['time']);
    }

    /** Add new feed
     * @return string - XML document
     */
    public function addFeed() {
        $result =
        "\t<channel>".LF.
        "\t\t".'<title>'.$this->title.'</title>'.LF.
        "\t\t".'<link>'.$this->url.'</link>'.LF.
        "\t\t".'<description>'.$this->description.'</description>'.LF.
        "\t\t".'<language>'.$this->language.'</language>'.LF.
        "\t\t".'<copyright>'.$this->copyright.'</copyright>'.LF.
        "\t\t<lastBuildDate>".date('r')."</lastBuildDate>".LF.
        "\t\t".'<generator>'.$this->generator.'</generator>'.LF;
        foreach ($this->items as $item) {
            $result .=
            "\t\t<item>".LF;
            "\t\t\t".'<title>'.$item[0].'</title>'.LF.
            "\t\t\t".'<description>'.$item[1].'</description>'.LF.
            "\t\t\t".'<link>'.$this->url.$item[2].'</link>'.LF.
            "\t\t\t<pubDate>".date('r', $item[3])."</pubDate>".LF.
            "\t\t</item>".LF;
        }
        $result .= "\t</channel>".LF;
        return $result;
    }

    /** Show RSS feed
     * @return string - XML document
     */
    public function showFeed() {
        $result =
        '<?xml version="1.0" encoding="UTF-8"?>'.LF.
        '<rss version="2.0">'.LF.
        "\t<channel>".LF.
        "\t\t".'<title>'.$this->title.'</title>'.LF.
        "\t\t".'<link>'.$this->url.'</link>'.LF.
        "\t\t".'<description>'.$this->description.'</description>'.LF.
        "\t\t".'<language>'.$this->language.'</language>'.LF.
        "\t\t".'<copyright>'.$this->copyright.'</copyright>'.LF.
        "\t\t<lastBuildDate>".date('r')."</lastBuildDate>".LF.
        "\t\t".'<generator>'.$this->generator.'</generator>'.LF;
        foreach ($this->items as $item) {
            $result .=
            "\t\t<item>".LF.
            "\t\t\t".'<title>'.$item[0].'</title>'.LF.
            "\t\t\t".'<description>'.$item[1].'</description>'.LF.
            "\t\t\t".'<link>'.$this->url.$item[2].'</link>'.LF.
            "\t\t\t<pubDate>".date('r', $item[3])."</pubDate>".LF.
            "\t\t</item>".LF;
        }
        $result .=
        "\t</channel>".LF.
        '</rss>'.LF;
        return $result;
    }

    /** Get RSS feed
     * @param string $feed RSS feed
     * @return void
     */
    public function getFeed($feed) {
        list($module, $section) = explode('@', $feed);
        $module = strtoupper($module);
        if (CMS::call($module)->getSection($section)) {
            $categories = CMS::call($module)->getCategories($section);
            foreach ($categories as $key => $category) {
                $content = CMS::call($module)->getContent($key);
                $limit = CONFIG::getValue('main', 'last');
                $list = array_slice($content, -$limit, $limit, TRUE);
                foreach ($list as $id => $post) {
                    $this->addItem(CMS::call($module)->getItem($id, 'desc', TRUE));
                }
            }
        }
    }
}

/**
 * @file      system/modules/rss/module.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>\n
 *            <https://github.com/Greenray/idxCMS/system/modules/rss/module.php>
 * @copyright (c) 2011 - 2014 Victor Nabatov\n
 *            Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *            <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 */

/** Class LastRSS - RSS aggregator */
class LastRSS {

    /** Default encoding
     * @param string $default_cp
     */
    public $default_cp = 'UTF-8';
    public $CDATA = 'nochange';
    public $cp = '';

    /** Items limit
     * @param integer $items_limit
     */
    public $items_limit = 0;

    /**
     * Strip HTML?
     * @param boolean $stripHTML
     */
    public $stripHTML = FALSE;

    /** Date format
     * @param string $date_format
     */
    public $date_format = '';

    /** Channel tags
     * @param array $channeltags
     */
    private $channeltags = array ('title', 'link', 'desc', 'language', 'copyright', 'managingEditor', 'webMaster', 'lastBuildDate', 'rating', 'docs');

    /** Item tags
     * @param array $itemtags
     */
    private $itemtags = array('title', 'link', 'desc', 'author', 'category', 'comments', 'enclosure', 'guid', 'pubDate', 'source');

    /** Image tags
     * @param array $imagetags
     */
    private $imagetags = array('title', 'url', 'link', 'width', 'height');

    /** Text input tags
     * @param array $textinputtags
     */
    private $textinputtags = array('title', 'desc', 'name', 'link');

    /** Parse RSS file and returns associative array
     * @param  string $rss_url RSS URL
     * @return array - RSS data
     */
    public function get($rss_url) {
        // If CACHE ENABLED
        if ($this->cache_dir != '') {
            $cache_file = $this->cache_dir.'/rsscache_'.md5($rss_url);
            $timedif = time() - filemtime($cache_file);

            if ($timedif < $this->cache_time) {
                // Cached file is fresh enough, return cached array
                $result = unserialize(join('', file($cache_file)));
                // Set 'cached' to 1 only if cached file is correct
                if ($result) $result['cached'] = 1;
            } else {
                // Cached file is too old, create new
                $result = $this->parse($rss_url);
                $serialized = serialize($result);
                if ($f = @fopen($cache_file, 'w')) {
                    fwrite ($f, $serialized, strlen($serialized));
                    fclose($f);
                }
                if ($result) {
                    $result['cached'] = 0;
                }
            }
        }
        // If CACHE DISABLED >> load and parse the file directly
        else {
            $result = $this->parse($rss_url);
            if ($result) {
                $result['cached'] = 0;
            }
        }
        return $result;
    }

    /** Modification of preg_match();
     * @param  string $pattern Pattern
     * @param  string $subject Subject
     * @return string - Trimed field with index 1 from 'classic' preg_match() array output
     */
    private function myPregMatch ($pattern, $subject) {
        preg_match($pattern, $subject, $out);        // Start regullar expression
        // if there is some result... process it and return it
        if (isset($out[1])) {
            // Process CDATA (if present)
            if ($this->CDATA == 'content') {         // Get CDATA content (without CDATA tag)
                $out[1] = strtr($out[1], array('<![CDATA['=>'', ']]>'=>''));
            } elseif ($this->CDATA == 'strip') {     // Strip CDATA
                $out[1] = strtr($out[1], array('<![CDATA['=>'', ']]>'=>''));
            }
            // If code page is set convert character encoding to required
            if ($this->cp != '') {
                $out[1] = iconv(@$this->rsscp, $this->cp.'//TRANSLIT', $out[1]);
            }
            return trim($out[1]);
        } else {
            return '';      // if there is NO result, return empty string
        }
    }

    /** Replace HTML entities.
     * @param  string $string String
     * @return string  - Parsed string
     */
    public function UnHtmlEntities ($string) {
        $trans_tbl = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);  // Get HTML entities table
        $trans_tbl = array_flip($trans_tbl);                                 // Flip keys<==>values
        $trans_tbl += array('&apos;' => "'");                                // Add support for &apos; entity (missing in HTML_ENTITIES)
        return strtr($string, $trans_tbl);                                   // Replace entities by values
    }

    /** Parse RSS file
     * @param  string $rss_url RSS URL
     * @return array|boolean - Parsed RSS or FALSE
     */
    private function parse ($rss_url) {
        // Open and load RSS file
        if ($f = @fopen($rss_url, 'r')) {
            $rss_content = '';
            while (!feof($f)) {
                $rss_content .= fgets($f, 4096);
            }
            fclose($f);
            // Parse document encoding
            $result['encoding'] = $this->myPregMatch("'encoding=[\'\"](.*?)[\'\"]'si", $rss_content);
            // If document codepage is specified, use it, otherwise use the default codepage
            $this->rsscp = ($result['encoding'] != '') ? $result['encoding'] : 'UTF-8';
            // Parse CHANNEL info
            preg_match("'<channel.*?>(.*?)</channel>'si", $rss_content, $out_channel);
            foreach($this->channeltags as $channeltag) {
                $temp = $this->myPregMatch("'<$channeltag.*?>(.*?)</$channeltag>'si", $out_channel[1]);
                if ($temp != '') {
                    $result[$channeltag] = $temp; // Set only if not empty
                }
            }
            // If date_format is specified and lastBuildDate is valid
            if ($this->date_format != '' && ($timestamp = strtotime($result['lastBuildDate'])) !== -1) {
                // Convert lastBuildDate to specified date format
                $result['lastBuildDate'] = date($this->date_format, $timestamp);
            }
            // Parse TEXTINPUT info
            preg_match("'<textinput(|[^>]*[^/])>(.*?)</textinput>'si", $rss_content, $out_textinfo);
            // This a little strange regexp means:
            // Look for tag <textinput> with or without any attributes, but skip truncated version <textinput /> (it's not beggining tag)
            if (isset($out_textinfo[2])) {
                foreach($this->textinputtags as $textinputtag) {
                    $temp = $this->myPregMatch("'<$textinputtag.*?>(.*?)</$textinputtag>'si", $out_textinfo[2]);
                    if ($temp != '') {
                        $result['textinput_'.$textinputtag] = $temp; // Set only if not empty
                    }
                }
            }

            // Parse IMAGE info
            preg_match("'<image.*?>(.*?)</image>'si", $rss_content, $out_imageinfo);
            if (isset($out_imageinfo[1])) {
                foreach($this->imagetags as $imagetag) {
                    $temp = $this->myPregMatch("'<$imagetag.*?>(.*?)</$imagetag>'si", $out_imageinfo[1]);
                    if ($temp != '') {
                        $result['image_'.$imagetag] = $temp; // Set only if not empty
                    }
                }
            }

            // Parse ITEMS
            preg_match_all("'<item(| .*?)>(.*?)</item>'si", $rss_content, $items);
            $rss_items = $items[2];
            $i = 0;
            $result['items'] = []; // Create array even if there are no items

            foreach($rss_items as $rss_item) {
                // If number of items is lower then limit: Parse one item
                if ($i < $this->items_limit || $this->items_limit == 0) {
                    foreach($this->itemtags as $itemtag) {
                        $temp = $this->myPregMatch("'<$itemtag.*?>(.*?)</$itemtag>'si", $rss_item);
                        if ($temp != '') {
                            $result['items'][$i][$itemtag] = $temp; // Set only if not empty
                        }
                    }
                    // Strip HTML tags and other bullshit from DESCRIPTION
                    if ($this->stripHTML && !empty($result['items'][$i]['desc']))
                        $result['items'][$i]['desc'] = strip_tags($this->unHtmlEntities(strip_tags($result['items'][$i]['desc'])));
                    // Strip HTML tags and other bullshit from TITLE
                    if ($this->stripHTML && !empty($result['items'][$i]['title']))
                        $result['items'][$i]['title'] = strip_tags($this->unHtmlEntities(strip_tags($result['items'][$i]['title'])));
                    // If date_format is specified and pubDate is valid
                    if ($this->date_format != '' && ($timestamp = strtotime($result['items'][$i]['pubDate'])) !== -1) {
                        // Convert pubDate to specified date format
                        $result['items'][$i]['pubDate'] = date($this->date_format, $timestamp);
                    }
                    $i++;                     // Item counter
                }
            }
            $result['items_count'] = $i;
            return $result;
        } else {
            return FALSE;
        }
    }
}

switch (SYSTEM::get('locale')) {
    case 'ru':
        $LANG['def']['RSS aggregator']         = 'RSS агрегатор';
        $LANG['def']['RSS feeds']              = 'Ленты RSS';
        $LANG['def']['RSS feeds are off']      = 'Ленты RSS отключены';
        $LANG['def']['RSS feeds list']         = 'Список RSS лент';
        $LANG['def']['Subscribe for all']      = 'Подписаться на все';
        $LANG['def']['Subscribe to RSS feeds'] = 'Подписка на ленты RSS';
        break;

    case 'ua':
        $LANG['def']['RSS aggregator']         = 'RSS агрегатор';
        $LANG['def']['RSS feeds']              = 'Стрічки RSS';
        $LANG['def']['RSS feeds are off']      = 'Стрічки RSS відключені';
        $LANG['def']['RSS feeds list']         = 'Список RSS стрічок';
        $LANG['def']['Subscribe for all']      = 'Підписатися на все';
        $LANG['def']['Subscribe to RSS feeds'] = 'Підписка на стрічки RSS';
        break;

    case 'by':
        $LANG['def']['RSS aggregator']         = 'RSS агрэгатар';
        $LANG['def']['RSS feeds']              = 'Стужкі RSS';
        $LANG['def']['RSS feeds are off']      = 'Стужкі RSS адключаныя';
        $LANG['def']['RSS feeds list']         = 'Спіс RSS стужак';
        $LANG['def']['Subscribe for all']      = 'Падпісацца на ўсе';
        $LANG['def']['Subscribe to RSS feeds'] = 'Падпіска на стужкі RSS';
        break;
}

SYSTEM::registerModule('rss',            'RSS feeds',      'box');
SYSTEM::registerModule('rss.list',       'RSS feeds list', 'main');
SYSTEM::registerModule('rss.aggregator', 'RSS aggregator', 'box');
