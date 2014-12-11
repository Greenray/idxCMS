<?php
/**
 * @file      system/parser.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>\n
 *            <https://github.com/Greenray/idxCMS/system/parser.class.php>
 * @copyright (c) 2011 - 2014 Victor Nabatov\n
 *            Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *            <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 */

/** Class PARSER - BBCODES parser */
class PARSER {

    /** Text to parse
     * @var string
     */
    private $text = '';

    /** Temorary variable fo code processing
     * @var array
     */
    private $temp = array();

    /** Array of regexp
     * @var array
     */
    private $regexp = array();

    /** Class initialization */
    public function __construct() {
        $this->regexp[0] = array(
            "#\[align=(\"|&quot;|)(left|right|center|justify)(\"|&quot;|)\](.*?)\[/align(.*?)\]#is" => '<div style="text-align:\\2;">\\4</div>',
            "#\[(left|right|center|justify)\](.*?)\[/\\1\]#is" => '<div style="text-align:\\1;">\\2</div>',
            "#\[b\](.*?)\[/b\]#is" => '<b>\\1</b>',
            "#\[bgcolor=(\"|&quot;|)([\#\w]*)(\"|&quot;|)\](.*?)\[/bgcolor(.*?)\]#is" => '<span style="background:\\2">\\4</span>',
            "#\[color=(\"|&quot;|)([\#\w]*)(\"|&quot;|)\](.*?)\[/color(.*?)\]#is" => '<span style="color:\\2;">\\4</span>',
            "#\[del\](.*?)\[/del\]#is" => '<del>\\1</del>',
            "#\[email\][\s\n\r]*([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)[\s\n\r]*\[/email\]#is" => '<a href="mailto:\\1">\\1</a>',
            "#\[email=(\"|&quot;|)([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)(\"|&quot;|)\](.*?)\[/email\]#is" => '<a href="mailto:\\2">\\5</a>',
            "#\[font=(\"|&quot;|)([\#\w]*)(\"|&quot;|)\](.*?)\[/font(.*?)\]#is" => '<span style="font-family:\\2;">\\4</span>',
            "#\[h([0-9])\](.*?)\[/h([Войти0-9])\]#is" => '<h\\1>\\2</h\\3>',
            "#\[hr\]#is" => '<hr />',
            "#\[i\](.*?)\[/i\]#is" => '<i>\\1</i>',
            "#\[indent\](.*?)\[/indent\]#is" => '<blockquote>\\1</blockquote>',
            "#\[mailto\][\s\n\r]*([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)[\s\n\r]*\[/mailto\]#is" => '<a href="mailto:\\1">\\1</a>',
            "#\[mailto=(\"|&quot;|)([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)(\"|&quot;|)\](.*?)\[/mailto\]#is" => '<a href="mailto:\\2">\\5</a>',
            "#\[note\](.*?)\[/note\]#is" => '<div class="note">\\1</div>',
            "#\[offtopic\](.*?)\[/offtopic\]#is" => '<small>\\1</small>',
            "#\[\[ol\]\](.*?)\[/\[ol\]\]#is" => '<ol>\\1</ol>',
            "#\[p\](.*?)\[/p\]#is" => '<p>\\1</p>',
            "#\[pre\](.*?)\[/pre\]#is" => '<pre>\\1</pre>',
            "#\[s\](.*?)\[/s\]#is" => '<s>\\1</s>',
            "#\[size=(\"|&quot;|)([0-9]*)(\"|&quot;|)\](.*?)\[/size(.*?)\]#is" => '<span style="font-size:\\2px;">\\4</span>',
            "#\[sub\](.*?)\[/sub\]#is" => '<sub>\\1</sub>',
            "#\[sup\](.*?)\[/sup\]#is" => '<sup>\\1</sup>',
            "#\[u\](.*?)\[/u\]#is" => '<span style="text-decoration:underline;">\\1</span>',
            "#\[\[ul\]\](.*?)\[/\[ul\]\]#is" => '<ul>\\1</ul>',
            "#\[url\][\s\n\r]*(((https?|ftp|ed2k|irc)://)[^ \"\n\r\t\<]*)[\s\n\r]*\[/url\]#is" => '<a href="\\1" target="_blank">\\1</a>',
            "#\[url\][\s\n\r]*(www\.[^ \"\n\r\t\<]*?)[\s\n\r]*\[/url\]#is" => '<a href="http://\\1" target="_blank">\\1</a>',
            "#\[url\][\s\n\r]*((ftp)\.[^ \"\n\r\t\<]*?)[\s\n\r]*\[/url\]#is" => '<a href="\\2://\\1" target="_blank">\\1</a>',
            "#\[url=(\"|&quot;|)([^ \"\n\r\t\<]*?)(\"|&quot;|)\](.*?)\[/url\]#is" => '<a href="\\2">\\4</a>',
            "#\[url=(\"|&quot;|)(www\.[^ \"\n\r\t\<]*?)(\"|&quot;|)\](.*?)\[/url\]#is" => '<a href="http://\\2" target="_blank">\\4</a>',
            "#\[url=(\"|&quot;|)(((https?|ftp|ed2k|irc)://)[^ \"\n\r\t\<]*?)(\"|&quot;|)(.*?)\](.*?)\[/url\]#is" => '<a href="\\2\\6" target="_blank">\\7</a>',
            "#\[user\]([\d\w]*?)\[/user\]#is" => '<a href="'.ROOT.'?module=user&user=\\1">\\1</a>',
            "#\[user=([\d\w]*?)\](.*?)\[/user\]#is" => '<a href="'.ROOT.'?module=user&user=\\1">\\2</a>',
            "#\[\*\](.*?)\[/\*\]#is" => '<li>\\1</li>',
            "#\[mp3\](.*?)\[/mp3\]#is" => $this->parseMP3(),
            "#\[youtube\](.*?)\[/youtube\]#is" => $this->parseYouTube()
        );
    }

