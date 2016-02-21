<?php
/**
 * @program   idxCMS: Flat Files Content Management System
 * @version   3.2
 * @author    David Casado Martínez <tokkara@gmail.com>
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011-2015 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      classes/template.php
 * @package   Template
 */

/** idxCMS templates parser. */
class TEMPLATE {

    /** @var string Template content */
    private $code;

    /** @var array Errors in the template code */
	private $errors;

	/** @var string Name of the template file that is executing */
	private $file;

	/** @var integer Line of the template content */
	private $line;

	/** @var array Array of the template modifiers */
	private $modifiers;

	/** @var array Array of the template options */
	private $options;

    /* @var The prefixes of browsers */
    private $styles = [
        'background-origin'   => ['-webkit-', '-moz-', '-o-', ''],
        'background-size'     => ['-webkit-', '-moz-', '-o-', ''],

        'border-radius'       => ['-webkit-', '-moz-', ''],

        'border-top-left-radius'     => ['-webkit-', '-moz-', ''],
        'border-top-right-radius'    => ['-webkit-', '-moz-', ''],
        'border-bottom-right-radius' => ['-webkit-', '-moz-', ''],
        'border-bottom-left-radius'  => ['-webkit-', '-moz-', ''],

        'border-image'        => ['-webkit-', '-moz-', ''],
        'border-image-outset' => ['-webkit-', '-moz-', ''],
        'border-image-repeat' => ['-webkit-', '-moz-', ''],
        'border-image-source' => ['-webkit-', '-moz-', ''],
        'border-image-width'  => ['-webkit-', '-moz-', ''],

        'box-shadow'          => ['-webkit-', '-moz-', ''],

        'box-sizing'          => ['-webkit-', '-moz-', ''],

        'perspective'         => ['-webkit-', '-moz-', ''],
        'perspective-origin'  => ['-webkit-', '-moz-', ''],

        'transform'           => ['-webkit-', '-moz-', '-ms-', ''],
        'transform-origin'    => ['-webkit-', '-moz-', '-ms-', ''],
        'transform-style'     => ['-webkit-', '-moz-', ''],

        'transition'          => ['-webkit-', '-moz-', '-o-', ''],
        'transition-delay'    => ['-webkit-', '-moz-', '-o-', ''],
        'transition-duration' => ['-webkit-', '-moz-', '-o-', ''],
        'transition-property' => ['-webkit-', '-moz-', '-o-', ''],

        'transition-timing-function' => ['-webkit-', '-moz-', '-o-', ''],
    ];

	/** @var array Array of the template variables */
	private $vars;

	/** @var array Array of the error and warning messages */
	private $warnings;

	/**
	 * Class constructor.
     *
	 * @param array $options Vector with the template parser options
	 */
	public function __construct($template, $options = []) {
        $this->vars = $this->modifiers = $this->warnings = [];
		$this->file = '';
		$this->line = 0;
        $this->errors    = [];
		$this->options   = [
            'compact'     => FALSE,
			'debug'       => FALSE,
            'allow_cache' => FALSE,
            'expired'     => FALSE,
			'error_func'  => ''
		];

        $this->options = array_replace($this->options, $options);
        $this->getTemplate($template);
        #
        # Remove php code from template
        #
        $this->code = preg_replace("#<\?php(.*?)\?>#is", '', $this->code);
	}

    /**
	 * Reads template.
     *
	 * @param  string $template Template filename with full path or template code
	 */
	private function getTemplate($template) {
        $this->file = $template;
        $template   = basename($template).'.php';
        #
        # The template of the first stage
        #
        if (file_exists(CURRENT_SKIN.$template)) {
            $this->code = file_get_contents(CURRENT_SKIN.$template);
            #
            # The template of the second stage
            #
        } elseif (file_exists($this->file.'.php')) {
            $this->code = file_get_contents($this->file.'.php');
            #
            # The template of the third stage
            #
        } elseif (file_exists(TEMPLATES.$template)) {
            $this->code = file_get_contents(TEMPLATES.$template);
            #
            # Direct code
            #
        } else {
            $this->code = $this->file;
            $this->file = '';
        }
	}

    /**
     * Sets a variable of the template.
     *
     * @param string $name  Variable name
     * @param mixed  $value Variable value
     */
    public function set($name, $value = '') {
        if (is_array($name))
             $this->vars = array_merge($this->vars, $name);
        else $this->vars[$name] = $value;
    }

