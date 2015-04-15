<?php
/** Templates parser.
 *
 * @program   idxCMS: Flat Files Content Management Sysytem
 * @file      system/template.class.php
 * @version   2.4
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-Share Alike 4.0 Unported License
 * @package   Core
 */

class TEMPLATE {

    /** @var array Template variables */
    private $vars = [];

    /** @var string Temlate name */
    private $tpl = '';

    /** @var array Template patterns */
    private $patterns = [
        'die'       => "#<\?php(.*?)\?>#is",
        'each'      => "#\[each=(.*?)\](.*?)\[\/each.\\1\]#is",
        'foreach'   => "#\[foreach=([\w\_\-]*).([\w\_\-]*).([\w\_\-]*)\](.*?)\[\/foreach.\\1\]#is",
        'for'       => "#\[for=(.*?)\](.*?)\[\/for\]#is",
        'if'        => "#\[if=(.*?)(|\[(.*?)\])\](.*?)\[\/if\]#is",
        'ifelse'    => "#\[ifelse=(.*?)(|\[(.*?)\])\](.*?)\[else\](.*?)\[\/else\]#is",
        'translate' => "#\[__(.*?)\]#is",
        'value'     => "#\{([\-\#\w]+)(|\[(.*?)\])\}#is",
        'show'      => "#\[show=(.*?)\]#is"
    ];

    /** Class initialization.
     * <pre>
     * There are three templates directories:
     *  - ./skins/CURRENT_SKIN/        - created (modified) templates by site's disigner
     *  - ./system/modules/__MODULE__/ - original module templates
     *  - ./system/templates/          - common templates for two or more modules
     * </pre>
     *
     * @param  string $template Path to template
     * @return void
     */
    public function __construct($template) {
        $tpl = basename($template);
        if     (file_exists(CURRENT_SKIN.$tpl.'.php')) $this->tpl = file_get_contents(CURRENT_SKIN.$tpl.'.php');
        elseif (file_exists(TEMPLATES.$tpl.'.php'))    $this->tpl = file_get_contents(TEMPLATES.$tpl.'.php');
        elseif (file_exists($template.'.php'))         $this->tpl = file_get_contents($template.'.php');
        else                                           $this->tpl = $template;
    }

