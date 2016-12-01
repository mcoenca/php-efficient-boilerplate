<?php
namespace App\Lib;
require_once realpath(dirname(dirname(dirname(__FILE__))) . '/config.php');
// Loading Pug
require_once realpath(ROOT_PATH . '/vendor/autoload.php');

// Wrapper around our rendering + our translation engine
class Template {
  protected $templater = null;

  function __construct(TemplaterInterface $templater) {
    $this->templater = $templater;
    // $this->translation = $translation;
    // Legacy code
    // foreach ($this->templater->getAllTemplatesPhpStrings() as $phpString) {
    //   $this->translation->addFromPhpCodeString($phpString);
    // }
    // $this->translation->exportTranslationsToPhpArrayFile();
  }


  public function render($relativePathToTemplate, $paramsArray) {
    return $this->templater->render($relativePathToTemplate, $paramsArray);
  }
}

interface TemplaterInterface {
  public function render($relativePathToTemplate, $paramsArray);
  public function getAllTemplatesPhpStrings();
  public function addFunctionHelper($shortcut, $functionSymbol);
}

class PugTemplater implements TemplaterInterface {
  protected $pug;
  public $pathToTemplates;

  function __construct($pugConfig, $pathToTemplates) {
    $pug = new \Pug\Pug($pugConfig);

    $missingRequirements = array_keys(array_filter($pug->requirements(), function ($valid) {
        return $valid === false;
    }));

    $missings = count($missingRequirements);
    if ($missings) {
      echo $missings . ' requirements are missing.<br />';
      foreach ($missingRequirements as $requirement) {
        switch($requirement) {
          case 'streamWhiteListed':
            print 'Suhosin is enabled and ' . $pug->getOption('stream') . ' is not in suhosin.executor.include.whitelist, please add it to your php.ini file.<br />';
            break;
          case 'cacheFolderExists':
            print 'The cache folder does not exists, please enter in a command line : <code>mkdir -p ' . $pug->getOption('cache') . '</code>.<br />';
            break;
          case 'cacheFolderIsWritable':
            print 'The cache folder is not writable, please enter in a command line : <code>chmod -R +w ' . $pug->getOption('cache') . '</code>.<br />';
            break;
          default:
            print $requirement . ' is false.<br />';
        }
      }
      exit();
    }

    $this->pug = $pug;
    $this->pathToTemplates = $pathToTemplates;
  }

  public function addFunctionHelper($shortcut, $functionSymbol) {
    $this->pug->addKeyword($shortcut, function($args) use($functionSymbol) {
      return [
        'beginPhp' => $functionSymbol . '('. $args .');'
      ];
    });
  }

  public function getAllTemplatesPhpStrings() {
    $viewsPaths = array_diff(scandir($this->pathToTemplates), [
      '.',
      '..',
      'layouts'
    ]);

    return \Functional\map($viewsPaths, function($viewPath) { 
      return $this->pug->compile(realpath($this->pathToTemplates . '/' . $viewPath));
    });
  }

  public function render($relativePathToTemplate, $paramsArray) {
    return $this->pug->render(realpath($this->pathToTemplates . '/' . $relativePathToTemplate . '.pug'), array_merge($paramsArray));
  }
}
?>
