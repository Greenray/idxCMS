<?php
/******************************************************************
 * 
 * Projectname:   PHP Error Handler Class 
 * Version:       1.0
 * Author:        Radovan Janjic <rade@it-radionica.com>
 * Last modified: 13 12 2013
 * Copyright (C): 2013 IT-radionica.com, All Rights Reserved
 * 
 * GNU General Public License (Version 2, June 1991)
 *
 * This program is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License
 * for more details.
 * 
 ******************************************************************/

class ErrorHandler {
	
	/** Error log message type
	 * @var integer
	 */
	public static $displayErrors = FALSE;
	
	/** Error log message type
	 * @var integer
	 */
	public static $logMessageType = 3;
	
	/** E-mail me on
	 * @var integer
	 */
	public static $mailOnErrorType = 0;
	
	/** E-mail
	 * @var string
	 */
	public static $mail = 'user@example.com';
	
	/** E-mail Subject
	 * @var string
	 */
	public static $mailSub = 'Critical Error';
	
	/** Log file path
	 * @var string
	 */
	public static $logFile = 'error.log';
	
	/** Time format
	 * @var string
	 */
	public static $timeFormat = 'Y-m-d H:i:s (T)';
	
	/** Log / Display Types
	 * @var integer
	 */
	private static $logTypes = E_ALL;
	
	/** 
	 * @var array
	 */
	private static $errorType = array (
			E_ERROR              => 'Error',
			E_WARNING            => 'Warning',
			E_PARSE              => 'Parsing Error',
			E_NOTICE             => 'Notice',
			E_CORE_ERROR         => 'Core Error',
			E_CORE_WARNING       => 'Core Warning',
			E_COMPILE_ERROR      => 'Compile Error',
			E_COMPILE_WARNING    => 'Compile Warning',
			E_USER_ERROR         => 'User Error',
			E_USER_WARNING       => 'User Warning',
			E_USER_NOTICE        => 'User Notice',
			E_STRICT             => 'Runtime Notice',
			E_RECOVERABLE_ERROR  => 'Catchable Fatal Error',
			E_DEPRECATED         => 'Deprecated'
		);
	
	/** User errors - var trace will be saved
	 * @var	array
	 */
	private static $userErrors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE, E_USER_DEPRECATED);
	
	/** Init handler
	 * @param	void
	 */
	static function Init($logTypes = E_ALL, $logFile = NULL) {
		error_reporting(0);
		ErrorHandler::$logTypes = $logTypes;
		if ($logFile !== NULL) {
			ErrorHandler::$logFile = $logFile;
		}
		set_error_handler('ErrorHandler::Log', ErrorHandler::$logTypes);
		register_shutdown_function('ErrorHandler::Shutdown');
	}
	
	/** Shutdown handler
	 * @param	void
	 */
	static function Shutdown() {
		$fileName = "unknown file";
		$errMsg   = "shutdown";
		$errno    = E_CORE_ERROR;
		$lineNum  = 0;
		$error = error_get_last();
		if ($error !== NULL) {
			$errNo    = $error["type"];
			$fileName = $error["file"];
			$lineNum  = $error["line"];
			$errMsg   = $error["message"];
		}
		ErrorHandler::Log($errNo, $errMsg, $fileName, $lineNum, array());
	}
	
	/** Log error to file
	 * @param	integer		$errNo		- Level of the error raised
	 * @param	string		$errMsg		- Error message
	 * @param	string		$fileName	- Filename in which the error was raised in
	 * @param	string		$lineNum	- Line number
	 * @param	string		$vars		- Line number
	 */
	static function Log($errNo, $errMsg, $fileName, $lineNum, $vars) {
		// Message
		$err = str_pad('Error Type:', 15, ' ', STR_PAD_RIGHT) . ErrorHandler::$errorType[$errNo] . PHP_EOL;
		$err .= str_pad('Date Time:', 15, ' ', STR_PAD_RIGHT) . date(ErrorHandler::$timeFormat) . PHP_EOL;
		$err .= str_pad('Error Num:', 15, ' ', STR_PAD_RIGHT) . $errNo . PHP_EOL;
		$err .= str_pad('Error Msg:', 15, ' ', STR_PAD_RIGHT) . $errMsg . PHP_EOL;
		$err .= str_pad('Script Name:', 15, ' ', STR_PAD_RIGHT) . $fileName . PHP_EOL;
		$err .= str_pad('Script Line:', 15, ' ', STR_PAD_RIGHT) . $lineNum . PHP_EOL;
		$err .= (in_array($errNo, ErrorHandler::$userErrors)) ? 'Var Trace' . PHP_EOL . str_repeat('-', 100) . PHP_EOL . print_r($vars, TRUE) . PHP_EOL : NULL;
		$err .= str_repeat('-', 100) . PHP_EOL . PHP_EOL;
		
		// Display error
		if (ErrorHandler::$displayErrors) {
			echo '<pre>', $err, '</pre>';
		}
		
		// Save to the error log
		error_log($err, ErrorHandler::$logMessageType, ErrorHandler::$logFile);
		
		// E-mail error
		if (ErrorHandler::$mailOnErrorType == $errNo) {
			mail(ErrorHandler::$mail, ErrorHandler::$mailSub, $err);
		}
	}
}