    /** Parses control structure EACH.
     * <pre>
     * The templates are:
     *   [еach=var]...[еndeach.var]
     *   [еach=var[index]]...[еndeach.var]
     * </pre>
     *
     * @param  array $matches Matches for control structure "each"
     * @return string         Parsed string
     */
    private function __each($matches) {
        $temp = '';
        if (!empty($this->vars[$matches[1]])) {
            foreach ($this->vars[$matches[1]] as $key => $var) {
                $tpl = $matches[2];
                if (is_array($var)) {
                    # [each=var[index]]...[/each.var[index]]
                    preg_match_all('#\[each='.$matches[1].'\[(.*)\]\](.*?)\[/each.'.$matches[1].'\[\\1\]\]#is', $tpl, $sigs);
                    if (!empty($sigs[0])) {
                        $tmp = $sigs[2][0];
                        preg_match($this->patterns['if'], $tmp, $ifsigs);
                        if (!empty($ifsigs)) {
                            $tmpl = '';
                            foreach ($var[$ifsigs[1]] as $i => $values) {
                                if (!empty($values[$ifsigs[3]]))
                                     $tmpl .= str_replace($ifsigs[0], $ifsigs[4], $tmp);
                                else $tmpl .= str_replace($ifsigs[0], '', $tmp);

                                foreach ($values as $k => $value) {
                                    $tmpl = str_replace('{'.$sigs[1][0].'['.$k.']}', $value, $tmpl);
                                }
                            }
                            $tpl = str_replace($sigs[0][0], $tmpl, $tpl);
                        }
                        # [each=var[index]]
                        #     [ifelse=var]...[/else]
                        # [/each.var[index]]
                        preg_match($this->patterns['ifelse'], $tmp, $ifsigs);
                        if (!empty($ifsigs)) {
                            $tmpl = '';
                            if (!empty($var[$ifsigs[1]])) {
                                foreach ($var[$ifsigs[1]] as $i => $values) {
                                    if (!empty($values[$ifsigs[3]]))
                                         $tmpl .= str_replace($ifsigs[0], $ifsigs[4], $tmp);
                                    else $tmpl .= str_replace($ifsigs[0], $ifsigs[5], $tmp);
                                    # [each=var[index]]
                                    #     [ifelse=var]
                                    #         {var[index]}
                                    #     [/else]
                                    # [/each.var[index]]
                                    foreach ($values as $k => $value) {
                                        $tmpl = str_replace('{'.$sigs[1][0].'['.$k.']}', $value, $tmpl);
                                    }
                                }
                            }
                            $tpl = str_replace($sigs[0][0], $tmpl, $tpl);
                        }
                        # [each=var[index]]
                        #     {var[index]}
                        # [/each.var[index]]
                        preg_match_all('#\{'.$sigs[1][0].'\[(.*?)\]\}#is', $tmp, $subsigs);
                        $tmpl = '';
                        if (!empty($var[$sigs[1][0]])) {
                            foreach ($var[$sigs[1][0]] as $k => $value) {
                                foreach ($subsigs[0] as $j => $idx) {
                                    if (isset($value[$subsigs[1][$j]])) {
                                        $tmp = str_replace($idx, $value[$subsigs[1][$j]], $tmp);
                                    }
                                }
                                $tmpl .= preg_replace($subsigs[0], $value, $tmp);
                                $tmp = $sigs[2][0];
                            }
                            $tpl = str_replace($sigs[0][0], $tmpl, $tpl);
                        } else {
                            $tpl = str_replace($sigs[0][0], $tmpl, $tpl);
                        }
                    }
                    # [each=var[index]]
                    #     [if=var]...[/if]
                    # [/each.var[index]]
                    preg_match_all($this->patterns['if'], $tpl, $sigs);
                    if (!empty($sigs)) {
                        foreach ($sigs[1] as $k => $idx) {
                            if (!empty($var[$sigs[3][$k]]))
                                 $tpl = str_replace($sigs[0][$k], $sigs[4][$k], $tpl);
                            else $tpl = str_replace($sigs[0][$k], '', $tpl);
                        }
                    }
                    # [each=var[index]]
                    #     [ifelse=var]...[/else]
                    # [/each.var[index]]
                    preg_match_all($this->patterns['ifelse'], $tpl, $sigs);
                    if (!empty($sigs)) {
                        foreach ($sigs[1] as $k => $idx) {
                            $val = $this->searchValueInArray($sigs[3][$k], $var);
                            if (!empty($var[$sigs[3][$k]]) || !empty($val))
                                 $tpl = str_replace($sigs[0][$k], $sigs[4][$k], $tpl);
                            else $tpl = str_replace($sigs[0][$k], $sigs[5][$k], $tpl);
                        }
                    }
                    # [each=var[index]]
                    #     {var[index][subindex]}
                    # [/each.var[index]]
                    preg_match_all('/\{'.$matches[1].'\[(.*)\]\[(.*)\]\}/U', $tpl, $sigs);
                    if (!empty($sigs)) {
                        foreach($sigs[1] as $k => $idx) {
                            if (in_array($idx, $var)) {
                                $tpl = str_replace($sigs[0][$k], $var[$idx][$sigs[2][$k]], $tpl);
                            } elseif (array_key_exists($idx, $var) && !empty($var[$idx])) {
                                $tpl = str_replace($sigs[0][$k], $var[$idx], $tpl);
                            } else {
                                if (array_key_exists($idx, $var) && ($var[$idx] === 0)) {
                                    $tpl = str_replace($sigs[0][$k], $var[$idx], $tpl);
                                }
                            }
                        }
                    }
                    # [each=var[index]]
                    #     {var[index]}
                    # [/each.var[index]]
                    preg_match_all('/\{'.$matches[1].'(|\[(.*)\])\}/U', $tpl, $sigs);
                    if (!empty($sigs)) {
                        foreach($sigs[2] as $k => $idx) {
                            if (array_key_exists($idx, $var)) {
                                $tpl = str_replace($sigs[0][$k], $var[$idx], $tpl);
                            }
                        }
                    }
                    $temp .= $tpl;
                } else {
                    # [each=var[index]]
                    #     {var}
                    # [/each.var[index]]
                    $temp .= str_replace('{'.$matches[1].'}', $var, $tpl);   # Parsing of structure {var}
                }
            }
        }
        return str_replace($matches[0], $temp, $matches[0]);
    }

    /** Parses of a control structure FOR.
     * <pre>
     * The template is:
     *   [fоr=x.var]...[еndfor]
     * </pre>
     *
     * @param  array  $matches Matches for control structure "each"
     * @return string          Parsed string
     */
    private function __for($matches) {
        $params = explode('.', $matches[1]);
        $count  = sizeof($this->vars);
        $tpl    = $matches[2];
        $result = '';
        for ($i = $params[0]; $i <= $count; $i++) {
            $tpl = str_replace('{'.$params[1].'}', $this->vars[$i], $tpl);
            $result .= $tpl;
        }
        return str_replace($matches[0], $result, $matches[0]);
    }

