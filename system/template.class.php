<?php
# idxCMS version 2.2
# Copyright (c) 2014 Greenray greenray.spb@gmail.com

class TEMPLATE {

    private $vars = array();
    private $tpl  = '';
    private $patterns = array(
        'die'       => "#<\?php(.*?)\?>#is",
        'each'      => "#\[each=(.*?)\](.*?)\[endeach.\\1\]#is",
        'foreach'   => "#\[foreach=([\w\_\-]*).([\w\_\-]*).([\w\_\-]*)\](.*?)\[endforeach.\\1\]#is",
        'for'       => "#\[for=(.*?)\](.*?)\[endfor\]#is",
        'if'        => "#\[if=(.*?)(|\[(.*?)\])\](.*?)\[endif\]#is",
        'ifelse'    => "#\[ifelse=(.*?)(|\[(.*?)\])\](.*?)\[else\](.*?)\[endelse\]#is",
        'translate' => "#\[__(.*?)\]#is",
        'value'     => "#\{([\-\#\w]+)(|\[(.*?)\])\}#is",
        'show'      => "#\[show=(.*?)\]#is"
    );

    # There are three templates directories:
    # - ./skins/CURRENT_SKIN/ - created (modified) templates by site's disigner;
    # - ./system/modules/__MODULE__/ - original module templates;
    # - ./system/templates/ - original templates for two or more modules;
    # The last is a variable with template code.
    public function __construct($template) {
        $tpl = basename($template);
        if (file_exists(CURRENT_SKIN.$tpl.'.php')) {
            $this->tpl = file_get_contents(CURRENT_SKIN.$tpl.'.php');
        } elseif (file_exists($template.'.php')) {
            $this->tpl = file_get_contents($template.'.php');
        } elseif (file_exists(TEMPLATES.$tpl.'.php')) {
            $this->tpl = file_get_contents(TEMPLATES.$tpl.'.php');
        } else {
            $this->tpl = $template;
        }
    }

    # Parse control structure FOREACH
    # The template is:
    # - [foreac=var1.var2.var3]...[endforeach.var1]
    private function __foreach($matches) {
        $temp = '';
        if (!empty($this->vars[$matches[1]])) {
            foreach ($this->vars[$matches[1]] as $key => $var) {
                # Parsing of structure [if]...[endif]
                preg_match($this->patterns['if'], $matches[4], $sigs);
                if (!empty($sigs)) {
                    if (!empty($var)) {
                        $tmp = str_replace($sigs[0], $sigs[4], $matches[4]);
                    } else {
                        $tmp = str_replace($sigs[0], '', $matches[4]);
                    }
                } else $tmp = $matches[4];
                # Parsing of structure {var}
                $temp .= str_replace(array('{'.$matches[2].'}', '{'.$matches[3].'}'), array($key, $var), $tmp);
            }
        }
        return str_replace($matches[0], $temp, $matches[0]);
    }

