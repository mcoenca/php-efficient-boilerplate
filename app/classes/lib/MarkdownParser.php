<?php
namespace App\Lib;
require_once dirname(dirname(dirname(__FILE__))) . '/config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

interface MarkdownParserInterface {
  public function parse($markdownString);
}

class CebeMarkdownParser implements MarkdownParserInterface {
  protected $cebeParser;

  function __construct() {
    $this->cebeParser = new \cebe\markdown\Markdown();
    $this->cebeParser->html5 = true;
  }

  public function parse($markdownString) {
    return $this->cebeParser->parse($markdownString);
  }
}
?>