    /**
	 * Parses a template file.
     *
	 * @param string $filename Path of the file to be parsed
	 */
	public function parse() {
		$this->warnings = [];
        set_error_handler([$this, 'templateErrorHandler']);

		ob_start();
		$result = $this->start();

		if ($this->options['debug'] && !empty($this->warnings))
			 echo implode('', $this->warnings).ob_get_clean();
		else ob_end_flush();

        restore_error_handler();
        return $result;
	}

    /**
	 * Executes the template file.
     *
	 * @return string $file File name
	 */
	final protected function start() {
        $old_file = $this->file;
		$old_line = $this->line;
		$code = '';
        #
		# Get code stored in cache file
        #
		$code_stored = $this->getFromCache($this->file, $code);
		if (!$code_stored) {
            #
			# Extract and parse cached code
            #
            $this->line = 0;
            $php_lines  = [];
            $lines = explode("\n", $this->code);
            $count = sizeof($lines);

            do {
                $php_lines[$this->line] = '<?php $this->line='.($this->line + 1).';?>'.$this->parseLine($lines[$this->line]);
                $this->line++;
            } while($this->line < $count && empty($this->errors));

            $code = empty($this->errors) ? preg_replace('#\[^;]?>([\s]*)<\?php#', '$1', implode("\n", $php_lines)) :
					'<?php $this->_error(E_USER_ERROR,\''.$this->errors[0].'\',FALSE,'.$this->line.'); ?>';

            $code = preg_replace_callback("#\{([\-\w]+)\}#is",  [&$this, 'value'],     $code);
            $code = preg_replace_callback("#\[__(.*?)\]#is",    [&$this, 'translate'], $code);
            $code = preg_replace_callback("#__(.*?)__#is",      [&$this, 'translate'], $code);
            $code = preg_replace_callback("#\[show=(.*?)\]#is", [&$this, 'show'],      $code);

            $css = preg_match_all("#\<link rel=\"stylesheet\" type=\"text/css\" href=\"(.*?)\" media=\"screen\" /\>#is", $code, $matches);
            if (!empty($matches[1])) {
                foreach($matches[1] as $key => $file) {
                    $code = str_replace($matches[0][$key], '<style type="text/css"><!--'.$this->compressCSS($file).'--></style>', $code);
                }
            }
		}
        #
		# Execute php code
        #
        ob_start();

		if (!eval('?>'.$code.'<?php return TRUE; ?>')) {
			$err_msg = ob_get_clean();
			$this->getEvalError($err_msg, $err_line);
			$err_msg = str_replace("';'", "':]'", $err_msg);
			$this->_error(E_USER_ERROR, $err_msg, $this->file, $err_line);
		}
        $result = ob_get_contents();
		ob_end_clean();
        #
		# Store the data in the cache
		#
        if (!$code_stored) $this->toCache($this->file, $code);

		$this->line = $old_line;
		$this->file = $old_file;

        return $result;
	}

    /**
	 * Parses a line of code.
     *
	 * @param  string $codeline Code line
	 * @return return Simphple Code transformed in php code
	 */
	private function parseLine($code) {
        #
		# Empty line
        #
		if (!trim($code)) return $code;
        #
		# Get html comments and key structures
        #
		$code = $this->htmlCommentsInKey($code, $htmlc);

        $k = 0;
		$keys    = [];
		$search  = '#\[([^\n\r{]*?(?:(\'|\\\\*")(?:.*?)(?<!\\\\)\2.*?)*?)\:([a-zA-Z]*)\]#e';
		$replace = '(($keys[$k]=array(\'$1\', \'$3\'))&&FALSE).\'KEY_STRUCTURE_\'.($k++).\'\'';

		$code = preg_replace($search, $replace, $code);

		for($i = 0; $i < $k; $i++) {
			$keys[$i] = '<?php echo '.$this->toPhpCode($keys[$i][0], $keys[$i][1]).'; ?>';
        }
		$code = preg_replace($search, $replace, $code);
        #
		# Check illegal php tags
        #
		if ($this->checkPhpCode($code)) return 'TRUE';

        $search   = '#(\$(?:[_a-zA-Z][_a-zA-Z0-9]*\.)?[_a-zA-Z][_a-zA-Z0-9]*)(?:\:([a-zA-Z]+))?#e';
		$replace  = '\'<?php echo \'.($this->toPhpCode(\'$1\', \'$2\')).\'; ?>\'';
		$code     = preg_replace($search, $replace, $code);
        #
		# Transform the key in php code
        #
		$search = [
            '#-HTML_COMMENT_([0-9]+)#e',
			'#KEY_STRUCTURE_([0-9]+)#e'
		];
		$replace = [
            '$htmlc[$1]',
			'$keys[$1]'
		];

		return preg_replace($search, $replace, $code);
	}

