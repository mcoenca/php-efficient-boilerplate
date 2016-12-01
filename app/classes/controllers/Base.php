<?php
namespace App\Controllers;
require_once dirname(dirname(dirname(__FILE__))) . '/config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

class Base {
   protected $ci;
   //Constructor
   public function __construct(\Slim\Container $ci) {
       $this->ci = $ci;
   }
}
?>
