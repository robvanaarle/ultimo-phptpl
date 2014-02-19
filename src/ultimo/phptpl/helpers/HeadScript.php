<?php

namespace ultimo\phptpl\helpers;

class HeadScript extends \ultimo\phptpl\helpers\support\HeadTag {
  const TYPE_JAVASCRIPT = 'text/javascript';
  
  /**
   * Helper initial function.
   * @return HeadScript This class.
   */
  public function __invoke() {
    return $this;
  }
  
  /**
   * Returns the name of the tags the helper generates.
   * @return string The name of the tags the helper generates.
   */
  public function getTagName() {
    return 'script';
  }
  
  /**
   * Starts the capturing of javascript.
   * @param string $mode How the captured javascript should be added.
   */
  public function captureJavascriptStart($mode=self::MODE_APPEND) {
    $this->captureStart(array('type' => self::TYPE_JAVASCRIPT), $mode);
  }
  
  /**
   * Ends the capturing of javascript.
   */
  public function captureJavascriptEnd() {
    $this->captureEnd();
  }
  
  /**
   * Creates and returns the file attributes as array.
   * @param string $src The url to the file.
   * @param string $type The type of the file.
   * @return The file attributes as array.
   */
  protected function createFileAttrs($src, $type) {
    return array('src' => $src, 'type' => $type);
  }
    
  /**
   * Appends a javascript file.
   * @param string $src The url to the javascript file.
   * @param string $dup What to do with duplicates.
   * @return HeadScript This instance for fluid design.
   */
  public function appendJavascriptFile($src, $dup=self::DUP_DISALLOWED) {
    $this->addTag($this->createFileAttrs($src, self::TYPE_JAVASCRIPT), '', self::MODE_APPEND, $dup);
    return $this;
  }
  
  /**
   * Appends a javascript file.
   * @param string $src The url to the javascript file.
   * @param string $dup What to do with duplicates.
   * @return HeadScript This instance for fluid design.
   */
  public function prependJavascriptFile($src, $dup=self::DUP_DISALLOWED) {
    $this->addTag($this->createFileAttrs($src, self::TYPE_JAVASCRIPT), '', self::MODE_PREPEND, $dup);
    return $this;
  }
  
  /**
   * Appends javascript.
   * @param string $script The javascript to append.
   * @return HeadScript This instance for fluid design.
   */
  public function appendJavascript($script) {
    $this->addTag(array('type' => self::TYPE_JAVASCRIPT), $script, self::MODE_APPEND);
    return $this;
  }
  
  /**
   * Prepends javascript.
   * @param string $script The javascript to prepend.
   * @return HeadScript This instance for fluid design.
   */
  public function prependJavascript($script) {
    $this->addTag(array('type' => self::TYPE_JAVASCRIPT), $script, self::MODE_PREPEND);
    return $this;
  }
  
  /**
   * Converts tag attributes and value to a string.
   * @param array $attrs The tag attributes.
   * @param string $value The value of the tag.
   * @return string The tag as string.
   */
  public function tagToString($attrs, $value) {
    if ($value !== null && !isset($attrs['src'])) {
      $value = "\n" . '//<![CDATA[' . "\n" . $value . "\n" . '//]]>' . "\n";
    }
    return parent::tagToString($attrs, $value);
  }
}