    /**
	 * Transforms the html comments in keys.
     *
	 * @param  string $code  Template code
	 * @param  array  $htmlc Array with all html comments transformed into php code
	 * @return string Template code
	 */
	private function htmlCommentsInKey($code, &$htmlc) {
		$i = 0;
        $htmlc   = [];
		$search  = '#(<!--.*?(?:(\'|\\\\*")(.*?)(?<!\\\\)\2.*?)*?-->)#e';
		$replace = '(($htmlc[$i]=\'$1\')&&FALSE).\'-HTML_COMMENT_\'.($i++).\'\'';

		$code = preg_replace($search, $replace, $code);
        #
		# Transform the html comments in php code
        #
		$search = [
            '#<!-- INCLUDE (.+?) -->#e',
			'#<!-- IF (.+?) -->#e',
			'#<!-- ELSEIF (.+?) -->#e',
			'#<!-- ELSE -->#',
			'#<!-- ENDIF -->#',
			'#<!-- SWITCH (.+) CASE (.+) -->#e',
			'#<!-- ENDSWITCH -->#',
			'#<!-- CASE (.+?) -->#e',
			'#<!-- DEFAULT -->#',
			'#<!-- BREAK -->#',
			'#<!-- FOREACH ([_a-zA-Z][_a-zA-Z0-9]*)[\t ]*=[\t ]*(.+?) -->#e',
			'#<!-- ENDFOREACH -->#',
			'#<!-- EXIT -->#',
			'#<!-- CONTINUE -->#',
		];
		$replace = [
            '$this->_include(\'$1\')',
			'$this->_if (\'$1\', FALSE)',
			'$this->_if (\'$1\', TRUE)',
			'<?php else:$this->line='.($this->line + 1).'; ?>',
			'<?php endif; ?>',
			'$this->_switch(\'$1\', \'$2\')',
			'<?php endswitch; ?>',
			'$this->_case(\'$1\')',
			'<?php default: ?>',
    		'<?php break; ?>',
			'$this->_foreach(\'$1\', \'$2\')',
			'<?php endfor; ?>',
			'<?php return TRUE; ?>',
			'<?php continue; ?>'
		];

		for($k = 0; $k < $i; $k++)
			$htmlc[$k] = preg_replace($search, $replace, $htmlc[$k]);

		return $code;
	}

    /**
	 * Trasnforms a template basic code (function, strings, variables & operators) in php code.
     *
	 * @param  string $code      Simphple basic code
	 * @param  string $modifiers Modifiers used in the template basic code
	 * @return string PHP code
	 */
	private function toPhpCode($code, $modifiers = '') {
        #
		# Transform the strings in key
        #
        $strings = [];
		$search  = '#(\'|\\\\*")(.*?)(?<!\\\\)\1#e';
		$replace = '($this->storeString($strings, \'$1\', \'$2\')).\'STRING\'';
		$code    = preg_replace($search, $replace, $code);
        #
		# Check illegal php tags
        #
		if ($this->checkPhpCode($code)) return 'TRUE';
        #
		# Check illegal characters
        #
		if ($this->checkIllegalCharacters($code)) return 'TRUE';
        #
		# Transform the variables and functions in key
        #
        $i = 0;
		$vars    = [];
		$search  = '#\$(?:([_a-zA-Z][_a-zA-Z0-9]*)\.)?([_a-zA-Z][_a-zA-Z0-9]*)#e';
		$replace = '(($vars[$i]=array(\'$1\', \'$2\'))&&FALSE).\'VARIABLE_\'.($i++).\'\'';
		$code    = preg_replace($search, $replace, $code);
        #
		# Add the modifiers
        #
		if ($modifiers) {
            $length     = strlen($modifiers);
            $code_parts = array_map('trim', explode(',', $code));
            $format     = '$this->executeModifier(\'%1$s\', %2$s)';
            foreach($code_parts as $i => $code) {
                for($j = 0; $j < $length; $j++) {
                    $code = sprintf($format, $modifiers[$j], $code);
                }
                $code_parts[$i] = $code;
            }
            $code = implode(',', $code_parts);
        }
        #
		# Transform keys in php code
        #
        $search = [
            '#VARIABLE_ISSET_([0-9]+)#e',
			'#VARIABLE_EMPTY_([0-9]+)#e',
			'#VARIABLE_([0-9]+)#e'
		];
		$replace = [
            '$this->toPhpVar($vars[$1][0], $vars[$1][1], \'isset\')',
			'$this->toPhpVar($vars[$1][0], $vars[$1][1], \'empty\')',
			'$this->toPhpVar($vars[$1][0], $vars[$1][1])'
		];
		$code = preg_replace($search, $replace, $code);

        $i = 0;
		$search  = '#STRING#e';
		$replace = '$strings[$i][0].$strings[$i][1].$strings[$i++][0]';
		$code    = preg_replace($search, $replace, $code);

		return $code;
	}

