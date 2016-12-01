<?php
namespace App\Lib;
require_once dirname(dirname(dirname(__FILE__))) . '/config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

interface TranslationInterface {  
  public function addFromPhpCodeString($phpCodeString);
  public function translate($token, $exportToArray);
}

class GettextTranslation implements TranslationInterface {
  protected $pathToCurrentTranslationFile;
  protected $pathToNewTranslationFile;
  protected $translations;
  protected $translator;
  protected $markdownPrefix;
  protected $markdownParser;

  function __construct($config, $markdownParser) {
    $this->pathToCurrentTranslationFile = $config['current-array-loc'];
    $this->pathToNewTranslationFile = $config['new-array-loc'];
    $this->markdownPrefix = $config['markdown-prefix'];

    $translations = \Gettext\Translations::fromPhpArrayfile($config['current-array-loc']);
    $this->translations = $translations;

    $translator = new \Gettext\Translator();
    $translator->loadTranslations($translations);
    // This will load global variable __ in any page using
    // $translator->register();

    $this->translator = $translator;
    $this->markdownParser = $markdownParser;
  }

  private function tokenExists($translationToken) {
    return !empty($this->translations->find(null, $translationToken));
  }

  private function addToken($translationToken) {
    $this->translations->insert(null, $translationToken);
  }

  private function exportTranslationsToPhpArrayFile() {
    $this->translations->toPhpArrayFile($this->pathToNewTranslationFile);
  }

  public function addFromPhpCodeString($phpCodeString) {
    $this->translations->addFromPhpCodeString($phpCodeString);
  }

  public function translate($token, $exportToArray) {
    if ($exportToArray) {
      if (!$this->tokenExists($token)) {
        $this->addToken($token);
        $this->exportTranslationsToPhpArrayFile();
      }
    }

    if ($this->markdownPrefix && stripos($token, $this->markdownPrefix) === 0) {
      return $this->markdownParser->parse($this->translator->gettext($token));
    }

    return $this->translator->gettext($token);
  }
}
?>
