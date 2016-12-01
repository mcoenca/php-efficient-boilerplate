<?php
namespace App\Lib;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require_once realpath(dirname(dirname(dirname(__FILE__))) . '/config.php');
// Loading Pdo
require_once realpath(ROOT_PATH . '/vendor/autoload.php');

interface AuthenticationInterface {
  public function login($email, $passowrd);
  public function logout();
  public function isLoggedIn();
  // Old middleware style
  // public function __invoke(Request $request, Response $response, callable $next);
  public function middleWare($invalidRedirectRouteName);
}

//We're using something weird that's a middleware
class Authentication implements AuthenticationInterface {
  private function _checkPasswords($inputPassword, $storedPasswordHash, $passwordHashAlgo) {
    // Assumes you have a mix of passwords hashed from md5 passwords
    // and passwords
    // See http://security.stackexchange.com/questions/103393/is-using-bcrypt-on-existing-sha1-hashes-good-enough-when-switching-password-impl/103403#103403
    if ($passwordHashAlgo === 'md5') {
      $matches = password_verify(md5($inputPassword), $storedPasswordHash);
    }
    else {
      $matches = password_verify($inputPassword, $storedPasswordHash);
    }
    return $matches;
  }

  private function _isValid($user) {
    return $user && $user->id;
  }

  public function login($email, $inputPassword) {
    $user = \ORM::forTable('users')
      ->where('mail', $email)
      ->findOne();

    if ($this->_isValid($user)) {
      $passwordsMatches = $this->_checkPasswords($inputPassword, $user->new_password, $user->password_hash_algo);

      if ($passwordsMatches) {
        if ($user->password_hash_algo === 'md5') {
          $user->new_password = password_hash($inputPassword, PASSWORD_DEFAULT);
          $user->password_hash_algo = 'bcrypt';
          $user->save();
        }

        $_SESSION['userId'] = $user->id;
        return true;
      }
    }

    return false;
  }

  public function logout() {
    unset($_SESSION['userId']);
  }

  public function isLoggedIn() {
    return !empty($_SESSION['userId']);
  }

  // SlimMiddleWare part
  // XXX could be refactored to a programmable anonymous function
  // with a specific redirect
  // public function __invoke(Request $request, Response $response, callable $next) {
  //   // $method = $request->getMethod();
  //   if ($this->isLoggedIn()) {
  //     return $next($request, $response);
  //   }

  //   $uri = $request->getUri()->withPath($this->container->router->pathFor('home'));
  //   return $response->withRedirect($uri, 403);
  // }

  public function middleWare($redirectRouteName) {
    $auth = $this;
    return function (Request $request, Response $response, callable $next) use($redirectRouteName, $auth) {
      // $method = $request->getMethod();
      if ($auth->isLoggedIn()) {
        return $next($request, $response);
      }

      $uri = $request->getUri()->withPath($this->router->pathFor($redirectRouteName));
      return $response->withRedirect($uri, 403);
    };
  }
}

// Roll your own
class AdminAuthentication extends Authentication implements AuthenticationInterface {

}
?>