    /** bbCodes panel for the specified textarea.
     * @param  string  $textarea  Textarea ID
     * @param  boolean $moderator Is user an admin or moderator?
     * @param  string  $dir       Current directory
     * @return array - bbCodes panel
     */
    function showBbcodesPanel($textarea, $moderator = FALSE, $dir = '') {
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
                            'full' => USER::loggedIn(),
                            'bbimg' => IMAGES.'bbcodes'.DS,
                            'form' => $area[0],
                            'area' => $area[1],
                            'smile' => $names,
                            'colors' => $colors,
                            'path' => MODULE.'editor&amp;dir='.$dir
                        )
        );
    }

    /** Parse smiles in text.
     * @return string - HTML tag with smile image
     */
    private function parseSmiles() {
        preg_match_all("#\[(.*?)\]#is", $this->text, $matches);
        if (!empty($matches)) {
            foreach ($matches[1] as $i => $smile) {
                if (file_exists(SMILES.$smile.'.gif')) {
                    $this->text = str_replace($matches[0][$i], '<img src="'.SMILES.$smile.'.gif" alt="'.$smile.'" />', $this->text);
                }
            }
        }
        $smiles = array(
            ' :)' => ' <img src="'.SMILES.'smile.gif" alt="smile" /> ',
            ' ;)' => ' <img src="'.SMILES.'wink.gif" alt="wink" /> ',
            ' :(' => ' <img src="'.SMILES.'sad.gif" alt="sad" /> ',
            ' :D' => ' <img src="'.SMILES.'rofl.gif" alt="rofl" /> ',
           ' :-D' => ' <img src="'.SMILES.'yahoo.gif" alt="yahoo" /> ',
            ' :S' => ' <img src="'.SMILES.'suicide.gif" alt="confused" /> ',
            ' =)' => ' <img src="'.SMILES.'yow.gif" alt="yow" /> ');
        foreach ($smiles as $search => $replace) {
            $this->text = str_replace($search, $replace, $this->text);
        }
    }

    /** Parse [code]...[/code] bbtag.
     * @return string - HTML div block with highlited php code
     */
    function parseCode() {
        preg_match_all("#[\s\n\r]*\[code\][\n\r]*(.*?)[\s\n\r]*\[/code\][\s\n\r]*#is", $this->text, $matches);
        if (!empty($matches)) {
            foreach ($matches[1] as $i => $code) {
                $code = preg_replace("#[\n\r]+#", '', highlight_string(strtr($code, array_flip(get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES))), TRUE));
                $tmp = '$:'.RandomString(6).':$';
                $this->temp[$tmp] = '<div class="codetext">'.$code.'</div>';
                $this->text = str_replace($matches[0][$i], $tmp, $this->text);
            }
        }
    }

    /** Parse [php]...[/php] bbtag.
     * @return string - HTML div block with highlited php code
     */
    private function parsePhp() {
        preg_match_all("#[\s\n\r]*\[php\][\n\r]*(.*?)[\s\n\r]*\[/php\][\s\n\r]*#is", $this->text, $matches);
        if (!empty($matches)) {
            foreach ($matches[1] as $i => $code) {
                if ((mb_strpos($code, '&lt;?php') !== 0) && (mb_strpos($code, '<?php') !== 0)) {
                    $code = '<?php'.LF.$code.LF.'?>';
                }
                $code = preg_replace("#[\n\r]+#", '', highlight_string(strtr($code, array_flip(get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES))), TRUE));
                $tmp = '$:'.RandomString(6).':$';
                $this->temp[$tmp] = '<div class="codephp">'.$code.'</div>';
                $this->text = str_replace($matches[0][$i], $tmp, $this->text);
            }
        }
    }

    /** Parse [html]...[/html] bbtag.
     * @return string - HTML div block with highlited html tags
     */
    private function parseHtml() {
        preg_match_all("#[\s\n\r]*\[html\][\n\r]*(.*?)[\s\n\r]*\[/html\][\s\n\r]*#is", $this->text, $matches);
        if (!empty($matches)) {
            foreach ($matches[1] as $i => $code) {
                $this->HtmlHighlighter(html_entity_decode($code));
                $tmp = '$:'.RandomString(6).':$';
                $this->temp[$tmp] = '<div class="codehtml">'.$this->highlight().'</div>';
                $this->text = str_replace($matches[0][$i], $tmp, $this->text);
            }
        }
    }

    /** Parse [qoute|quote="Who"]...[/qoute] bbtags.
     * @return string - HTML div block with the quoted text
     */
    private function parseQuote() {
        $this->text = preg_replace(
                "#[\s\n\r]*\[quote\][\s\n\r]*(.*?)[\s\n\r]*\[/quote\][\s\n\r]*#is", '<div class="quotetext">\\1</div>', $this->text
        );
        $this->text = preg_replace(
                "#[\s\n\r]*\[quote=(\"|\"|)(.*?)(\"|\"|)\][\s\n\r]*(.*?)[\s\n\r]*\[/quote\][\s\n\r]*#is", '<div class="quotetitle">' .
                '<strong>\\2 :</strong>' .
                '<div class="quotetext">\\4</div>' .
                '</div>', $this->text
        );
    }

    /** Show spoiler with hidden text.
     * @param  array $matches  Array of spoiler parameters
     * @return string - HTML div block with hidden text
     */
    private function parseSpoiler($matches) {
        if (!empty($matches)) {
            $id = RandomString(6);
            $title = !empty($matches[3]) ? __('Spoiler').': '.$matches[3] : __('Spoiler').' ('.__('Click to view').')';
            return '<div id="'.RandomString(6).'" class="spoiler">' .
                    '<a onClick="javascript:document.getElementById(\''.$id.'\').style.display=\'block\';">'.$title.'</a>' .
                    '</div>' .
                    '<div id="'.$id.'" class="codetext none">' .
                    $matches[5] .
                    '</div>';
        }
    }

    /** Parse [img]...[/img] bbtag.
     * @param  string $path Path to images directory
     * @return string - HTML div block with the image
     */
    private function parseImage($path = '') {
        preg_match_all("#\[img\][\s\n\r]*([^ \"\n\r\t<]*?)[\s\n\r]*\[/img\]#is", $this->text, $matches);
        if (!empty($matches)) {
            foreach ($matches[1] as $k => $picture) {
                $image = basename($picture);
                $width = CONFIG::getValue('main', 'thumb-width');
                $height = CONFIG::getValue('main', 'thumb-height');
                $zoom = '';
                $external = '';
                $internal = '';
                if ($picture !== $image) {
                    # Parsing of an old image.
                    $parts = explode(DS, $picture);
                    if ($parts[0] !== 'http:') {
                        if (file_exists($picture.'.jpg')) {
                            $zoom = $picture;
                        } else {
                            $internal = $picture;
                        }
                    } else
                        $external = $picture;
                } else {
                    if (file_exists(TEMP.$picture)) {
                        # Uploaded image
                        if (empty($path)) {
                            # Uploaded image for preview
                            $internal = TEMP.$picture;
                        } else {
                            # Uploaded image for saving of text
                            $zoom = $path.$picture;
                            rename(TEMP.$picture, $zoom);
                            rename(TEMP.$picture.'.jpg', $zoom.'.jpg');
                        }
                    } elseif (file_exists(CONTENT.'images'.DS.$picture)) {
                        # Common images
                        $internal = CONTENT.'images'.DS.$picture;
                    } else {
                        if (file_exists($path.$picture)) {
                            if (file_exists($path.$picture.'.jpg')) {
                                $zoom = $path.$picture;
                            } else {
                                $internal = $path.$picture;
                            }
                        }
                    }
                }
                if (!empty($zoom)) {
                    $size = getimagesize($zoom.'.jpg');
                    if ($size !== FALSE) {
                        $output = '<a class="cbox" href="'.$zoom.'">' .
                                '<img src="'.$zoom.'.jpg" '.$size[3].' hspace="10" vspace="10" alt="" />' .
                                '</a>';
                    } else {
                        $output = '<a class="cbox" href="'.$zoom.'">' .
                                '<img src="'.$zoom.'.jpg" width="'.$width.'" height="'.$height.'" hspace="10" vspace="10" alt="" />' .
                                '</a>';
                    }
                } elseif (!empty($external)) {
                    $output = '<img src="'.$external.'" hspace="10" vspace="10" alt="" />';
                } elseif (!empty($internal)) {
                    $size = getimagesize($internal);
                    if ($size !== FALSE) {
                        $output = '<img src="'.$internal.'" '.$size[3].' hspace="10" vspace="10" alt="" />';
                    } else {
                        $output = '[Image not found]';
                    }
                } else {
                    $output = '[Image not found]';
                }
                $this->text = str_replace($matches[0][$k], $output, $this->text);
            }
        }
    }

    /** Parse [mp3]...[/mp3] bbtag.
     * @return string - HTML block with flash mp3 player
     */
    private function parseMP3() {
        $player = CONFIG::getSection('audio');
        $player['autostart'] = empty($player['autostart']) ? 'no' : 'yes';
        $player['loop'] = empty($player['loop']) ? 'no' : 'yes';
        return '<object type="application/x-shockwave-flash" data="'.TOOLS.'scmp3player.swf" id="mp3player1" width="'.$player['width'].'" height="'.$player['height'].'">
                    <param name="movie" value="'.TOOLS.'scmp3player.swf">
                    <param name="FlashVars" value="playerID=1&amp;bg='.$player['bgcolor'].'&amp;leftbg='.$player['leftbg'].'&amp;lefticon='.$player['lefticon'].'&amp;rightbg='.$player['rightbg'].'&amp;rightbghover='.$player['rightbghover'].'&amp;righticon='.$player['righticon'].'&amp;righticonhover='.$player['righticonhover'].'&amp;text='.$player['playertext'].'&amp;slider='.$player['slider'].'&amp;track='.$player['track'].'&amp;border='.$player['border'].'&amp;loader='.$player['loader'].'&amp;loop='.$player['loop'].'&amp;autostart='.$player['autostart'].'&amp;soundFile=\\1">
                    <param name="quality" value="high">
                    <param name="menu" value="FALSE">
                    <param name="wmode" value="transparent">
                </object>';
    }

    /** Parse [youtube]...[/youtube] bbtag.
     * @return string - HTML block with youtube player
     */
    private function parseYouTube() {
        $width = CONFIG::getValue('video', 'width');
        $height = CONFIG::getValue('video', 'height');
        return '<object width="'.$width.'" height="'.$height.'">
                    <param name="movie" value="http://www.youtube.com/v/\\1"></param>
                    <param name="wmode" value="transparent"></param>
                    <embed src="http://www.youtube.com/v/\\1" type="application/x-shockwave-flash" wmode="transparent" width="'.$width.'" height="'.$height.'"></embed>
                </object>';
    }

    /** Main parser.
     * @param  string $text Text for parsing
     * @param  string $path Path of images directory
     * @return string - Parsed text
     */
    public function parse($text, $path) {
        $this->text = $text;
        $this->parseCode();
        $this->parsePhp();
        $this->parseHtml();
        $this->parseImage($path);
        $this->parseQuote();
        $this->text = preg_replace_callback("#\[spoiler(=(\"|&quot;|)(.*?)(\"|&quot;|)|)\](.*?)\[/spoiler\]#is", array(&$this, 'parseSpoiler'), $this->text);
        $this->text = preg_replace(array_keys($this->regexp[0]), array_values($this->regexp[0]), $this->text);
        $this->parseSmiles();
        $this->text = str_replace(array("\r\n", "\n\r", "\r", "\n"), "<br />", $this->text);
        $this->text = str_replace(array_keys($this->temp), array_values($this->temp), $this->text);
        $this->text = str_replace(array('[', ']'), array('&#91;', '&#93;'), $this->text);
        return $this->text;
    }

    /** Parse text.
     * @param  string $text Text for parsing
     * @param  string $path Path to the images directory
     * @return string _ Parsed text
     */
    function parseText($text, $path = '') {
        $text = trim($text);
        if (empty($text)) {
            return '';
        }
        if (!CMS::call('USER')->checkRoot()) {
            $text = htmlspecialchars($text);
        }
        return $this->parse($text, $path);
    }
}

