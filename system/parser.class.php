<?php
/**
 * BBCODES parser.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.1
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/parser.class.php
 * @package   Core
 */

class PARSER {

    /** @var string Text to parse */
    private $text = '';

    /** @var array Temorary variable for code processing */
    private $temp = [];

    /** @var array Array of regexp */
    private $regexp = [];

    /** Class initialization */
    public function __construct() {
        $this->regexp[0] = [
            "#\[\*\](.*?)\[/\*\]#is" => '<li>\\1</li>',
            "#\[align=(\"|&quot;|)(left|right|center|justify)(\"|&quot;|)\](.*?)\[/align(.*?)\]#is" => '<div style="text-align:\\2;">\\4</div>',
            "#\[b\](.*?)\[/b\]#is" => '<b>\\1</b>',
            "#\[bgcolor=(\"|&quot;|)([\#\w]*)(\"|&quot;|)\](.*?)\[/bgcolor(.*?)\]#is" => '<span style="background:\\2">\\4</span>',
            "#\[color=(\"|&quot;|)([\#\w]*)(\"|&quot;|)\](.*?)\[/color(.*?)\]#is"     => '<span style="color:\\2;">\\4</span>',
            "#\[del\](.*?)\[/del\]#is" => '<del>\\1</del>',
            "#\[email\][\s\n\r]*([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)[\s\n\r]*\[/email\]#is"             => '<a href="mailto:\\1">\\1</a>',
            "#\[email=(\"|&quot;|)([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)(\"|&quot;|)\](.*?)\[/email\]#is" => '<a href="mailto:\\2">\\5</a>',
            "#\[font=(\"|&quot;|)([\#\w]*)(\"|&quot;|)\](.*?)\[/font(.*?)\]#is" => '<span style="font-family:\\2;">\\4</span>',
            "#\[h([0-9])\](.*?)\[/h([Войти0-9])\]#is" => '<h\\1>\\2</h\\3>',
            "#\[hr\]#is"           => '<hr />',
            "#\[i\](.*?)\[/i\]#is" => '<i>\\1</i>',
            "#\[indent\](.*?)\[/indent\]#is" => '<blockquote>\\1</blockquote>',
            "#\[(left|right|center|justify)\](.*?)\[/\\1\]#is" => '<div style="text-align:\\1;">\\2</div>',
            "#\[mailto\][\s\n\r]*([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)[\s\n\r]*\[/mailto\]#is"             => '<a href="mailto:\\1">\\1</a>',
            "#\[mailto=(\"|&quot;|)([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)(\"|&quot;|)\](.*?)\[/mailto\]#is" => '<a href="mailto:\\2">\\5</a>',
            "#\[mp3\](.*?)\[/mp3\]#is"   => $this->parseMP3(),
            "#\[note\](.*?)\[/note\]#is" => '<div class="note">\\1</div>',
            "#\[offtopic\](.*?)\[/offtopic\]#is" => '<small>\\1</small>',
            "#\[\[ol\]\](.*?)\[/\[ol\]\]#is"     => '<ol>\\1</ol>',
            "#\[p\](.*?)\[/p\]#is"     => '<p>\\1</p>',
            "#\[pre\](.*?)\[/pre\]#is" => '<pre>\\1</pre>',
            "#\[s\](.*?)\[/s\]#is"     => '<s>\\1</s>',
            "#\[size=(\"|&quot;|)([0-9]*)(\"|&quot;|)\](.*?)\[/size(.*?)\]#is" => '<span style="font-size:\\2px;">\\4</span>',
            "#\[sub\](.*?)\[/sub\]#is" => '<sub>\\1</sub>',
            "#\[sup\](.*?)\[/sup\]#is" => '<sup>\\1</sup>',
            "#\[u\](.*?)\[/u\]#is"     => '<span style="text-decoration:underline;">\\1</span>',
            "#\[\[ul\]\](.*?)\[/\[ul\]\]#is" => '<ul>\\1</ul>',
            "#\[url\][\s\n\r]*(((https?|ftp|ed2k|irc)://)[^ \"\n\r\t\<]*)[\s\n\r]*\[/url\]#is" => '<a href="\\1" target="_blank">\\1</a>',
            "#\[url\][\s\n\r]*(www\.[^ \"\n\r\t\<]*?)[\s\n\r]*\[/url\]#is"             => '<a href="http://\\1" target="_blank">\\1</a>',
            "#\[url\][\s\n\r]*((ftp)\.[^ \"\n\r\t\<]*?)[\s\n\r]*\[/url\]#is"           => '<a href="\\2://\\1" target="_blank">\\1</a>',
            "#\[url=(\"|&quot;|)([^ \"\n\r\t\<]*?)(\"|&quot;|)\](.*?)\[/url\]#is"      => '<a href="\\2">\\4</a>',
            "#\[url=(\"|&quot;|)(www\.[^ \"\n\r\t\<]*?)(\"|&quot;|)\](.*?)\[/url\]#is" => '<a href="http://\\2" target="_blank">\\4</a>',
            "#\[url=(\"|&quot;|)(((https?|ftp|ed2k|irc)://)[^ \"\n\r\t\<]*?)(\"|&quot;|)(.*?)\](.*?)\[/url\]#is" => '<a href="\\2\\6" target="_blank">\\7</a>',
            "#\[user\]([\d\w]*?)\[/user\]#is"       => '<a href="'.ROOT.'?module=user&user=\\1">\\1</a>',
            "#\[user=([\d\w]*?)\](.*?)\[/user\]#is" => '<a href="'.ROOT.'?module=user&user=\\1">\\2</a>',
            "#\[youtube\](.*?)\[/youtube\]#is"      => $this->parseYouTube()
        ];
    }

