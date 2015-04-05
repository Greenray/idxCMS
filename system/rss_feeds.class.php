<?php
# idxCMS Flat Files Content Management Sysytem

/** Process rss feeds.
 *
 * @file      system/rss_feeds.class.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   RSS
 */

class RSS_FEEDS {

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

    /**Class initialization.
     *
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

    /** Adds item to the RSS feed.
     *
     * @param  string $item Item text fo the RSS feed
     * @return void
     */
    public function addItem($item) {
        if (empty($item['desc'])) {
            $item['desc'] = $item['text'];
        }
        $this->items[] = array($item['title'], $item['desc'], $item['link'], $item['time']);
    }

    /** Adds new feed.
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

    /** Shows RSS feed.
     * @return string - XML document
     */
    public function showFeed() {
        $result =
        '<?xml version="1.0" encoding="UTF-8"?>'.LF.
        '<rss version="2.0">'.LF.
        $result .= addFeed();
        $result .= '</rss>'.LF;
        return $result;
    }

    /** Gets RSS feed.
     *
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
