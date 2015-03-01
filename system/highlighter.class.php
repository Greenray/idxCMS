<?php
# idxCMS Flat Files Content Management Sysytem

/** HTML and PHP highlighter.
 *
 * @file      system/highlighter.class.php
 * @version   2.3
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Core
 */

class HIGHLIGHTER extends PARSER {

    /** The results of parsing.
     * @var string
     */
    private $output = '';

    /** Text to parse.
     * @var string
     */
    private $text = '';

    /** Start parsing from the begining.
     * @var integer
     */
    private $current = 0;

    /** Tag name color.
     * @var string
     */
    private $tag = 'color:blue;';

    /** Tag attribute color.
     * @var string
     */
    private $attr = 'color:green;';

    /** Tag value color.
     * @var string
     */
    private $value = 'color:red;';
    
    /** php tag color.
     * @var string
     */
    private $php = 'color:black';

    /** inline style for comment.
     * @var string
     */
    private $comment = 'font-style:italic;color:gray;';

    /** Class initialization.
     * @param  string $code Code for highlighting
     * @return void
     */
    public function __construct($code) {
        $this->text = $code;
    }

    /** Comment highlighter.
     * @return string HTML span block with highlighted comment
     */
    private function highlightComment() {
        $this->output .= '<span style="'.$this->comment.'">&lt;';
        for ($this->current += 1; ($this->current < mb_strlen($this->text)) && ($this->text[$this->current] !== '>'); $this->current++) {
            $this->output .= $this->text[$this->current];
        }
        $this->output .= '&gt;</span>';
    }

    /** php code highlighter.
     * @return string HTML span block with highlighted php code
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
     * @return string HTML span block with highlighted tag
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

    /** Hightlights string.
     * @return string Highlighted html
     */
    public function highlight() {
        $regexp = ["#echo#is" => '<span style="color:purple;">echo</span>'];
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
