<?php namespace ServerAssert;
/* 
Assert.php

Sample Script for Checking if the Server Could Run
ProcessWire.

use in the command line as

php Assert.php

or go to the webserver.

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

require_once './ServerAssert.php';
use \ServerAssert\ServerAssert as ServerAssert;

// Assert with the path to root
$data = ServerAssert::assert('./');

$htmlbreak = '';
if (php_sapi_name() != 'cli') $htmlbreak = '<br>';

foreach ($data['messages'] as $text) {
	echo "MESSAGE: $text\n" . $htmlbreak;
}

foreach ($data['errors'] as $text) {
	echo "ERROR: $text\n" . $htmlbreak;
}

foreach ($data['warnings'] as $text) {
	echo "WARNING: $text\n" . $htmlbreak;
}

echo $data['stats'];