    # Parse control structure EACH.
    # The template is:
    # - [each=var]...[endeach.var]
    # - [each=var[index]]...[endeach.var[index]]
    private function __each($matches) {
        $temp = '';
        if (!empty($this->vars[$matches[1]])) {
            foreach ($this->vars[$matches[1]] as $key => $var) {
                $tpl = $matches[2];
                if (is_array($var)) {
                    preg_match_all('#\[each='.$matches[1].'\[(.*)\]\](.*?)\[endeach.'.$matches[1].'\[\\1\]\]#is', $tpl, $sigs);
                    if (!empty($sigs[0])) {
                        $tmp = $sigs[2][0];
                        preg_match($this->patterns['if'], $tmp, $ifsigs);
                        if (!empty($ifsigs)) {
                            $tmpl = '';
                            foreach ($var[$ifsigs[1]] as $i => $values) {
                                if (!empty($values[$ifsigs[3]])) {
                                    $tmpl .= str_replace($ifsigs[0], $ifsigs[4], $tmp);
                                } else {
                                    $tmpl .= str_replace($ifsigs[0], '', $tmp);
                                }
                                foreach ($values as $k => $value) {
                                    $tmpl = str_replace('{'.$sigs[1][0].'['.$k.']}', $value, $tmpl);
                                }
                            }
                            $tpl = str_replace($sigs[0][0], $tmpl, $tpl);
                        }
                        preg_match($this->patterns['ifelse'], $tmp, $ifsigs);
                        if (!empty($ifsigs)) {
                            $tmpl = '';
                            if (!empty($var[$ifsigs[1]])) {
                                foreach ($var[$ifsigs[1]] as $i => $values) {
                                    if (!empty($values[$ifsigs[3]])) {
                                        $tmpl .= str_replace($ifsigs[0], $ifsigs[4], $tmp);
                                    } else {
                                        $tmpl .= str_replace($ifsigs[0], $ifsigs[5], $tmp);
                                    }
                                    foreach ($values as $k => $value) {
                                        $tmpl = str_replace('{'.$sigs[1][0].'['.$k.']}', $value, $tmpl);
                                    }
                                }
                            }
                            $tpl = str_replace($sigs[0][0], $tmpl, $tpl);
                        }
                        # Parsinfg of template variables {var[index]}
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
                    preg_match_all($this->patterns['if'], $tpl, $sigs);
                    if (!empty($sigs)) {
                        foreach ($sigs[1] as $k => $idx) {
                            if (!empty($var[$sigs[3][$k]])) {
                                $tpl = str_replace($sigs[0][$k], $sigs[4][$k], $tpl);
                            } else {
                                $tpl = str_replace($sigs[0][$k], '', $tpl);
                            }
                        }
                    }
                    preg_match_all($this->patterns['ifelse'], $tpl, $sigs);
                    if (!empty($sigs)) {
                        foreach ($sigs[1] as $k => $idx) {
                            $val = SearchInArray($sigs[3][$k], $var);
                            if (!empty($var[$sigs[3][$k]]) || !empty($val)) {
                                $tpl = str_replace($sigs[0][$k], $sigs[4][$k], $tpl);
                            } else {
                                $tpl = str_replace($sigs[0][$k], $sigs[5][$k], $tpl);
                            }
                        }
                    }
                    # Parsinfg of template variables {var[index][subindex]}
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
                    # Parsinfg of template variables {var[index]}
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
                    $temp .= str_replace('{'.$matches[1].'}', $var, $tpl);   # Parsing of structure {var}
                }
            }
        }
        return str_replace($matches[0], $temp, $matches[0]);
    }
    
    # Parse of a control structure FOR.
    # The template is:
    # - [for=x.var]...[endfor]
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

    # Parse of a control structure IF ELSE.
    # The template is:
    # - [ifelse=var]...[else]...[endelse]
    private function __if_else($matches) {
        if (empty($this->vars[$matches[1]])) {
            return str_replace($matches[0], $matches[5], $matches[0]);
        }
        if (!empty($matches[3])) {
            $var = SearchInArray($matches[3], $this->vars[$matches[1]]);
            if (empty($var)) {
                return str_replace($matches[0], $matches[5], $matches[0]);
            }
        }
        return str_replace($matches[0], $matches[4], $matches[0]);
    }

    # Parse of a control structure IF.
    # The template is:
    # - [if=var]...[endif]
    # - [if=var[index]]...[endif]
    private function __if($matches) {
        if (empty($this->vars[$matches[1]])) {
            return str_replace($matches[0], '', $matches[0]);
        }
        if (!empty($matches[3])) {
            $var = SearchInArray($matches[3], $this->vars[$matches[1]]);
            if (empty($var)) {
                return str_replace($matches[0], '', $matches[0]);
            }
        }
        if (is_array($this->vars[$matches[1]])) {
            # Parsinfg of template variables {var[index]}
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

    # Localization.
    # The template is:
    # - [__var]
    private function translate($matches) {
        return str_replace($matches[0], __($matches[1]), $matches[0]);
    }

    # Replaces constants and variables with their values.
    # Templates are:
    # - {var}        - constant or plain variable;
    # - {var[index]} - array of variables.
    private function value($matches) {
        # Show constant.
        if (defined($matches[1])) {
            return str_replace($matches[0], constant($matches[1]), $matches[0]);
        }
        if (isset($this->vars[$matches[1]])) {
            # Parsinfg of template variables {var[index][subindex]}
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
                return str_replace($matches[0], $this->vars[$matches[1]][$matches[3]], $matches[0]);
            }
            if (is_array($this->vars[$matches[1]])) {
                return str_replace($matches[0], current($this->vars[$matches[1]]), $matches[0]);
            }
            if (array_key_exists($matches[1], $this->vars)) {
                return str_replace($matches[0], $this->vars[$matches[1]], $matches[0]);
            }
        }
        return $matches[0];
    }

    private function element($matches) {
        if (!empty($matches)) {
            $params = explode(',', $matches[1]);
            if (!empty($params[1])) {
                 return str_replace($matches[0], call_user_func('ShowElement', $params[0], $params[1]), $matches[0]);
            } else {
                return str_replace($matches[0], call_user_func('ShowElement', $params[0]), $matches[0]);
            }
        }
    }

    public function parse($params = '', $secure = TRUE) {
        $this->vars = $params;
        $this->tpl = preg_replace($this->patterns['die'], '', $this->tpl);
        $tpl = preg_replace_callback($this->patterns['foreach'], array(&$this, '__foreach'), $this->tpl);
        $tpl = preg_replace_callback($this->patterns['each'], array(&$this, '__each'), $tpl);
        $tpl = preg_replace_callback($this->patterns['ifelse'], array(&$this, '__if_else'), $tpl);
        $tpl = preg_replace_callback($this->patterns['if'], array(&$this, '__if'), $tpl);
        $tpl = preg_replace_callback($this->patterns['for'], array(&$this, '__for'), $tpl);
        $tpl = preg_replace_callback($this->patterns['translate'], array(&$this, 'translate'), $tpl);
        $tpl = preg_replace_callback($this->patterns['value'], array(&$this, 'value'), $tpl);
        $tpl = preg_replace_callback($this->patterns['show'], array(&$this, 'element'), $tpl);
        return $tpl;
    }
}
?>