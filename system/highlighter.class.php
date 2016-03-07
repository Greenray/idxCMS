<?php
/**
 * HTML and PHP highlighter.
 *
 * @program   idxCMS: Flat Files Content Management System
 * @version   4.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      system/highlighter.class.php
 * @package   Core
 */

class HIGHLIGHTER extends PARSER {

    /** @var string Tag attribute color */
    private $_attr = 'color:green;';

    /** @var string inline style for comment */
    private $_comment = 'font-style:italic;color:gray;';

    /** @var integer Parsing counter */
    private $_current = 0;

    /** @var string The results of parsing */
    private $_output = '';

    /** @var string PHP tag color */
    private $_php = 'color:black;';

    /** @var string Tag name color */
    private $_tag = 'color:blue;';

    /** @var string Text to parse */
    private $_text = '';

    /** @var string Tag value color */
    private $_value = 'color:red;';

    /**
     * Class initialization.
     *
     * @param  string $code Code for highlighting
     */
    public function __construct($code) {
        $this->_text = $code;
    }

    /**
     * Comment highlighter.
     *
     *  @return string HTML span block with highlighted comment
     */
    private function highlightComment() {
        $this->_output .= '<span style="'.$this->_comment.'">&lt;';
        for ($this->_current += 1; ($this->_current < mb_strlen($this->_text)) && ($this->_text[$this->_current] !== '>'); $this->_current++) {
            $this->_output .= $this->_text[$this->_current];
        }
        $this->_output .= '&gt;</span>';
    }

    /**
     * PHP code highlighter.
     *
     * @return string HTML span block with highlighted php code
     */
    private function highlightPhp() {
        $this->_output .= '<span style="'.$this->_php.'">&lt;';
        ++$this->_current;
        $this->_output .= $this->_text[$this->_current];
        ++$this->_current;

        while ($this->_text[$this->_current] !== '?') {
            $nextChar = $this->_text[$this->_current + 1];
            if ($this->_text[$this->_current] === ' ') {
                $this->_output .= '&nbsp;';

            } elseif ($this->_text[$this->_current] === '<') {
                $this->_output .= '&lt;';

            } elseif ($this->_text[$this->_current] === '>') {
                $this->_output .= '&gt;';

            } elseif ($this->_text[$this->_current] === "\r") {
                if ($nextChar === "\n") {
                    $this->_output .= str_replace("\r", '<br />', $this->_text[$this->_current]);
                    ++$this->_current;
                    $this->_output .= str_replace("\n", '', $this->_text[$this->_current]);
                } else {
                    $this->_output .= str_replace("\r", '<br />', $this->_text[$this->_current]);
                }
            } elseif ($this->_text[$this->_current] === "\n") {
                if ($nextChar === "\r") {
                    $this->_output .= str_replace("\n", '<br />', $this->_text[$this->_current]);
                    ++$this->_current;
                    $this->_output .= str_replace("\r", '', $this->_text[$this->_current]);
                } else {
                    $this->_output .= str_replace("\n", '<br />', $this->_text[$this->_current]);
                }
            } else {
                $this->_output .= $this->_text[$this->_current];
            }
            ++$this->_current;
        }
        $this->_output .= '?';
        ++$this->_current;
        $this->_output .= '&gt;</span>';
    }

