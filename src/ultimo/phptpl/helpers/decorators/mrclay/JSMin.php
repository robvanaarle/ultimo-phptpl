<?php

namespace ultimo\phptpl\helpers\decorators\mrclay;

/**
 * This helper decorator assumes the file on the server beloning to href="css/foo.scss" is relative
 * the public folder, of which it assumes is what the current directory ('.' in include_path) is.
 */
class JSMin extends \ultimo\phptpl\HelperDecorator {
  protected $compiledPath;
  
  /**
   * The file modify time cache, or null if no versioning needs to be used.
   * @var \ultimo\io\FileModifyTimeCache
   */
  protected $fileMTimeCache = null;
  
  
  protected $minifierType = "jsmin"; // jsminplus, none
  
  
  public function __construct(\ultimo\phptpl\Helper $helper, array $config = array()) {
    parent::__construct($helper, $config);
    
    if (!isset($config['compiledPath'])) {
      throw new \Exception("Missing compiledPath config");
    }
    $this->compiledPath = $config['compiledPath'];
    
    if (isset($config['fileMTimeCache'])) {
      $this->fileMTimeCache = $config['fileMTimeCache'];
    }
    
    if (isset($config['minifierType'])) {
      $this->minifierType = strtolower($config['minifierType']);
    }
  }
  
  protected function minify($src) {
    // don't process absolute hrefs
    if (strpos($src, '://') !== false) {
      return $src;
    }
    
    $pathinfo = pathinfo($src);
    
    
    // build src of minified file
    $src = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $src);
    $srcMinified = $this->compiledPath . '/' . $pathinfo['filename'] . '.' . substr(md5($src), 0, 8) . '.js';
    $srcMinified = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $srcMinified);
    
    // check modification dates
    $mtimeSrc = $this->getFileMTime($src);
    $mTimeSrcMinified = $this->getFileMTime($srcMinified);
    
    // only minify if compiled file does not exist or is older than source
    if ($mtimeSrc === null || ($mTimeSrcMinified !== null && $mtimeSrc < $mTimeSrcMinified)) {
      return str_replace(array('\\', '/'), '/', $srcMinified);
    }
    
    $source = file_get_contents($src);
    
    switch ($this->minifierType) {
      case "jsmin":
        $minified = \JSMin::minify($source);
        break;
      
      case "jsminplus":
        $minified = \JSMinPlus::minify($source);
        break;
      
      default:
        $minified = $source;
    }
    
    file_put_contents($srcMinified, $minified);
    
    // refresh fileMTimeCache for minified file to prevent keep reminifing until it expires
    if ($this->fileMTimeCache !== null) {
      $this->fileMTimeCache->refresh($srcMinified);
    }
    
    return str_replace(array('\\', '/'), '/', $srcMinified);
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
  
  public function appendJavascriptFile($src, $dup=\ultimo\phptpl\helpers\support\HeadTag::DUP_DISALLOWED) {
    parent::appendJavascriptFile($this->minify($src), $dup);
    return $this;
  }
  
  public function prependJavascriptFile($src, $dup=\ultimo\phptpl\helpers\support\HeadTag::DUP_DISALLOWED) {
    parent::prependJavascriptFile($this->minify($src), $dup);
    return $this;
  }
}