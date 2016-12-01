<?php
namespace App\Controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require_once dirname(dirname(dirname(__FILE__))) . '/config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

// Forwards all calls to CSS server that handles SCSS compilation and caching. See style.php.
class Css extends Base {   
  public function get(Request $request, Response $response, $args) {
    // We prevent file load attacks by allowing only the load of top level scss files
    // see http://www.phptherightway.com/#data-filtering
    $cleanedPath = str_replace([chr(0), '/', '../'], ['', '', ''], $args['relativePathToCss']);
    return $response->withStatus(302)->withHeader('Location', "/style.php?p={}.scss");
  }
}
?>
