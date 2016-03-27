<?php
/**
 * Process rss feeds.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   4.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/rss_feeds.class.php
 * @package   RSS
 */

class RSS_FEEDS {

    /** @param string RSS feed title */
    public $title = '';

    /** @param string RSS feed URL */
    public $url = '';

    /** @param string RSS feed description */
    public $description = '';

    /** @param string RSS feed language */
    public $language = '';

    /** @param string RSS feed copyright */
    public $copyright = '';

    /** @param string RSS feed generator */
    public $generator = '';

    /** @param array RSS feed items */
    public $items = [];

    /**
     * Class initialization.
     *
     * @param  string $title       RSS feed title
     * @param  string $description RSS feed description
     */
    public function __construct($title, $description) {
        $this->title       = $title;
        $this->url         = SYSTEM::get('url');
        $this->description = $description;
        $this->language    = SYSTEM::get('locale');
        $this->copyright   = CONFIG::getValue('main', 'copyright');
        $this->generator   = 'idxCMS '.IDX_VERSION;
    }

    /**
     * Adds item to the RSS feed.
     *
     * @param  string $item Item text fo the RSS feed
     */
    public function addItem($item) {
        if (empty($item['desc'])) {
            $item['desc'] = $item['text'];
        }
        $this->items[] = [$item['title'], $item['desc'], $item['link'], $item['time']];
    }

    /**
     * Adds new feed.
     *
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

    /**
     * Shows RSS feed.
     *
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

    /**
     * Gets RSS feed.
     *
     * @param string $feed RSS feed
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
