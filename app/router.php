<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once dirname(__FILE__) . '/config.php';
require_once ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/app/app.php';

// Forward all css calls to our scss server 
// It handles caching and stuff
// See style.php
$app->get('/css/{relativePathToCss}', '\App\Controllers\Css:get')
  ->setName('cssFile');

$app->get('/', '\App\Controllers\Home:get')
  ->setName('home');

$app->get('/hello/{name}', '\App\Controllers\Home:hello')
  ->setName('hello');

$app->get('/se-connecter', '\App\Controllers\Login:getLogin')
  ->setName('login');

$app->post('/se-connecter', '\App\Controllers\Login:postLoginRequest');


$app->get('/secret-place', function(Request $req, Response $res, $args) {
  return $res->write('Logged In !');
})->setName('secretPlace')
->add($auth->middleWare('home'));

$app->get('/super-secret-place', function(Request $req, Response $res, $args) {
  return $res->write('Admin logged In !');
})->setName('superSecretPlace')
->add($adminAuth->middleWare('secretPlace'));

?>
