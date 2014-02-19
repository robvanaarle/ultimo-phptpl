<?php

namespace ultimo\phptpl\helpers\decorators;

/**
 * This helper decorator assumes the file on the server beloning to href="css/foo.css" is relative
 * the public folder, of which it assumes is what the current directory ('.' in include_path) is.
 */
abstract class HeadTagFileCache extends \ultimo\phptpl\HelperDecorator {
  /**
   * The file modify time cache, or null if no versioning needs to be used.
   * @var \ultimo\io\FileModifyTimeCache
   */
  protected $fileMTimeCache = null;
  
  public function __construct(\ultimo\phptpl\Helper $helper, array $config = array()) {
    parent::__construct($helper, $config);
    
    if (isset($config['fileMTimeCache'])) {
      $this->fileMTimeCache = $config['fileMTimeCache'];
    }
  }
  
  /**
   * Helper initial function.
   * @return HeadLink This class.
   */
  public function __invoke() {
    return $this;
  }
  
  /**
   * Adds the version of a file to a url, if versioning is enabled and the url
   * is not absolute.
   * @param string $url The url to the file.
   * @return string The url to the file with the version added to it.
   */
  protected function addVersion($url) {
    if (strpos($url, '://') !== false) {
      return $url;
    }
    
    $version = $this->getFileMTime(str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $url));
    
    if ($version === null) {
      return $url;
    }
    
    return $url . '?v=' . $version;
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

}