	/**
	 * Transforms a template variable in a php variable.
     *
	 * @param  string $prefix        Prefix of a template variable
	 * @param  string $name          Name of a template variable
	 * @param  string $function_name String indicating if this variable will use in a special function (isset or empty)
	 * @return string PHP variable
	 */
	private function toPhpVar($prefix, $name, $function_name = '') {
		if (($name == 'LINE') || ($name == 'FILE')) {
			if ($function_name == 'isset') return 'TRUE';
			if ($function_name == 'empty') return 'FALSE';

			return $name == 'LINE' ? '$this->line' : '$this->file';
		}

		$error_format = '$this->_error(E_USER_NOTICE, \'Undefined $%1$s variable\')';
		$var_format   =	(($function_name == 'isset') || ($function_name == 'empty')) ?
		                  $function_name.'(%2$s)' : '(isset(%2$s)?%3$s:'.$error_format.')';
        #
		# Simple variable
        #
		if (!$prefix) {
			$var = '$this->vars[\''.$name.'\']';
			return sprintf($var_format, $name, $var, $var);
		}
        #
		# Foreach variables
        #
		if ($prefix) {
			switch($name) {
				case '_CUR_':
					$var = '$'.$prefix.'_cur';
					return sprintf($var_format, $prefix.'._CUR_', $var, $var);

				case '_EVEN_':
					$var  = '$'.$prefix.'_cur';
					$expr = '$'.$prefix.'_cur%2!=0';
					return sprintf($var_format, $prefix.'._EVEN_', $var, $expr);

				case '_FIRST_':
					$var  = '$'.$prefix.'_cur';
					$expr = '$'.$prefix.'_cur==0';
					return sprintf($var_format, $prefix.'._FIRST_', $var, $expr);

				case '_LAST_':
					$var  = '$'.$prefix.'_cur';
					$expr = '$'.$prefix.'_cur+1==$'.$prefix.'_max';
					return sprintf($var_format, $prefix.'._LAST_', $var, $expr);

				case '_MAX_':
					$var = '$'.$prefix.'_max';
					return sprintf($var_format, $prefix.'._MAX_', $var, $var);

				case '_VAL_':
					$var = '$'.$prefix.'_var';
					return sprintf($var_format, $prefix.'_VAL_', $var, $var);

				default:
					$var      = '$'.$prefix.'_var';
					$var_name = $var.'[\''.$name.'\']';
					if ($function_name == 'empty')
						return 'empty('.$var_name.') || !is_array('.$var.')';

					$iss = 'isset('.$var_name.')&&is_array('.$var.')';
					return $function_name == 'isset' ?'('.$iss.')' : '('.$iss.'?'.$var_name.':'.sprintf($error_format, $prefix.'.'.$name).')';
			}
		}
	}

	/**
	 * Template empty() function.
     * This function checks if the template variable is not empty.
     *
	 * @param  boolean $... All parameters of the template empty() function
	 * @return boolean
	 */
	private function _empty() {
		$args = func_get_args();
		return in_array(TRUE, $args);
	}

    /**
	 * Trasnforms the FOREACH structure in php code.
     *
	 * @param  string $name Foreach name
	 * @param  string $code Code for FOREACH structure
	 * @return string PHP code
	 */
	private function _foreach($name, $code) {
		$ary  = '$'.$name.'_ary';
		$cur  = '$'.$name.'_cur';
		$max  = '$'.$name.'_max';
		$var  = '$'.$name.'_var';
		$code = $this->toPhpCode($code);

		return	'<?php '.
				$ary.'='.$code.';'.
				'if (is_array('.$ary.')) {'.
				$ary.'=array_values('.$ary.');'.
				'} else {'.
				$ary.'=[];'.
				'$this->_error(E_USER_WARNING, "The foreach argument should be an array");'.
				'}'.
				$max.'=sizeof('.$ary.');'.
				'for('.$cur.'=0; '.$cur.'<'.$max.'; '.$cur.'++):'.
				$var.'='.$ary.'['.$cur.'];'.
				'?>';
	}