/**
 * @file      system/parser.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>\n
 *            <https://github.com/Greenray/idxCMS/system/parser.class.php>
 * @copyright (c) 2011 - 2014 Victor Nabatov\n
 *            Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License\n
 *            <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 */

/** Class HtmlHighlighter - HTML and php highlighter */
class HtmlHighlighter extends PARSER {

    /** The results of parsing
     * @var string
     */
    private $output  = '';
    /** Text to parse
     *  @var string
     */
    private $text    = '';
    /** Start parsing from the begining
     * @var integer
     */
    private $current = 0;
    /** Tag name color
     * @var string
     */
    private $tag     = 'color:blue;';
    /** Tag attribute color
     * @var string
     */
    private $attr    = 'color:green;';
    /** Tag value color
     * @var string
     */
    private $value   = 'color:red;';
    /** php tag color
     * @var string
     */
    private $php     = 'color:black';
    /** inline style for comment
     * @var string
     */
    private $comment = 'font-style:italic;color:gray;';

    /** Class initialization */
    public function __construct($code) {
        $this->text = $code;
    }

    /** Comment highlighter.
     * @return string - HTML span block with highlighted comment
     */
    private function highlightComment() {
        $this->output .= '<span style="'.$this->comment.'">&lt;';
        for ($this->current += 1; ($this->current < mb_strlen($this->text)) && ($this->text[$this->current] !== '>'); $this->current++) {
            $this->output .= $this->text[$this->current];
        }
        $this->output .= '&gt;</span>';
    }

