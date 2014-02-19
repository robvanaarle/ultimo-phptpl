<?php

namespace ultimo\phptpl\helpers;

class HeadStyle extends \ultimo\phptpl\helpers\support\HeadTag {
  const TYPE_CSS = 'text/css';
  
  /**
   * Helper initial function.
   * @return HeadStyle This class.
   */
  public function __invoke() {
    return $this;
  }
  
  /**
   * Returns the name of the tags the helper generates.
   * @return string The name of the tags the helper generates.
   */
  public function getTagName() {
    return 'style';
  }
  
  /**
   * Starts the capturing of css.
   * @param string $mode How the captured css should be added.
   */
  public function captureCssStart($mode=self::MODE_APPEND) {
    $this->captureStart(array('type' => self::TYPE_CSS), $mode);
  }
  
  /**
   * Starts the capturing of css.
   * @param string $mode How the captured css should be added.
   */
  public function captureCss($mode=self::MODE_APPEND) {
    $this->captureCssStart($mode);
  }
  
  /**
   * Ends the capturing of css.
   */
  public function captureCssEnd() {
    $this->captureEnd();
  }
  
  public function appendCss($css) {
    $this->addTag(array('type' => self::TYPE_CSS), $css, self::MODE_APPEND);
    return $this;
  }
  
  public function prependCss($css) {
    $this->addTag(array('type' => self::TYPE_CSS), $css, self::MODE_PREPEND);
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
      $value = "\n" . '/*<![CDATA[*/' . "\n" . $value . "\n" . '/*]]>*/' . "\n";
    }
    return parent::tagToString($attrs, $value);
  }
}