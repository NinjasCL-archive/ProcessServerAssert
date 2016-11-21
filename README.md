# ProcessServerAssert
Simple Module for Asserting if the server pass the minimum requirements for Processwire.

```php
require_once './ServerAssert.php';
use ServerAssert\ServerAssert as ServerAssert;

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
```

**Result**

```
MESSAGE: PHP version 5.5.31
MESSAGE: PDO (mysql) database
MESSAGE: Filter functions (filter_var)
MESSAGE: MySQLi (not required by core, but may be required by some 3rd party modules)
MESSAGE: GD 2.0 or newer
MESSAGE: JSON support
MESSAGE: PCRE support
MESSAGE: CTYPE support
MESSAGE: ICONV support
MESSAGE: SESSION support
MESSAGE: HASH support
MESSAGE: SPL support
MESSAGE: ZipArchive support
MESSAGE: /site/config.php is not writable.
WARNING: Unable to determine if Apache mod_rewrite (required by ProcessWire) is installed. On some servers, we may not be able to detect it until your .htaccess file is place. Maybe you are using Nginx or another web server. If you know what are you doing you can ignore this message.
WARNING: /.htaccess doesn't exist. Before continuing, you should rename the included htaccess.txt file to be .htaccess (with the period in front of it, and no '.txt' at the end). Maybe you are using Nginx or another web server. If you know what are you doing you can ignore this message.
```
