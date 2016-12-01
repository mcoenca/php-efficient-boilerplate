<?php
  require_once dirname(dirname(__FILE__)) . '/app/config.php';
  require_once ROOT_PATH . '/vendor/autoload.php';

  $scss = new Leafo\ScssPhp\Compiler();
  $scss->setImportPaths(ROOT_PATH . '/app/classes/views/scss/');
  if ($config['env'] === 'production') {
    $scss->setFormatter(Leafo\ScssPhp\Formatter\Crunched);
  } else {
    $scss->setLineNumberStyle(Leafo\ScssPhp\Compiler::LINE_COMMENTS);
  }

  $server = new Leafo\ScssPhp\Server(ROOT_PATH . '/app/classes/views/scss/', ROOT_PATH . '/app/cache/scss', $scss);
  $server->serve();
?>