    /** php code highlighter.
     * @return string - HTML span block with highlighted php code
     */
    private function highlightPhp() {
        $this->output .= '<span style="'.$this->php.'">&lt;';
        ++$this->current;
        $this->output .= $this->text[$this->current];
        ++$this->current;
        while ($this->text[$this->current] !== '?') {
            $nextChar = $this->text[$this->current + 1];
            if ($this->text[$this->current] === ' ') {
                $this->output .= '&nbsp;';
            } elseif ($this->text[$this->current] === '<') {
                $this->output .= '&lt;';
            } elseif ($this->text[$this->current] === '>') {
                $this->output .= '&gt;';
            } elseif ($this->text[$this->current] === "\r") {
                if ($nextChar === "\n") {
                    $this->output .= str_replace("\r", '<br />', $this->text[$this->current]);
                    ++$this->current;
                    $this->output .= str_replace("\n", '', $this->text[$this->current]);
                } else {
                    $this->output .= str_replace("\r", '<br />', $this->text[$this->current]);
                }
            } elseif ($this->text[$this->current] === "\n") {
                if ($nextChar === "\r") {
                    $this->output .= str_replace("\n", '<br />', $this->text[$this->current]);
                    ++$this->current;
                    $this->output .= str_replace("\r", '', $this->text[$this->current]);
                } else {
                    $this->output .= str_replace("\n", '<br />', $this->text[$this->current]);
                }
            } else {
                $this->output .= $this->text[$this->current];
            }
            ++$this->current;
        }
        $this->output .= '?';
        ++$this->current;
        $this->output .= '&gt;</span>';
    }

