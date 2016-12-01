<?php
namespace App\Controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require_once dirname(dirname(dirname(__FILE__))) . '/config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

class Login extends Base {   
  public function getLogin(Request $req, Response $res, $args) {
    // $forgottenPasswordUrl = $this->router->pathFor('forgottenPassword');
    $forgottenPasswordUrl = '/mot-de-passe-oublie';
    $res->write(
      $this->ci->template->render('login', [
        'appName' => $this->ci->config['appName'],
        'forgottenPasswordUrl' => $forgottenPasswordUrl,
        'flash' => $this->ci->flash->getMessages()
      ])
    );

    return $res;
  }

  public function postLoginRequest(Request $req, Response $res, $args) {
    $d = $req->getParsedBody();

    $email = filter_var($d['email'], FILTER_SANITIZE_EMAIL);
    $password = $d['password'];

    if ($this->ci->auth->login($email, $password)) {
      $uri = $req->getUri()->withPath($this->ci->router->pathFor('secretPlace'));
      return $res->withStatus(200)->withHeader('Location', $uri);
    }

    $this->ci->flash->addMessage('error', 'Email ou mot de passe invalide');
    $uri = $req->getUri()->withPath($this->ci->router->pathFor('login'));

    return $res->withStatus(403)->withHeader('Location', $uri);
  }
}
?>
