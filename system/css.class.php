<?php
/**
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.2
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      classes/css.class.php
 * @package   Template
 */

/** Templates parser. */
class CSS {

    /** @var boolean TRUE if cache is allowed */
    private $cache;

    /** @var string CSS file that is executing */
	private $css = '';

    /* @var array The prefixes of browsers */
    private $styles = [
        'background-origin'   => ['-webkit-', '-moz-', '-o-', ''],
        'background-size'     => ['-webkit-', '-moz-', '-o-', ''],

        'border-radius'              => ['-webkit-', '-moz-', ''],
        'border-top-left-radius'     => ['-webkit-', '-moz-', ''],
        'border-top-right-radius'    => ['-webkit-', '-moz-', ''],
        'border-bottom-right-radius' => ['-webkit-', '-moz-', ''],
        'border-bottom-left-radius'  => ['-webkit-', '-moz-', ''],

        'border-image'        => ['-webkit-', '-moz-', '-o-', ''],
        'border-image-outset' => ['-webkit-', '-moz-', '-o-', ''],
        'border-image-repeat' => ['-webkit-', '-moz-', '-o-', ''],
        'border-image-source' => ['-webkit-', '-moz-', '-o-', ''],
        'border-image-width'  => ['-webkit-', '-moz-', '-o-', ''],

        'box-shadow'          => ['-webkit-', '-moz-', ''],

        'box-sizing'          => ['-webkit-', '-moz-', ''],

        'perspective'         => ['-webkit-', '-moz-', ''],
        'perspective-origin'  => ['-webkit-', '-moz-', ''],

        'transform'           => ['-webkit-', '-moz-', '-ms-', '-o-', ''],
        'transform-origin'    => ['-webkit-', '-moz-', '-ms-', '-o-', ''],
        'transform-style'     => ['-webkit-', '-moz-', ''],

        'transition'          => ['-webkit-', '-moz-', '-o-', ''],
        'transition-delay'    => ['-webkit-', '-moz-', '-o-', ''],
        'transition-duration' => ['-webkit-', '-moz-', '-o-', ''],
        'transition-property' => ['-webkit-', '-moz-', '-o-', ''],

        'transition-timing-function' => ['-webkit-', '-moz-', '-o-', ''],

        'linear-gradient' => ['-webkit-', '-moz-', '-o-', ''],
        'radial-gradient' => ['-webkit-', '-moz-', '-o-', ''],
        'repeating-linear-gradient' => ['-webkit-', '-moz-', '-o-', ''],
        'repeating-radial-gradient' => ['-webkit-', '-moz-', '-o-', '']
    ];

	/**
     * Class constructor.
     *
     * @param boolean Is cache allowed?
     */
	public function __construct($cache) {
        $this->cache = $cache;
    }

    /** Handles directives "@font-face" and "@import". */
    private function import() {
        preg_match_all('/\@font\-face[^\}]*\}/', $this->css, $match);
        if (!empty($match[0])) {
            $this->css = preg_replace('/\@font\-face[^\}]*\}/', '', $this->css);
            $this->css = implode(LF, $match[0]).LF.$this->css;
        }
        preg_match_all('/\@import[^\;]*\;/', $this->css, $match);
        if (!empty($match[0])) {
            $this->css = preg_replace('/\@import[^\;]*\;/', '', $this->css);
            $this->css = implode(LF, $match[0]).LF.$this->css;
        }
    }

    /**
     * Generates CSS3 properties with browser-specific prefixes.
     * The prefix list is not complete, it contains only the used properties in the CMS.
     * So it can easily be extended.
     */
    private function setPrefixes() {
        #
        # Remove comments
        #
        $this->css = preg_replace('#(\/\*).*?(\*\/)#s', '', $this->css);
        $values = [];
        foreach ($this->styles as $property => $styles) {
            preg_match_all('#[^-\{]'.$property.'#s', $this->css, $result);
            if (!empty($result[0])) {
                $values[] = array_unique($result[0]);
            }
        }
        foreach ($values as $value) {
            $value = trim($value[0]);
            #
            # Search properties from $this->styles list
            #
            preg_match_all('#'.$value.':[a-zA-Z0-9\.\-\#|\d\s]+?;|[a-zA-Z\-]+: '.$value.'[\S+].+?;#s', $this->css, $keys);
            foreach ($keys[0] as $property) {
                foreach ($this->styles as $style => $prefixes) {
                    if ($style === $value) {
                        $result = '';
                        foreach ($prefixes as $match) {
                            $pos = strpos($property, $value);
                            if ($pos === 0) {
                                $result .= $match.$property;
                            } else {
                                $parts = explode(':', $property);
                                $parts[0] = $parts[0].':';
                                $parts[1] = trim($parts[1]);
                                $parts[1] = $match.$parts[1];
                                $result  .= implode($parts);
                            }
                        }
                        if (isset($parts)) {
                               $this->css = str_replace($property, $result, $this->css);
                               #
                               # Exclude properties like "left-margin", "font-face", etc.
                               #
                        } else $this->css = preg_replace('/[^-]'.$property.'/', $result, $this->css);
                    }
                }
            }
        }
    }

    /**
     * Generates CSS3 properties with browser-specific prefixes.
     * The list of prefixes is not yet complete.
     * Then we remove unneeded characters, see comments.
     *
     * @param  string $file CSS file
     * @return string       Compressed CSS
     */
    public function compress($file) {
        $file = str_replace('./', '/', $file);
        $css = basename($file);
        if ($this->cache === TRUE) {
            $this->css = $this->getFromCache($css);
        } else {
            $this->css = file_get_contents($_SERVER['DOCUMENT_ROOT'].$file);
            #
            # Processing directives @font-face and @import
            #
            $this->import();
            #
            # Set the prefixes of browsers
            #
            $this->setPrefixes();
            #
            # Remove newline characters and tabs
            #
            $this->css = str_replace(["\r\n", "\r", "\n", "\t"], '', $this->css);
            #
            # Remove two or more consecutive spaces
            #
            $this->css = preg_replace('# {2,}#', '', $this->css);
            $this->css = str_replace([' 0px', ' 0em', ' 0%', ' 0ex', ' 0cm', ' 0mm', ' 0in', ' 0pt', ' 0pc'], '0', $this->css);
            $this->css = str_replace([':0px', ':0em', ':0%', ':0ex', ':0cm', ':0mm', ':0in', ':0pt', ':0pc'], ':0', $this->css);
            #
            # Remove the spaces, if a curly bracket, colon, semicolon or comma is placed before or after them
            #
            $this->css = preg_replace('#\s*([\{:;,])\s*#', '$1', $this->css);
            #
            # Place the compiled data into cache
            #
            file_put_contents(CACHE.$css, $this->css, LOCK_EX);
        }
        return $this->css;
    }

    /**
	 * Gets a compiled file from the cache.
     *
     * @param  string $file CSS file
     * @return mixed        Data from cache or FALSE
	 */
	private function getFromCache($file) {
        return (file_exists(CACHE.$file)) ? file_get_contents(CACHE.$file) : FALSE;
	}
}