    /** Tag highlighter.
     * @return string - HTML span block with highlighted tag
     */
    private function highlightTag() {
        $this->output .= '<span style="'.$this->tag.'">&lt;';
        $parsedTag = FALSE;
        # Parse full tag
        $length = mb_strlen($this->text);
        for ($this->current += 1; ($this->current < $length) && ($this->text[$this->current] !== ">"); $this->current++) {
            if (($this->text[$this->current] === ' ') && !$parsedTag) {
                $parsedTag = TRUE;
                $this->output .= '</span>';
            } elseif (($this->text[$this->current] !== ' ') && $parsedTag) {
                $attribute = '';
                # While we are in the tag
                for (; ($this->current < $length) && ($this->text[$this->current] !== '>'); $this->current++) {
                    if ($this->text[$this->current] !== '=') {
                        $attribute .= $this->text[$this->current];
                    } else {
                        $this->output .= '<span style="'.$this->attr.'">'.$attribute.'</span>=';
                        $attribute = '';
                        $value = '';
                        $quote = '';
                        for ($this->current += 1; ($this->current < $length) && ($this->text[$this->current] !== '>') && ($this->text[$this->current] !== ' '); $this->current++) {
                            if ($this->text[$this->current] === '"' || $this->text[$this->current] === "'") {
                                $quote = $this->text[$this->current];
                                $value .= $quote;
                                # Attribute value
                                for ($this->current += 1; ($this->current < $length) && ($this->text[$this->current] !== '>') && ($this->text[$this->current] !== $quote); $this->current++) {
                                    if ($this->text[$this->current] === '<') {
                                        if ($this->text[$this->current + 1] === '?') {
                                            $value .= '<span style="'.$this->php.'">&lt;';
                                            ++$this->current;
                                            $value .= $this->text[$this->current];
                                            $this->current += 1;
                                            while ($this->text[$this->current] !== '?') {
                                                $value .= $this->text[$this->current];
                                                ++$this->current;
                                            }
                                            $value .= '?';
                                            ++$this->current;
                                            $value .= '&gt;</span>';
                                        } else {
                                            $value .= '&lt;';
                                            for ($this->current += 1; $this->text[$this->current] !== '>'; $this->current++) {
                                                $value .= $this->text[$this->current];
                                            }
                                            $value .= '&gt;';
                                        }
                                    } else {
                                        $value .= $this->text[$this->current];
                                    }
                                }
                                $value .= $quote;
                            } else {
                                $value .= $this->text[$this->current];
                            }
                        }
                        $this->output .= '<span style="'.$this->value.'">'.$value.'</span>';
                        break;
                    }
                }
                if (!empty($attribute)) {
                    $this->output .= '<span style="'.$this->attr.'">'.$attribute.'</span>';
                }
            }
            if ($this->text[$this->current] === '>') {
                break;
            } else {
                $this->output .= $this->text[$this->current];
            }
        }
        if ($this->text[$this->current] === '>' && !$parsedTag) {
            $this->output .= '&gt;</span>';
            ++$this->current;
        }
        --$this->current;
    }