    /**
     * bbCodes panel for the specified textarea.
     *
     * @param  string  $textarea  Textarea ID
     * @param  boolean $moderator Is user an admin or moderator? (default = FALSE)
     * @param  string  $dir       Current directory (default = "")
     * @return string             bbCodes panel
     */
    function showBbcodesPanel($textarea, $moderator = FALSE, $dir = '') {
        $area   = explode('.', $textarea);
        $smiles = GetFilesList(SMILES);
        foreach ($smiles as $smile) {
            $names[] = basename($smile, '.gif');
        }
        $clrs   = ['00', '33', '66', '99', 'cc', 'ff'];
        $colors = [];
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
        $TPL->set([
            'moderator' => $moderator,
            'full'      => USER::$logged_in,
            'bbimg'     => IMAGES.'bbcodes'.DS,
            'form'      => $area[0],
            'area'      => $area[1],
            'smiles'    => $names,
            'colors'    => $colors,
            'path'      => MODULE.'editor&dir='.$dir
        ]);
        return $TPL->parse();
    }

    /**
     * Parses smiles in text.
     *
     * @return string
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
        $smiles = [
            ' :)' => ' <img src="'.SMILES.'smile.gif" alt="smile" /> ',
            ' ;)' => ' <img src="'.SMILES.'wink.gif" alt="wink" /> ',
            ' :(' => ' <img src="'.SMILES.'sad.gif" alt="sad" /> ',
            ' :D' => ' <img src="'.SMILES.'rofl.gif" alt="rofl" /> ',
           ' :-D' => ' <img src="'.SMILES.'yahoo.gif" alt="yahoo" /> ',
            ' :S' => ' <img src="'.SMILES.'suicide.gif" alt="confused" /> ',
            ' =)' => ' <img src="'.SMILES.'yow.gif" alt="yow" /> '
        ];
        foreach ($smiles as $search => $replace) {
            $this->text = str_replace($search, $replace, $this->text);
        }
    }

    /**
     * Parses [code]...[/code] bbtag.
     *
     * @return string
     */
    function parseCode() {
        preg_match_all("#[\s\n\r]*\[code\][\n\r]*(.*?)[\s\n\r]*\[/code\][\s\n\r]*#is", $this->text, $matches);
        if (!empty($matches)) {
            foreach ($matches[1] as $i => $code) {
                $code = preg_replace("#[\n\r]+#", '', highlight_string(strtr($code, array_flip(get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES))), TRUE));
                $tmp  = '$:'.RandomString(6).':$';
                $this->temp[$tmp] = '<div class="codetext">'.$code.'</div>';
                $this->text = str_replace($matches[0][$i], $tmp, $this->text);
            }
        }
    }

    /**
     * Parses [php]...[/php] bbtag.
     *
     * @return string
     */
    private function parsePhp() {
        preg_match_all("#[\s\n\r]*\[php\][\n\r]*(.*?)[\s\n\r]*\[/php\][\s\n\r]*#is", $this->text, $matches);
        if (!empty($matches)) {
            foreach ($matches[1] as $i => $code) {
                if ((mb_strpos($code, '&lt;?php') !== 0) && (mb_strpos($code, '<?php') !== 0)) {
                    $code = '<?php'.LF.$code.LF.'?>';
                }
                $code = preg_replace("#[\n\r]+#", '', highlight_string(strtr($code, array_flip(get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES))), TRUE));
                $tmp  = '$:'.RandomString(6).':$';
                $this->temp[$tmp] = '<div class="codephp">'.$code.'</div>';
                $this->text = str_replace($matches[0][$i], $tmp, $this->text);
            }
        }
    }

    /**
     * Parses [html]...[/html] bbtag.
     *
     * @return string
     */
    private function parseHtml() {
        preg_match_all("#[\s\n\r]*\[html\][\n\r]*(.*?)[\s\n\r]*\[/html\][\s\n\r]*#is", $this->text, $matches);
        if (!empty($matches)) {
            foreach ($matches[1] as $i => $code) {
                $this->HIGHLIGHTER(html_entity_decode($code));
                $tmp = '$:'.RandomString(6).':$';
                $this->temp[$tmp] = '<div class="codehtml">'.$this->highlight().'</div>';
                $this->text = str_replace($matches[0][$i], $tmp, $this->text);
            }
        }
    }

    /**
     * Parses [qoute|quote="Who"]...[/qoute] bbtags.
     *
     * @return string
     */
    private function parseQuote() {
        $this->text = preg_replace(
            "#[\s\n\r]*\[quote\][\s\n\r]*(.*?)[\s\n\r]*\[/quote\][\s\n\r]*#is", '<div class="quotetext">\\1</div>', $this->text
        );
        $this->text = preg_replace(
            "#[\s\n\r]*\[quote=(\"|\"|)(.*?)(\"|\"|)\][\s\n\r]*(.*?)[\s\n\r]*\[/quote\][\s\n\r]*#is",
            '<div class="quotetitle"><strong>\\2 :</strong><div class="quotetext">\\4</div></div>',
            $this->text
        );
    }

    /**
     * Shows spoiler with hidden text.
     *
     * @param  array  $matches Array of spoiler parameters
     * @return string          HTML div block with hidden text
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

    /**
     * Parses [img]...[/img] bbtag.
     *
     * @param  string $path Path to images directory
     * @return string       HTML div block with the image
     */
    private function parseImage($path = '') {
        preg_match_all("#\[img\][\s\n\r]*([^ \"\n\r\t<]*?)[\s\n\r]*\[/img\]#is", $this->text, $matches);
        if (!empty($matches)) {
            foreach ($matches[1] as $k => $picture) {
                $image    = basename($picture);
                $width    = CONFIG::getValue('main', 'thumb_width');
                $height   = CONFIG::getValue('main', 'thumb-height');
                $zoom     = '';
                $external = '';
                $internal = '';
                if ($picture !== $image) {
                    #
                    # Parsing of an old image
                    #
                    $parts = explode(DS, $picture);
                    if ($parts[0] !== 'http:') {
                        if (file_exists($picture.'.jpg'))
                             $zoom     = $picture;
                        else $internal = $picture;

                    } else $external = $picture;
                } else {
                    if (file_exists(TEMP.$picture)) {
                        #
                        # Uploaded image
                        #
                        if (empty($path)) {
                            #
                            # Uploaded image for preview
                            #
                            $internal = TEMP.$picture;
                        } else {
                            #
                            # Uploaded image for saving of text
                            #
                            $zoom = $path.$picture;
                            rename(TEMP.$picture, $zoom);
                            rename(TEMP.$picture.'.jpg', $zoom.'.jpg');
                        }
                    } elseif (file_exists(CONTENT.'images'.DS.$picture)) {
                        #
                        # Common images
                        #
                        $internal = CONTENT.'images'.DS.$picture;
                    } else {
                        if (file_exists($path.$picture)) {
                            if (file_exists($path.$picture.'.jpg'))
                                 $zoom     = $path.$picture;
                            else $internal = $path.$picture;
                        }
                    }
                }
                if (!empty($zoom)) {
                    $size = getimagesize($zoom.'.jpg');
                    if ($size)
                         $output = '<a class="cbox" href="'.$zoom.'"><img src="'.$zoom.'.jpg" '.$size[3].' hspace="10" vspace="10" alt="" /></a>';
                    else $output = '<a class="cbox" href="'.$zoom.'"><img src="'.$zoom.'.jpg" width="'.$width.'" height="'.$height.'" hspace="10" vspace="10" alt="" /></a>';

                } elseif (!empty($external)) {
                    $output = '<img src="'.$external.'" hspace="10" vspace="10" alt="" />';
                } elseif (!empty($internal)) {
                    $size = getimagesize($internal);
                    if ($size)
                         $output = '<img src="'.$internal.'" '.$size[3].' hspace="10" vspace="10" alt="" />';
                    else $output = '[Image not found]';

                } else $output = '[Image not found]';

                $this->text = str_replace($matches[0][$k], $output, $this->text);
            }
        }
    }

    /**
     * Parses [mp3]...[/mp3] bbtag.
     *
     * @return string
     */
    private function parseMP3() {
        $player = CONFIG::getSection('audio');
        $player['autostart'] = empty($player['autostart']) ? 'no' : 'yes';
        $player['loop']      = empty($player['loop']) ? 'no' : 'yes';
        return '<object type="application/x-shockwave-flash" data="'.TOOLS.'scmp3player.swf" id="mp3player1" width="'.$player['width'].'" height="'.$player['height'].'">
                    <param name="movie" value="'.TOOLS.'scmp3player.swf">
                    <param name="FlashVars" value="playerID=1&bg='.$player['bgcolor'].'&leftbg='.$player['leftbg'].'&lefticon='.$player['lefticon'].'&rightbg='.$player['rightbg'].'&rightbghover='.$player['rightbghover'].'&righticon='.$player['righticon'].'&righticonhover='.$player['righticonhover'].'&text='.$player['playertext'].'&slider='.$player['slider'].'&track='.$player['track'].'&border='.$player['border'].'&loader='.$player['loader'].'&loop='.$player['loop'].'&autostart='.$player['autostart'].'&soundFile=\\1">
                    <param name="quality" value="high">
                    <param name="menu" value="FALSE">
                    <param name="wmode" value="transparent">
                </object>';
    }

    /**
     * Parses [youtube]...[/youtube] bbtag.
     *
     * @return string
     */
    private function parseYouTube() {
        $width  = CONFIG::getValue('video', 'width');
        $height = CONFIG::getValue('video', 'height');
        return '<object width="'.$width.'" height="'.$height.'">
                    <param name="movie" value="http://www.youtube.com/v/\\1"></param>
                    <param name="wmode" value="transparent"></param>
                    <embed src="http://www.youtube.com/v/\\1" type="application/x-shockwave-flash" wmode="transparent" width="'.$width.'" height="'.$height.'"></embed>
                </object>';
    }

    /**
     * Main parser.
     *
     * @param  string $text Text for parsing
     * @param  string $path Path of images directory
     * @return string       Parsed text
     */
    public function parse($text, $path) {
        $this->text = $text;
        $this->parseCode();
        $this->parsePhp();
        $this->parseHtml();
        $this->parseImage($path);
        $this->parseQuote();
        $this->text = preg_replace_callback("#\[spoiler(=(\"|&quot;|)(.*?)(\"|&quot;|)|)\](.*?)\[/spoiler\]#is", [&$this, 'parseSpoiler'], $this->text);
        $this->text = preg_replace(array_keys($this->regexp[0]), array_values($this->regexp[0]), $this->text);
        $this->parseSmiles();
        $this->text = str_replace(["\r\n", "\n\r", "\r", "\n"], "<br />", $this->text);
        $this->text = str_replace(array_keys($this->temp), array_values($this->temp), $this->text);
        $this->text = str_replace(['[', ']'], ['&#91;', '&#93;'], $this->text);
        return $this->text;
    }

    /**
     * Parses text.
     *
     * @param  string $text Text for parsing
     * @param  string $path Path to the images directory
     * @return string       Parsed text
     */
    function parseText($text, $path = '') {
        $text = trim($text);
        if (empty($text)) {
            return '';
        }
        if (!USER::$root) {
            $text = htmlspecialchars($text);
        }
        return $this->parse($text, $path);
    }
}
