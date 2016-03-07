<?php
/**
 * RSS aggregator.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   4.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/rss_aggregator.class.php
 * @package   RSS
 * @overview  RSS aggregator and website RSS feeds.
 */

class RSS_AGGREGATOR {

    /** @param string $date_format Date format */
    public $date_format = '';

    /** @param string $default_cp Default encoding */
    public $default_cp = 'UTF-8';

    /** @param array $channeltags Channel tags */
    private $channeltags =['title', 'link', 'desc', 'language', 'copyright', 'managingEditor', 'webMaster', 'lastBuildDate', 'rating', 'docs'];

    public $CDATA = 'nochange';
    public $cp    = '';

    /** @param array $imagetags Image tags */
    private $imagetags = ['title', 'url', 'link', 'width', 'height'];

    /** @param integer $items_limit Items limit */
    public $items_limit = 0;

    /** @param array $itemtags Item tags */
    private $itemtags = ['title', 'link', 'desc', 'author', 'category', 'comments', 'enclosure', 'guid', 'pubDate', 'source'];

    /** @param boolean $stripHTML Strip HTML? */
    public $stripHTML = FALSE;

    /** @param array $textinputtags Text input tags */
    private $textinputtags = ['title', 'desc', 'name', 'link'];

    /**
     * Parses RSS file and returns associative array.
     *
     * @param  string $rss_url RSS URL
     * @return array           RSS data
     */
    public function get($rss_url) {
        #
        # If CACHE ENABLED
        #
        if ($this->cache_dir != '') {
            $cache_file = $this->cache_dir.'/rsscache_'.md5($rss_url);
            $timedif = time() - filemtime($cache_file);

            if ($timedif < $this->cache_time) {
                #
                # Cached file is fresh enough, return cached array
                #
//                $result = json_decode(join('', file($cache_file)));
                $result = unserialize(join('', file($cache_file)));
                #
                # Set 'cached' to 1 only if cached file is correct
                #
                if ($result) $result['cached'] = 1;
            } else {
                #
                # Cached file is too old, create new
                #
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
        #
        # If CACHE DISABLED >> load and parse the file directly
        #
        else {
            $result = $this->parse($rss_url);
            if ($result) {
                $result['cached'] = 0;
            }
        }
        return $result;
    }

    /**
     * Modification of preg_match();.
     *
     * @param  string $pattern Pattern
     * @param  string $subject Subject
     * @return string          Trimed field with index 1 from 'classic' preg_match() array output
     */
    private function myPregMatch ($pattern, $subject) {
        preg_match($pattern, $subject, $out);
        #
        # if there is some result... process it and return it
        #
        if (isset($out[1])) {
            #
            # Process CDATA (if present)
            #
            if ($this->CDATA == 'content') {         # Get CDATA content (without CDATA tag)
                $out[1] = strtr($out[1], ['<![CDATA['=>'', ']]>'=>'']);
            } elseif ($this->CDATA == 'strip') {     # Strip CDATA
                $out[1] = strtr($out[1], ['<![CDATA['=>'', ']]>'=>'']);
            }
            #
            # If code page is set convert character encoding to required
            #
            if ($this->cp != '') {
                $out[1] = iconv(@$this->rsscp, $this->cp.'//TRANSLIT', $out[1]);
            }
            return trim($out[1]);
        } else {
            return '';      # if there is NO result, return empty string
        }
    }

    /**
     * Replaces HTML entities.
     *
     * @param  string $string String
     * @return string         Parsed string
     */
    public function UnHtmlEntities ($string) {
        $trans_tbl = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);  # Get HTML entities table
        $trans_tbl = array_flip($trans_tbl);                                 # Flip keys<==>values
        $trans_tbl += ['&apos;' => "'"];                                     # Add support for &apos; entity (missing in HTML_ENTITIES)
        return strtr($string, $trans_tbl);                                   # Replace entities by values
    }

    /**
     * Parses RSS file.
     *
     * @param  string $rss_url RSS URL
     * @return array|boolean   Parsed RSS or FALSE
     */
    private function parse ($rss_url) {
        #
        # Open and load RSS file
        #
        if ($f = @fopen($rss_url, 'r')) {
            $rss_content = '';
            while (!feof($f)) {
                $rss_content .= fgets($f, 4096);
            }
            fclose($f);
            #
            # Parse document encoding
            #
            $result['encoding'] = $this->myPregMatch("'encoding=[\'\"](.*?)[\'\"]'si", $rss_content);
            #
            # If document codepage is specified, use it, otherwise use the default codepage
            #
            $this->rsscp = ($result['encoding'] != '') ? $result['encoding'] : 'UTF-8';
            #
            # Parse CHANNEL info
            #
            preg_match("'<channel.*?>(.*?)</channel>'si", $rss_content, $out_channel);
            foreach($this->channeltags as $channeltag) {
                $temp = $this->myPregMatch("'<$channeltag.*?>(.*?)</$channeltag>'si", $out_channel[1]);
                if ($temp != '') {
                    $result[$channeltag] = $temp; # Set only if not empty
                }
            }
            #
            # If date_format is specified and lastBuildDate is valid
            #
            if ($this->date_format != '' && ($timestamp = strtotime($result['lastBuildDate'])) !== -1) {
                # Convert lastBuildDate to specified date format
                $result['lastBuildDate'] = date($this->date_format, $timestamp);
            }
            #
            # Parse TEXTINPUT info
            #
            preg_match("'<textinput(|[^>]*[^/])>(.*?)</textinput>'si", $rss_content, $out_textinfo);
            #
            # This a little strange regexp means:
            # Look for tag <textinput> with or without any attributes, but skip truncated version <textinput /> (it's not beggining tag)
            #
            if (isset($out_textinfo[2])) {
                foreach($this->textinputtags as $textinputtag) {
                    $temp = $this->myPregMatch("'<$textinputtag.*?>(.*?)</$textinputtag>'si", $out_textinfo[2]);
                    if ($temp != '') {
                        $result['textinput_'.$textinputtag] = $temp; # Set only if not empty
                    }
                }
            }
            #
            # Parse IMAGE info
            #
            preg_match("'<image.*?>(.*?)</image>'si", $rss_content, $out_imageinfo);
            if (isset($out_imageinfo[1])) {
                foreach($this->imagetags as $imagetag) {
                    $temp = $this->myPregMatch("'<$imagetag.*?>(.*?)</$imagetag>'si", $out_imageinfo[1]);
                    if ($temp != '') {
                        $result['image_'.$imagetag] = $temp; # Set only if not empty
                    }
                }
            }
            #
            # Parse ITEMS
            #
            preg_match_all("'<item(| .*?)>(.*?)</item>'si", $rss_content, $items);
            $rss_items = $items[2];
            $i = 0;
            $result['items'] = []; # Create array even if there are no items

            foreach($rss_items as $rss_item) {
                #
                # If number of items is lower then limit: Parse one item
                #
                if ($i < $this->items_limit || $this->items_limit == 0) {
                    foreach($this->itemtags as $itemtag) {
                        $temp = $this->myPregMatch("'<$itemtag.*?>(.*?)</$itemtag>'si", $rss_item);
                        if ($temp != '') {
                            $result['items'][$i][$itemtag] = $temp; # Set only if not empty
                        }
                    }
                    #
                    # Strip HTML tags and other bullshit from DESCRIPTION
                    #
                    if ($this->stripHTML && !empty($result['items'][$i]['desc']))
                        $result['items'][$i]['desc'] = strip_tags($this->unHtmlEntities(strip_tags($result['items'][$i]['desc'])));
                    #
                    # Strip HTML tags and other bullshit from TITLE
                    #
                    if ($this->stripHTML && !empty($result['items'][$i]['title']))
                        $result['items'][$i]['title'] = strip_tags($this->unHtmlEntities(strip_tags($result['items'][$i]['title'])));
                    #
                    # If date_format is specified and pubDate is valid
                    #
                    if ($this->date_format != '' && ($timestamp = strtotime($result['items'][$i]['pubDate'])) !== -1) {
                        #
                        # Convert pubDate to specified date format
                        $result['items'][$i]['pubDate'] = date($this->date_format, $timestamp);
                        #
                    }
                    $i++;  # Items counter
                }
            }
            $result['items_count'] = $i;
            return $result;
        } else {
            return FALSE;
        }
    }
}
