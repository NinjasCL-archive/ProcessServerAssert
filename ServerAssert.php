<?php namespace ServerAssert;

/* 
ServerAssert.php.
This script contains code available in the install.php
of ProcessWire.

MIT License

Copyright (c) 2016 Camilo Castro - Ninjas.cl

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
class ServerAssert {
	/**
	 * Minimum required PHP version to install ProcessWire
	 *
	 */
	const MIN_REQUIRED_PHP_VERSION = '5.3.8';

	protected $messages = [];
	protected $errors = [];
	protected $warnings = [];

	/**
	 * Check if the given function $name exists and report OK or fail with $label
	 *
	 */
	protected function checkFunction($name, $label) {
		
		if(function_exists($name)) {
		
			$this->messages[] = "$label"; 
		
		} else {
		
			$this->errors[] = "Fail: $label"; 	
		} 
	}

	protected function modules() {

		if(version_compare(PHP_VERSION, self::MIN_REQUIRED_PHP_VERSION) >= 0) {
		
			$this->messages[] = "PHP version " . PHP_VERSION;
		
		} else {

			$this->errors[] = "ProcessWire requires PHP version " . self::MIN_REQUIRED_PHP_VERSION . " or newer. You are running PHP " . PHP_VERSION;

		}

		if(extension_loaded('pdo_mysql')) {
			
			$this->messages[] = "PDO (mysql) database"; 

		} else {

			$this->errors[] =  "PDO (pdo_mysql) is required (for MySQL database)"; 
		}

		$this->checkFunction("filter_var", "Filter functions (filter_var)");
		
		$this->checkFunction("mysqli_connect", "MySQLi (not required by core, but may be required by some 3rd party modules)");
		
		$this->checkFunction("imagecreatetruecolor", "GD 2.0 or newer"); 
		
		$this->checkFunction("json_encode", "JSON support");
		
		$this->checkFunction("preg_match", "PCRE support"); 
		
		$this->checkFunction("ctype_digit", "CTYPE support");
		
		$this->checkFunction("iconv", "ICONV support"); 
		
		$this->checkFunction("session_save_path", "SESSION support"); 
		
		$this->checkFunction("hash", "HASH support"); 
		
		$this->checkFunction("spl_autoload_register", "SPL support"); 

		if(function_exists('apache_get_modules')) {

			if(in_array('mod_rewrite', apache_get_modules())) {

				$this->messages[] = "Found Apache module: mod_rewrite"; 

			} else { 

				$this->warnings[] = "Apache mod_rewrite does not appear to be installed and is required by ProcessWire. Maybe you are using Nginx or another web server. If you know what are you doing you can ignore this message."; 

			}

		} else {
			// apache_get_modules doesn't work on a cgi installation.
			// check for environment var set in htaccess file, as submitted by jmarjie. 
			$mod_rewrite = getenv('HTTP_MOD_REWRITE') == 'On' || getenv('REDIRECT_HTTP_MOD_REWRITE') == 'On' ? true : false;
			
			if($mod_rewrite) {
				
				$this->messages[] = "Found Apache module (cgi): mod_rewrite";

			} else {
				
				$this->warnings[] = "Unable to determine if Apache mod_rewrite (required by ProcessWire) is installed. On some servers, we may not be able to detect it until your .htaccess file is place. Maybe you are using Nginx or another web server. If you know what are you doing you can ignore this message."; 

			}
		}

		if(class_exists('\ZipArchive')) {
			
			$this->messages[] = "ZipArchive support"; 

		} else {
			
			$this->warnings[] = "ZipArchive support was not found. This is recommended, but not required to complete installation."; 
		}
	}

	protected function htaccess($path) {

		if(!is_file("{$path}.htaccess") || !is_readable("{$path}.htaccess")) {
			
			$this->warnings[] = "/.htaccess doesn't exist. Before continuing, you should rename the included htaccess.txt file to be .htaccess (with the period in front of it, and no '.txt' at the end). Maybe you are using Nginx or another web server. If you know what are you doing you can ignore this message.";

		} else if(!strpos(file_get_contents("{$path}.htaccess"), "PROCESSWIRE")) {

			$this->warnings[] = "/.htaccess file exists, but is not for ProcessWire. Please overwrite or combine it with the provided /htaccess.txt file (i.e. rename /htaccess.txt to /.htaccess, with the period in front). Maybe you are using Nginx or another web server. If you know what are you doing you can ignore this message."; 

		} else {

			$this->messages[] = ".htaccess looks good"; 
		}

	}

	protected function config($path) {

		if(is_writable("{$path}site/config.php")) { 
			
			$this->warnings[] = "/site/config.php is writable. Please adjust the server permissions."; 

		} else {
			$this->messages[] = "/site/config.php is not writable."; 

		}
	}


	public static function assert($path = './') {

		$assert = new ServerAssert();

		$assert->modules();
		$assert->htaccess($path);
		$assert->config($path);

		// TODO: Make a proper database check in the future
		$assert->messages[] = 'Remember if you are using InnoDB, MySQL version should be >= 5.6.4';

		$stats = "\nAssertion complete with (" . 
		count($assert->messages) . ") Messages " . 
		"(" . count($assert->errors) . ") Errors And (" . 
		count($assert->warnings) . ") Warnings\n";

		return ['messages' => $assert->messages, 
				'errors' => $assert->errors,
				'warnings' => $assert->warnings,
				'stats' => $stats];
	}
}