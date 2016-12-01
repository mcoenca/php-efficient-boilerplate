<?php
// Init PHP session
session_start();
/*
  Error reporting.
*/
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);

// Taken from slim skeleton to make css assets work
if (PHP_SAPI == 'cli-server') {
  // To help the built-in PHP dev server, check if the request was actually for
  // something which should probably be served as a static file
  $url  = parse_url($_SERVER['REQUEST_URI']);
  $file = __DIR__ . $url['path'];
  if (is_file($file)) {
    return false;
  }
}

require_once dirname(dirname(__FILE__)) . '/app/config.php';
require_once ROOT_PATH . '/app/router.php'; 

//Route and Run
$app->run();
?>
