<?php
require_once realpath(dirname(__FILE__) . '/config.php');
// Loading packages and our dependencies with composer autoload
require_once realpath(ROOT_PATH . '/vendor/autoload.php');

// Configure DB with Idiom ORM
ORM::configure($config['db']);

$dbLogger = new \Monolog\Logger('dbLogger');
$fileHandler = new \Monolog\Handler\StreamHandler(realpath(ROOT_PATH . '/logs/db.log'));
$dbLogger->pushHandler($fileHandler);
ORM::configure('logger', function($logString, $queryTime) use($dbLogger) {
  $dbLogger->addInfo($logString);
});

// Adding all config settings as slim global variables
$app = new \Slim\App(['settings' => $config]);

// Adding middlewares
// Pretty Print errors happening inside Slim
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

// Dependency Injection and config for packages
$container = $app->getContainer();

// Maybe its the same than just above ?
$container['config'] = function ($c) use($config) {
  return $config;
};

// Adding flash capability
// for error messages, better than get params
// see https://www.slimframework.com/docs/features/flash.html
// usage: in a request
// ->flash->addMessage('key', 'value');
// ->flash->getMessages(); from previous request
$container['flash'] = function () {
  return new \Slim\Flash\Messages();
};

// Logging class
$container['appLogger'] = function($c) {
  $appLogger = new \Monolog\Logger('appLogger');
  $fileHandler = new \Monolog\Handler\StreamHandler(realpath(LOGS_PATH . '/app.log'));
  $appLogger->pushHandler($fileHandler);

  return $appLogger;
};

// Translation function needs to be global for template inclusion
$markdownParser = new \App\Lib\CebeMarkdownParser();
$translation = new \App\Lib\GettextTranslation($config['gettext'], $markdownParser);

function trs($token) {
  global $config;
  global $translation;

  $exportToArray = $config['env'] === 'development';

  return $translation->translate($token, $exportToArray);
}

// We wrap the template engine inside our own class
$container['template'] = function ($c) {
  $templater = new App\Lib\PugTemplater($c->config['pug'], TEMPLATES_PATH);
  $template = new App\Lib\Template($templater);

  return $template;
};

// Authentication
$auth = new \App\Lib\Authentication();
$adminAuth = new \App\Lib\AdminAuthentication($config['adminId']);
$container['auth'] = function($c) use($auth) {
  return $auth;
};

$container['adminAuth'] = function($c) use($adminAuth) {
  return $adminAuth;
};