    /**
	 * Transforms the IF structure in php code.
     *
	 * @param  string  $code   Code for IF structure
	 * @param  boolean $elseif Flag indicating if the structure is IF or ELSEIF
	 * @return string PHP code
	 */
	private function _if ($code, $elseif) {
		$code = $this->toPhpCode($code);
		$else = '';
		if ($elseif) {
			$else = 'else';
			$code = '($this->line='.($this->line + 1).')&&'.$code;
		}
		return '<?php '.$else.'if ('.$code.'): ?>';
	}

	/**
	 * Transforms the INCLUDE structure in php code.
     *
	 * @param  string $code Code for INCLUDE structure
	 * @return string PHP code
	 */
	private function _include($code) {
		return '<?php $this->includeFile('.$this->toPhpCode($code).');?>';
	}

    /**
	 * Template isset() function.
     * This function checks if the template variable is set.
     *
	 * @param  boolean $... All parameters of the template isset() function
	 * @return boolean
	 */
	private function _isset() {
		$args = func_get_args();
		return !in_array(FALSE, $args);
	}

    /**
	 * Transforms the SWITCH structure in php code.
     *
	 * @param  string  $code_switch Code for the SWITCH structure
	 * @param  unknown $code_case   Code for the first CASE structure
	 * @return string PHP code
	 */
	private function _switch($code_switch, $code_case) {
		$code_switch = $this->toPhpCode($code_switch);
		$code_case   = $this->toPhpCode($code_case);
		return '<?php switch('.$code_switch.'): case (($this->line='.($this->line + 1).')&&FALSE).'.$code_case.': ?>';
	}

    /**
	 * Transforms the CASE structure in php code.
     *
	 * @param  string $code Code for CASE structure
	 * @return string PHP code
	 */
	private function _case($code) {
		return '<?php case (($this->line='.($this->line + 1).')&&FALSE).'.$this->toPhpCode($code).': ?>';
	}

