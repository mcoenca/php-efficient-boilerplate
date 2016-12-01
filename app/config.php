<?php
/*
  Creating constants for heavily used paths makes things a lot easier.
  ex. require_once(LIBRARY_PATH . "Paginator.php")
*/
defined("LIBRARY_PATH")
  or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));

defined("ROOT_PATH")
  or define("ROOT_PATH", realpath(dirname(dirname(__FILE__))));

defined("TEMPLATES_PATH")
  or define("TEMPLATES_PATH", realpath(dirname(dirname(__FILE__)) . '/app/classes/views/pug/'));

defined("LOGS_PATH")
  or define("LOGS_PATH", realpath(dirname(dirname(__FILE__)) . '/logs'));

$config = [
	// App Settings
	'appName' => '',
  'env' => 'development',
	'urls' => [
		'baseUrl' => 'http://test.com'
	],
	// Slim settings
	'displayErrorDetails' => true,
	'addContentLengthHeader' => false,
	// Whoops middleware settings
	'debug' => true,
	'whoops.editor' => 'sublime',
	// Idiom ORM Db Settings
	'db' => [
		'connection_string' => 'mysql:host=localhost;dbname=mydname',     
		'username' => 'user',
		'password' => 'password',
		'return_result_sets' => true,
		'driver_options' => [
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET CHARACTER SET utf8'
		],
		// See the plug with logger in app.php
		'logging' => true
	],
	'pug' => [
		'prettyprint' => true,
		'extension' => '.pug',
		// Uncomment to set up caching
		// Watch out it does not work well with layouts and includes
		// 'cache' => dirname(__FILE__) . '/cache/pug',
		// 'upToDateCheck' => true
	],
  'gettext' => [
    'current-array-loc' => realpath(dirname(dirname(__FILE__)) . '/locales/current.php'),
    'new-array-loc' => realpath(dirname(dirname(__FILE__)) . '/locales/new.php'),
    'markdown-prefix' => 'mk-'
  ]
];
 
?>
