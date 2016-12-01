<?php
namespace App\Controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require_once dirname(dirname(dirname(__FILE__))) . '/config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

class Home extends Base {   
  public function get(Request $request, Response $response, $args) {
    $this->ci->appLogger->addInfo('Homie');

    $user = \ORM::forTable('users')
      ->findOne();

    $response->write(
      $this->ci->template->render('home', [
        'appName' => $this->ci->config['appName'],
        'user' => $user,
        'swag' => function($yolo) {return 'yolo'.$yolo; },
        'array' => ['yo', 'lo', 'mk-stuff'],
        'get' => $request->getQueryParams()
      ])
    );

    return $response;
  }

  public function hello(Request $request, Response $response, $args) {
    $this->ci->appLogger->addInfo('Hello for ' . $args['name']);

    $user = \ORM::forTable('users')
      ->where('prenom', $args['name'])
      ->findOne();

    $response->write(
      $this->ci->template->render('home', [
        'appName' => $this->ci->config['appName'],
        'user' => $user,
        'swag' => function($yolo) {return 'yolo'.$yolo; },
        'array' => ['yo', 'lo', 'mk-stuff']
      ])
    );

    return $response;
  }
}
?>