    /** Hightlight string.
     * @return string - Highlighted html
     */
    public function highlight() {
        $regexp = array("#echo#is" => '<span style="color:purple;">echo</span>');
        $length = mb_strlen($this->text) - 1;
        for ($this->current = 0; $this->current < $length; $this->current++) {
            $nextChar = $this->text[$this->current + 1];
            if ($this->text[$this->current] === ' ') {
                $this->output .= str_replace(" ", '&nbsp;', $this->text[$this->current]);
            } elseif ($this->text[$this->current] === '<') {
                if ($nextChar === '!') {
                    $this->highlightComment();
                } elseif ($nextChar === '?') {
                    $this->highlightPhp();
                } else {
                    $this->highlightTag();
                }
            } elseif ($this->text[$this->current] === "\r") {
                if ($nextChar === "\n") {
                    $this->output .= str_replace("\r", '<br />', $this->text[$this->current]);
                    ++$this->current;
                    $this->output .= str_replace("\n", '', $this->text[$this->current]);
                } else {
                    $this->output .= str_replace("\r", '<br />', $this->text[$this->current]);
                }
            } elseif ($this->text[$this->current] === "\n") {
                if ($nextChar === "\r") {
                    $this->output .= str_replace("\n", '<br />', $this->text[$this->current]);
                    ++$this->current;
                    $this->output .= str_replace("\r", '', $this->text[$this->current]);
                } else {
                    $this->output .= str_replace("\n", '<br />', $this->text[$this->current]);
                }
            } else {
                $this->output .= $this->text[$this->current];
            }
        }
        $this->output = preg_replace(array_keys($regexp), array_values($regexp), $this->output);
        return '<code>'.$this->output.'</code>';
    }
}
