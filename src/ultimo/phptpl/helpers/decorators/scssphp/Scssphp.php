<?php

namespace ultimo\phptpl\helpers\decorators\scssphp;

/**
 * This helper decorator assumes the file on the server beloning to href="css/foo.scss" is relative
 * the public folder, of which it assumes is what the current directory ('.' in include_path) is.
 */
class Scssphp extends \ultimo\phptpl\HelperDecorator {
  protected $scssphpPath = 'scss.inc.php';
  protected $compiledPath;
  protected $reversedCompiledPath;
  protected $scssc = null;
  
  /**
   * The file modify time cache, or null if no versioning needs to be used.
   * @var \ultimo\io\FileModifyTimeCache
   */
  protected $fileMTimeCache = null;
  protected $extensions = array('scss');
  protected $scsscCallback = null;
  protected $formatter = null;
  
  public function __construct(\ultimo\phptpl\Helper $helper, array $config = array()) {
    parent::__construct($helper, $config);
    
    if (!isset($config['compiledPath'])) {
      throw new \Exception("Missing compiledPath config");
    }
    $this->compiledPath = $config['compiledPath'];
    $compiledPathDirNames = explode('/', $this->compiledPath);
    $this->reversedCompiledPath = str_repeat('../', count($compiledPathDirNames));
    
    if (isset($config['scssphpPath'])) {
      $this->scssphpPath = $config['scssphpPath'];
    }
    
    if (isset($config['fileMTimeCache'])) {
      $this->fileMTimeCache = $config['fileMTimeCache'];
    }
    
    if (isset($config['extensions'])) {
      $this->extensions = $config['extensions'];
    }
    
    if (isset($config['formatter'])) {
      $this->formatter = $config['formatter'];
    }
    
    if (isset($config['scsscCallback'])) {
      $this->scsscCallback = $config['scsscCallback'];
    }
  }
  
  protected function getScssc() {
    if ($this->scssc === null) {
      require_once $this->scssphpPath;
      $this->scssc = new Scssc();
      
      if ($this->formatter !== null) {
        $this->scssc->setFormatter($this->formatter);
      }
      
      if ($this->scsscCallback !== null) {
        call_user_func($this->scsscCallback, $this->scssc);
      }
    }
    return $this->scssc;
  }
  
  protected function compile($href) {
    // don't process absolute hrefs
    if (strpos($href, '://') !== false) {
      return $href;
    }
    
    $pathinfo = pathinfo($href);
    
    // only process accepted extensions
    if (!in_array($pathinfo['extension'], $this->extensions)) {
      return $href;
    }
    
    // build href of compiled file
    $href = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $href);
    $hrefCompiled = $this->compiledPath . '/' . $pathinfo['filename'] . '.' . substr(md5($href), 0, 8) . '.css';
    $hrefCompiled = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $hrefCompiled);
    
    // check modification dates
    $mtimeHref = $this->getFileMTime($href);
    $mTimeHrefCompiled = $this->getFileMTime($hrefCompiled);
    
    // only compile if compiled file does not exist or is older than source
    if ($mtimeHref === null || ($mTimeHrefCompiled !== null && $mtimeHref < $mTimeHrefCompiled)) {
      return str_replace(array('\\', '/'), '/', $hrefCompiled);
    }
    
    // compile file to destination
    $scssc = $this->getScssc();
    
    // set import paths relative to original
    $scssc->setImportPaths(array($pathinfo['dirname']));
    
    // make sure paths to assets relative to scss are fixed
    $this->registerUrlFunction($scssc, $this->reversedCompiledPath . $pathinfo['dirname']);
    
    $compiled = $scssc->compile(file_get_contents($href), $href);
    file_put_contents($hrefCompiled, $compiled);
    
    // refresh fileMTimeCache for compiled file to prevent keep recompiling until it expires
    if ($this->fileMTimeCache !== null) {
      $this->fileMTimeCache->refresh($hrefCompiled);
    }
    
    return str_replace(array('\\', '/'), '/', $hrefCompiled);
  }
  
  protected function getFileMTime($path) {
    if ($this->fileMTimeCache !== null) {
      return $this->fileMTimeCache->getMTime($path);
    } else {
      $filemtime = @filemtime($path);
      if (!$filemtime) {
        return null;
      }
      return $filemtime;
    }
  }
  
  protected function registerUrlFunction($scssc, $basePath) {
    // make sure url directory separators are /
    $basePath = rtrim(str_replace(array('\\', '/'), '/', $basePath), '/') . '/';
    
    $scssc->registerFunction('url', function($args, $scssc) use ($basePath) {
      
      // if there is nu argument, return 'nothing'.
      if (!isset($args[0])) {
        return 'url()';
      }

      // get first argument
      $arg = $scssc->getPhpValue($args[0]);
      
      // append path, if path is not absolute
      $result = $arg;
      if (strlen($arg) > 0 && $arg[0] != '/' && strpos($arg, '://') === false) {
        $result = $basePath . $arg;
      }

      // append quotes or double quotes
      if ($args[0][0] == "string") {
        $result = $args[0][1] . $result . $args[0][1];
      }

      return 'url(' . $result .')';
    });
  }
  
  public function appendStylesheet($href, $media='', $dup=\ultimo\phptpl\helpers\support\HeadTag::DUP_DISALLOWED) {
    parent::appendStylesheet($this->compile($href), $media, $dup);
    return $this;
  }
  
  public function prependStylesheet($href, $media='', $dup=\ultimo\phptpl\helpers\support\HeadTag::DUP_DISALLOWED) {
    parent::appendStylesheet($this->compile($href), $media, $dup);
    return $this;
  }
}