    /**
	 * Checks if there illegal characters in template code.
     *
	 * @param string $code Template code
	 * @return boolean TRUE if there illegal characters
	 */
	private function checkIllegalCharacters($code) {
		$char = [];
        #
		# Illegal characters
        #
		$search = '#(\[|\]|::|\?|:|->|\+=|-=|\*=|/=|\.=|%=|&=|\|=|\^=|<<=|>>=|\+\+|--|\{|\}|@|(?<![<>=!])=(?!=)|'.
				  '(?:^[^a-zA-Z0-9_$]*(?:return|for|function|foreach|as|while|if|switch|case|break)[^a-zA-Z0-9_]*$)|'.
				  '\$(?:[_a-zA-Z][_a-zA-Z0-9]*\.)?[_a-zA-Z][_a-zA-Z0-9]*[\s]*\()#';

		if (preg_match_all($search, $code, $char)) {
			$this->errors[] = 'syntax error, illegal string \\\''.$char[0][0].'\\\'.';
			return TRUE;
		}
        #
		# Incomplete strings
        #
		if ((stripos($code, '"') !== FALSE) || (stripos($code, '\'') !== FALSE)) {
			$this->errors[] = 'parse error.';
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Checks if there php tags in template code.
     *
	 * @param  string $code Template code
	 * @return boolean TRUE if there php tags
	 */
	private function checkPhpCode($code) {
		if (preg_match('#<\?php[\s]+|<\?[\s]+|[\s]+\?>#', $code)) {
			$this->errors[] = 'syntax error, illegal php tags';
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Executes a modifier.
     *
	 * @param  string $modifier Modifier character
	 * @param  mixed  $expr1    Parameter for the function associate to the modifier
	 * @return mixed Return the data returned for the function
	 */
	private function executeModifier($modifier, $expr1) {
        #
		# Check if the modifier exists
        #
		if (!isset($this->modifiers[$modifier]))
			$this->_error(E_USER_ERROR, "The modifier '$modifier' not exists.");

		return $this->executeFunction($this->modifiers[$modifier], $expr1);
	}

    /**
	 * Checks and executes an included template file.
     * This method is used in the template code.
     *
	 * @param string $file File name to be included
	 */
	private function includeFile($file) {
		$args = func_get_args();
		$this->start($file, $args);
	}

    /**
     * Localization.
     * <pre>
     * The template is:
     *   __string__
     * </pre>
     * Array variable $matches contains:
     *  - $matches[0] = part of template between control structures including them;
     *  - $matches[1] = part of template between control structures excluding them.
     *
     * @param  array  $matches Matches for control structure "if"
     * @return string          Parsed string
     */
    private function translate($matches) {
        return str_replace($matches[0], __($matches[1]), $matches[0]);
    }

    /**
     * Replaces constants and global variables with their values.
     * <pre>
     * The template is:
     *   {CONST} - global constant
     * </pre>
     *
     * @param  array  $matches Matches for control structure "IF"
     * @return string          Parsed string
     */
    private function value($matches) {
        if (defined($matches[1])) {
            return str_replace($matches[0], constant($matches[1]), $matches[0]);
        }
    }

    /**
     * Shows the content.
     * <pre>
     * The template is:
     *   [show=main,{module}@main]
     * - main - module type (main, box и т.д)
     * - {module} - module name
     * - main - template type (defined in /skins/_skin_name_/skin.php
     * </pre>
     *
     * @param  string $element    What to show
     * @param  string $parameters Parameters - type of the content block
     * @return string             Content
     */
    private function showElement($element, $parameters = '') {
        $output = '';
        switch($element) {

            case 'point':
                list($point, $template) = explode('@', $parameters);
                if (!empty(SYSTEM::$output[$point])) {
                    foreach (SYSTEM::$output[$point] as $i => $module) {
                        $output .= SYSTEM::showWindow(
                            $module[0],
                            $module[1],
                            $template
                        );
                    }
                }
                if (empty(SYSTEM::$output[$point]) && ($point !== 'up-center') && ($point !== 'down-center')) {
                    if (!empty(SYSTEM::$output['left'])) {
                        foreach (SYSTEM::$output['left'] as $i => $module) {
                            $output .= SYSTEM::showWindow(
                                $module[0],
                                $module[1],
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
                            $output .= SYSTEM::showWindow(
                                $box[0],
                                $box[1],
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
                $output .= '<meta name="description" content="'.$desc.'">'.LF;
                $keywords = CONFIG::getValue('main', 'keywords');
                if (!empty($meta['keywords'])) {
                    $words    = $keywords.','.$meta['keywords'];
                    $keywords = explode(',', $words);
                    $words    = array_unique($keywords);
                    $keywords = implode(',', $words);
                }
                $output .= '<meta name="keywords" content="'.$keywords.'">'.LF;
                if (CONFIG::getValue('enabled', 'rss')) {
                    $feeds = SYSTEM::get('feeds');
                    foreach ($feeds as $module => $title) {
                        $output .= '<link href="'.MODULE.'rss&amp;m='.$module.'" rel="alternate" type="application/xml" title="RSS '.$title[0].'" />'.LF;
                    }
                }
                return $output;

            case 'copyright':
                return IDX_POWERED.'<br />'.IDX_COPYRIGHT;
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
                    SYSTEM::showError($error);
                }
            break;
        }
    }

    /**
     * Shows element.
     *
     * @param  array  $matches Matches for parse
     * @return string          Parsed string
     */
    private function show($matches) {
        if (!empty($matches)) {
            $params = explode(',', $matches[1]);
            if (!empty($params[1])) {
                return str_replace($matches[0], call_user_func([$this, 'showElement'], $params[0], $params[1]), $matches[0]);
            } else {
                return str_replace($matches[0], call_user_func([$this, 'showElement'], $params[0]), $matches[0]);
            }
        }
    }

    /**
	 * Stores a template code string in array.
     *
	 * @param  array  $strings Array used for store the string
	 * @param  string $quot    Type of quote (' or ")
	 * @param  string $string  String stored in array
	 * @return string Empty string
	 */
	private function storeString(&$strings, $quot, $string) {
        #
		# Delete the \ character
        #
		$quot = strlen($quot) > 1 ? substr($quot, -1) : $quot;
        #
		# Delete the var parser
        #
		if ($quot!="'")
			$string = preg_replace('#(\\\*)\$#e', '\'$1\'.(strlen(\'$1\')%2!=0? \'$\': \'\\\$\')', $string);

		$strings[] = [$quot, str_replace('\\\\"', '"', $string)];
		return '';
	}

    /**
	 * Shows a template error.
     *
	 * @link http://php.net/manual/en/errorfunc.constants.php
	 * @param integer        $errno    Error id
	 * @param string         $err_msg  Error message
	 * @param string|boolean $err_file Error file. If is FALSE then you use the 'file' property
	 * @param string|boolean $err_line Error line. If is FALSE then you use the 'line' property
	 */
	private function _error($errno, $err_msg, $err_file = TRUE, $err_line = TRUE) {
		$is_error = ($errno == E_USER_ERROR) || ($errno == E_RECOVERABLE_ERROR);
		$err_file = $err_file !== FALSE ? $err_file : realpath($this->file);
		$err_line = $err_line !== FALSE ? $err_line : $this->line;
        #
		# If is a error then clean all buffers
        #
		if ($is_error) {
			while(ob_get_level() > 0) {
                ob_end_clean();
            }
        }
		$this->executeErrorFunction($errno, $err_msg, $err_file, $err_line);
        #
		# If is a error then exit of php
        #
		if ($is_error)
			exit;
	}

    /**
	 * Ececutes the error function when detect an error in template code.
     *
	 * @link http://php.net/manual/en/errorfunc.constants.php
	 * @param integer $errno    Error id
	 * @param string  $err_msg  Error message
	 * @param unknown $err_file Error file
	 * @param unknown $err_line Error line
	 */
	protected function executeErrorFunction($errno, $err_msg, $err_file, $err_line) {
		$is_error = ($errno == E_USER_ERROR) || ($errno == E_RECOVERABLE_ERROR);
        #
		# DEBUG MODE OFF
        #
		if (!$this->options['debug']) {
			$func = $this->options['error_func'];
			$code = '';

			if ($func && isCallable($func, $code)) {
				call_user_func($func, $errno, $err_msg, $err_file, $err_line);
			} elseif ($code)
				exit("The error function '$code' is undefined.");

			if ($is_error)
				exit('<b>Template error: </b>'.$err_msg.' in file <b>'.$err_file.'</b> on line <b>'.$err_line.'</b>');

			return;
		}
        #
		# DEBUG MODE ON
        #
		if (!$is_error) {
			$this->warnings[] = "<b>Template warning: </b>$err_msg in file <b>$err_file</b> on line <b>$err_line</b><br/>";
        } else {
			foreach($this->warnings as $warn) {
				echo $warn;
            }
			echo '<b>Template error: </b>'.$err_msg.' in file <b>'.$err_file.'</b> on line <b>'.$err_line.'</b>';
		}
	}

    /**
	 * Error handler.
     *
	 * @link http://php.net/manual/en/errorfunc.constants.php
	 * @param  integer $errno    Error id
	 * @param  string  $err_msg  Error message
	 * @param  string  $err_file Error file
	 * @param  integer $err_line Error line
	 * @return boolean TRUE if is a template error. FALSE if is other error
	 */
	final public function templateErrorHandler($errno, $err_msg, $err_file, $err_line) {
		if (stripos($err_file, "eval()'d") !== FALSE || $this->getEvalError($err_msg, $err_line)) {
			$err_file = realpath($this->file);
			$err_line = $this->line;
			$err_msg  = str_replace("';'", "':]'", $err_msg);
		} else
			return FALSE;

		$this->_error($errno, $err_msg, $err_file, $err_line);
		return TRUE;
	}

    /**
	 * Parses and gets the message and the line of a evaluation error.
     *
	 * @param  string  $err_msg  Eval error message. Out parameter is the error message in the eval error message
	 * @param  integer $err_line Line in the eval error message
	 * @return boolean TRUE if is a eval message error FALSE if not
	 */
	private function getEvalError(&$err_msg, &$err_line) {
        #
		# The msg isn"t eval error
        #
		if (stripos($err_msg, "eval()'d") === FALSE)
			return FALSE;

		$data    = [];
		$err_msg = str_replace("$", '&#36;', strip_tags($err_msg));

		$search  = '#(.+?) in .+? eval\(\)\'d code on line ([0-9]+).*#e';
		$replace = '(string)$data = array("$1", $2);';

		preg_replace($search, $replace, $err_msg);
		list($err_msg, $err_line) = $data;
		$err_msg = str_replace("\\'", "'", $err_msg);

		return TRUE;
	}

    /**
	 * Gets the php code of a cache handler.
     *
	 * @param  string $file Simphple file name. You use the name how id for the cache handler
	 * @param  string $code Out parameter. PHP code stored in the cache
	 * @return boolean TRUE if the file name is correct. FALSE if not
	 */
	protected function getFromCache($file, &$code) {
		if ($this->options['allow_cache'])
            $valid = FALSE;
            if (file_exists(CACHE_STORE.$file.'.cache.php')) {
                include $file;
                return $valid;
            }

			return $this->getDataFromCache(str_replace(['/', '.'], ['_', ''], $file), $code);

		return FALSE;
	}

    /**
	 * Gets a data from the cache.
     *
	 * @param  string $data_name The name of the data that you want to get
	 * @param  mixed  $data      Output parameter with the data
	 * @return boolean TRUE if the data is extracted. FALSE if the data not exists or there is a error
	 */
	public function getDataFromCache($data_name, &$data) {
		$is_valid = FALSE;
		$file     = CACHE_STORE.$data_name.'.cache.php';
		if (file_exists($file)) {
			include $file;
			return $is_valid;
		}
		return FALSE;
	}

    /**
	 * Puts the php code in a cache.
     *
	 * @param string  $file Template file name as id for the cache handler
	 * @param unknown $code Code to store in the cache
	 */
	protected function toCache($file, $code) {
		if ($this->options['allow_cache']) {
			if ($this->options['compact']) {
				$code = str_replace(["\n", "\r"], '', $code);
            }
			$this->storeInCache(str_replace(['/', '.'], ['_', ''], $file), $code, $this->options['expired']);
		}
	}

    /**
	 * Stores a template in the cache.
     *
	 * @param string          $file    Name of the cache file
	 * @param mixed           $data    Data to store in the cache
	 * @param boolean|integer $expired Time to live in seconds that data store in the cache. If is FALSE the data not expire
	 */
	public function storeInCache($file, $data, $expired = FALSE) {
		$data = var_export($data, TRUE);
		if ($expired !== FALSE) {
			$time    = time() + $expired;
			$valid   = 'time()<='.$time;
			$message = 'Will expire on '.gmdate(DATE_RFC822, $time).'.';
			$data    = '$valid?'.$data.':NULL';
		} else {
			$valid   = 'TRUE';
			$message = 'Not expire.';
		}

		$data_file = '<?php'.LF.
					 '/*'.LF.
					 ' * Data name: '.$file.LF.
					 ' * Expire: '.$message.LF.
					 ' */'.LF.
					 '$valid = '.$valid.';'.LF.
					 '$data = '.$data.';'.LF.
					 '?>';

        file_put_contents(CACHE_STORE.$file.'.cache.php', $file, LOCK_EX);
	}

    /**
     * Generates CSS3 properties with browser-specific prefixes.
     * The prefix list is not complete, it contains only the used properties in the CMS.
     *
     * @param  string $file css file to to work with
     * @return string       Parsed string
     */
    private function compressCSS($file) {
        $css = $this->setPrefixes($file);
        $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);
        $css = preg_replace('# {2,}#', '', $css);
        $css = str_replace([" { "," {","{ "], '{', $css);
        $css = str_replace([" }","} "," } "], '}', $css);
        $css = str_replace(": ", ':', $css);
        $css = str_replace("; ", ';', $css);
        $css = str_replace(", ", ',', $css);
        return $css;
    }

    /**
     * Generates CSS3 properties with browser-specific prefixes.
     * The prefix list is not complete, it contains only the used properties in the CMS.
     * So it can easily be extended.
     *
     * @param  string $file css file to to work with
     * @return string       Parsed string
     */
    private function setPrefixes($file) {
        $file = str_replace('./', '/', $file);
        $css  = file_get_contents($_SERVER['DOCUMENT_ROOT'].$file);
        $css  = preg_replace('#(\/\*).*?(\*\/)#s', '', $css);
        $values = [];
        foreach ($this->styles as $property => $styles) {
            preg_match_all('#[^-]'.$property.'#s', $css, $result);
            if (!empty($result[0])) {
                $tmp = array_unique($result[0]);
                    $values[] = $tmp;
            }

        }
        foreach ($values as $key => $value) {
            $value = trim($value[0]);
            preg_match_all('#'.$value.':[a-zA-Z0-9\.\-\#|\d\s]+?;|[a-zA-Z\-]+:\s_[a-z].+?;#s', $css, $keys);
            $control[] = $keys[0];
            foreach ($keys[0] as $property) {
                foreach ($this->styles as $style => $prefixes) {
                    if ($style === $value) {
                        $result = '';
                        foreach ($prefixes as $match) {
                            $result .= $match.$property;
                        }
                        $css = preg_replace('/[^-]'.$property.'/', $result, $css);
                    }
                }
            }
        }
        return $css;
    }
}