    /** Parses control structure FOREACH.
     * <pre>
     * The template is:
     *   [forеach=var1.var2.var3]...[/forеach.var1]
     * </pre>
     *
     * @param  array $matches Matches for control structure "foreach"
     * @return string         Parsed string
     */
    private function __foreach($matches) {
        $temp = '';
        if (!empty($this->vars[$matches[1]])) {
            foreach ($this->vars[$matches[1]] as $key => $var) {
                preg_match($this->patterns['if'], $matches[4], $sigs);
                # [foreach=var1.var2.var3]
                #     [if]...[/if]
                # [/foreach.var1]
                if (!empty($sigs)) {
                    if (!empty($var))
                         $tmp = str_replace($sigs[0], $sigs[4], $matches[4]);
                    else $tmp = str_replace($sigs[0], '', $matches[4]);
                } else   $tmp = $matches[4];
                # [foreach=var1.var2.var3]
                #     {var}
                # [/foreach.var1]
                $temp .= str_replace(['{'.$matches[2].'}', '{'.$matches[3].'}'], [$key, $var], $tmp);
            }
        }
        return str_replace($matches[0], $temp, $matches[0]);
    }

    /** Parses of a control structure IF.
     * <pre>
     * The templates are:
     *   [if=var]...[еndif.var]
     *   [if=var]
     *      [if=var1]...[еndif.var1]
     *   [еndif.var]
     *   [if=var[index]]...[еndif.var]
     * </pre>
     * Array variable $matches contains:
     *  - $matches[0] = part of template between control structures including them;
     *  - $matches[1] = variable name;
     *  - $matches[2] = first variable index;
     *  - $matches[3] = second variable index;
     *  - $matches[4] = part of template between control structures excluding them.
     *
     * @param  array  $matches Matches for control structure "if"
     * @return string          Parsed string
     */
    private function __if($matches) {
        if (!isset($this->vars[$matches[1]])) {
            return str_replace($matches[0], '', $matches[0]);
        }
        # [if=var]
        #     [if=var]...[/if]
        # [/if]
        if (!empty($matches[3])) {
            $var = $this->searchValueInArray($matches[3], $this->vars[$matches[1]]);
            if (empty($var)) {
                return str_replace($matches[0], '', $matches[0]);
            }
        }
        if (is_array($this->vars[$matches[1]])) {
            # [if=var]...{var[index]}...[/if]
            preg_match_all('/\{'.$matches[1].'(|\[(.*)\])\}/U', $matches[4], $sigs);
            if (!empty($sigs)) {
                foreach ($sigs[2] as $key => $value) {
                    if (!empty($this->vars[$matches[1]][$value])) {
                        $matches[4] = str_replace($sigs[0][$key], $this->vars[$matches[1]][$value], $matches[4]);
                    } else {
                        foreach ($this->vars[$matches[1]] as $i => $data) {
                            if (!empty($data[$value])) {
                                $matches[4] = str_replace($sigs[0][$key], $data[$value], $matches[4]);
                            }
                        }
                    }
                }
            }
        }
        return str_replace($matches[0], $matches[4], $matches[0]);
    }

    /** Parses of a control structure IF ELSE.
     * <pre>
     * The template is:
     *   [ifеlse=var]...[else]...[/elsе]
     *   [ifеlse=var[index]]...[else]...[/elsе.var]
     * </pre>
     *
     * @param  array  $matches Matches for control structure "if else"
     * @return string          Parsed string
     */
    private function __if_else($matches) {
        if (empty($this->vars[$matches[1]])) {
            return str_replace($matches[0], $matches[5], $matches[0]);
        }
        if (!empty($matches[3])) {
            $var = $this->searchValueInArray($matches[3], $this->vars[$matches[1]]);
            if (empty($var)) {
                return str_replace($matches[0], $matches[5], $matches[0]);
            }
        }
        return str_replace($matches[0], $matches[4], $matches[0]);
    }

    /** Shows element.
     * @param  array  $matches Matches for parse
     * @return string          Parsed string
     */
    private function __show($matches) {
        if (!empty($matches)) {
            $params = explode(',', $matches[1]);
            if (!empty($params[1]))
                 return str_replace($matches[0], call_user_func([$this, 'showElement'], $params[0], $params[1]), $matches[0]);
            else return str_replace($matches[0], call_user_func([$this, 'showElement'], $params[0]), $matches[0]);
        }
    }