    /**
     * Tag highlighter.
     *
     * @return string HTML span block with highlighted tag
     */
    private function highlightTag() {
        $this->_output .= '<span style="'.$this->_tag.'">&lt;';
        $parsedTag = FALSE;
        #
        # Parse full tag
        #
        $length = mb_strlen($this->_text);
        for ($this->_current += 1; ($this->_current < $length) && ($this->_text[$this->_current] !== '>'); $this->_current++) {
            if (($this->_text[$this->_current] === ' ') && !$parsedTag) {
                $parsedTag = TRUE;
                $this->_output .= '</span>';
            } elseif (($this->_text[$this->_current] !== ' ') && $parsedTag) {
                $attribute = '';
                #
                # While we are in the tag
                #
                for (; ($this->_current < $length) && ($this->_text[$this->_current] !== '>'); $this->_current++) {
                    if ($this->_text[$this->_current] !== '=') {
                        $attribute .= $this->_text[$this->_current];
                    } else {
                        $this->_output .= '<span style="'.$this->attr.'">'.$attribute.'</span>=';
                        $attribute = '';
                        $value     = '';
                        $quote     = '';
                        for ($this->_current += 1; ($this->_current < $length) && ($this->_text[$this->_current] !== '>') && ($this->_text[$this->_current] !== ' '); $this->_current++) {
                            if ($this->_text[$this->_current] === '"' || $this->_text[$this->_current] === "'") {
                                $quote  = $this->_text[$this->_current];
                                $value .= $quote;
                                #
                                # Attribute value
                                #
                                for ($this->_current += 1; ($this->_current < $length) && ($this->_text[$this->_current] !== '>') && ($this->_text[$this->_current] !== $quote); $this->_current++) {
                                    if ($this->_text[$this->_current] === '<') {
                                        if ($this->_text[$this->_current + 1] === '?') {
                                            $value .= '<span style="'.$this->_php.'">&lt;';
                                            ++$this->_current;
                                            $value .= $this->_text[$this->_current];
                                            $this->_current += 1;

                                            while ($this->_text[$this->_current] !== '?') {
                                                $value .= $this->_text[$this->_current];
                                                ++$this->_current;
                                            }
                                            $value .= '?';
                                            ++$this->_current;
                                            $value .= '&gt;</span>';

                                        } else {
                                            $value .= '&lt;';
                                            for ($this->_current += 1; $this->_text[$this->_current] !== '>'; $this->_current++) {
                                                $value .= $this->_text[$this->_current];
                                            }
                                            $value .= '&gt;';
                                        }
                                    } else $value .= $this->_text[$this->_current];
                                }
                                $value .= $quote;

                            } else $value .= $this->_text[$this->_current];
                        }
                        $this->_output .= '<span style="'.$this->_value.'">'.$value.'</span>';
                        break;
                    }
                }
                if (!empty($attribute)) {
                    $this->_output .= '<span style="'.$this->attr.'">'.$attribute.'</span>';
                }
            }
            if ($this->_text[$this->_current] === '>') {
                break;

            } else {
                $this->_output .= $this->_text[$this->_current];
            }
        }
        if ($this->_text[$this->_current] === '>' && !$parsedTag) {
            $this->_output .= '&gt;</span>';
            ++$this->_current;
        }
        --$this->_current;
    }

    /**
     * Hightlights string.
     *
     * @return string Highlighted html
     */
    public function highlight() {
        $regexp = ["#echo#is" => '<span style="color:purple;">echo</span>'];
        $length = mb_strlen($this->_text) - 1;
        for ($this->_current = 0; $this->_current < $length; $this->_current++) {
            $nextChar = $this->_text[$this->_current + 1];

            if ($this->_text[$this->_current] === ' ') {
                $this->_output .= str_replace(' ', '&nbsp;', $this->_text[$this->_current]);
                
            } elseif ($this->_text[$this->_current] === '<') {
                if ($nextChar === '!') {
                    $this->highlightComment();
                } elseif ($nextChar === '?') {
                    $this->highlightPhp();
                } else {
                    $this->highlightTag();
                }
            } elseif ($this->_text[$this->_current] === "\r") {
                if ($nextChar === "\n") {
                    $this->_output .= str_replace("\r", '<br />', $this->_text[$this->_current]);
                    ++$this->_current;
                    $this->_output .= str_replace("\n", '', $this->_text[$this->_current]);
                } else {
                    $this->_output .= str_replace("\r", '<br />', $this->_text[$this->_current]);
                }
            } elseif ($this->_text[$this->_current] === "\n") {
                if ($nextChar === "\r") {
                    $this->_output .= str_replace("\n", '<br />', $this->_text[$this->_current]);
                    ++$this->_current;
                    $this->_output .= str_replace("\r", '', $this->_text[$this->_current]);
                } else {
                    $this->_output .= str_replace("\n", '<br />', $this->_text[$this->_current]);
                }
            } else {
                $this->_output .= $this->_text[$this->_current];
            }
        }
        $this->_output = preg_replace(array_keys($regexp), array_values($regexp), $this->_output);
        return '<code>'.$this->_output.'</code>';
    }
}