    /** Localization.
     * <pre>
     * The template is:
     *   [ __string]
     * </pre>
     * Array variable $matches contains:
     *  - $matches[0] = part of template between control structures including them;
     *  - $matches[1] = part of template between control structures excluding them.
     *
     * @param  array  $matches Matches for control structure "if"
     * @return string          Parsed string
     */
    private function __translate($matches) {
        return str_replace($matches[0], __($matches[1]), $matches[0]);
    }

    /** Replaces constants and variables with their values.
     * <pre>
     * The templates are:
     *   {var}                     - constant or plain variable
     *   {var[index]}              - array of variables
     *   {var[index[x]][index[y]]} - array of variables
     * </pre>
     *
     * @param  array  $matches Matches for control structure "if"
     * @return string          Parsed string
     */
    private function __value($matches) {
        # CONSTANT
        if (defined($matches[1])) {
            return str_replace($matches[0], constant($matches[1]), $matches[0]);
        }
        if (isset($this->vars[$matches[1]])) {
            # {var[index][subindex[x]]}
            preg_match_all('/\{'.$matches[1].'\[(.*)\]\[(.*)\]\}/U', $matches[0], $sigs);
            if (!empty($sigs)) {
                foreach($sigs[1] as $k => $idx) {
                    if (!empty($idx)) {
                        foreach ($this->vars[$matches[1]] as $var) {
                            $matches[0] = str_replace($sigs[0][$k], $var[$idx][$sigs[2][$k]], $matches[0]);
                        }
                        return $matches[0];
                    }
                }
            }
            if (isset($matches[3])) {
                # {var[index][subindex]}
                return str_replace($matches[0], $this->vars[$matches[1]][$matches[3]], $matches[0]);
            }
            if (is_array($this->vars[$matches[1]])) {
                #  {var[index]}
                return str_replace($matches[0], current($this->vars[$matches[1]]), $matches[0]);
            }
            if (array_key_exists($matches[1], $this->vars)) {
                # Uses key of variables array: {var[index]}
                return str_replace($matches[0], $this->vars[$matches[1]], $matches[0]);
            }
        }
        return $matches[0];
    }

    /** Recursively searches of the value in a multidimensional array.
     * @param  mixed $needle   The desired value
     * @param  array $haystack Array to search
     * @return mixed           The value of the key
     */
    private function searchValueInArray($needle, $haystack) {
        $result = '';
        foreach ($haystack as $key => $value) {
            if ($needle == $key) {
                $result = $value;
            }
            if (is_array($value)) {
                $result = $this->searchValueInArray($needle, $value);
            }
        }
        return $result;
    }

    /** Shows the content.
     * @param  string $element    What to show
     * @param  string $parameters Parameters - type of the content block
     * @return string
     */
    private function showElement($element, $parameters = '') {
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

            case 'title':
                $title = CONFIG::getValue('main', 'title');
                $pagename = SYSTEM::get('pagename');
                if (!empty($pagename)) {
                    $title = $title.' - '.$pagename;
                }
                return $title;

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

            case 'copyright':
                if ($parameters) {
                    $TPL = new TEMPLATE('copyright.tpl');
                    return CMS::call('SYSTEM')->showWindow('__NOWINDOW__', $TPL->parse());
                } else {
                    return IDX_POWERED.'<br />'.IDX_COPYRIGHT;
                }
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
                    return CMS::call('SYSTEM')->showWindow('Error', $error, 'center', 'error');
                }
            break;
        }
    }

    /** Parses template with given variables.
     * @param  array  $params Template variables
     * @return string         Parsed template
     */
    public function parse($params = null) {
        $this->vars = $params;
        $this->tpl  = preg_replace($this->patterns['die'], '', $this->tpl);
        $tpl = preg_replace_callback($this->patterns['foreach'],   [&$this, '__foreach'],   $this->tpl);
        $tpl = preg_replace_callback($this->patterns['each'],      [&$this, '__each'],      $tpl);
        $tpl = preg_replace_callback($this->patterns['ifelse'],    [&$this, '__if_else'],   $tpl);
        $tpl = preg_replace_callback($this->patterns['if'],        [&$this, '__if'],        $tpl);
        $tpl = preg_replace_callback($this->patterns['for'],       [&$this, '__for'],       $tpl);
        $tpl = preg_replace_callback($this->patterns['translate'], [&$this, '__translate'], $tpl);
        $tpl = preg_replace_callback($this->patterns['value'],     [&$this, '__value'],     $tpl);
        $tpl = preg_replace_callback($this->patterns['show'],      [&$this, '__show'],      $tpl);
        return $tpl;
    }
}
