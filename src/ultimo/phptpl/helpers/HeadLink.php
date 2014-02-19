<?php

namespace ultimo\phptpl\helpers;

class HeadLink extends \ultimo\phptpl\helpers\support\HeadTag {
  const TYPE_CSS = 'text/css';

  /**
   * Helper initial function.
   * @return HeadLink This class.
   */
  public function __invoke() {
    return $this;
  }
  
  /**
   * Returns the name of the tags the helper generates.
   * @return string The name of the tags the helper generates.
   */
  public function getTagName() {
    return 'link';
  }
  
  /**
   * Creates and returns the file attributes as array.
   * @param string $href The url of the file.
   * @param string $media The media type of the file.
   * @param string $rel The rel of the file.
   * @param string $type The type of the file.
   * @return The file attributes as array.
   */
  protected function createFileAttrs($href, $media, $rel, $type) {
    return array(
      'href' => $href,
      'media' => $media,
      'rel' => $rel,
      'type' => $type);
  }

  /**
   * Appends a stylesheet.
   * @param string $href The url to the stylesheet.
   * @param string $media The medis type of the stylesheet.
   * @param string $dup What to do with duplicates.
   * @return HeadLink This instance for fluid design.
   */
  public function appendStylesheet($href, $media='', $dup=self::DUP_DISALLOWED) {
    $this->addTag($this->createFileAttrs($href, $media, 'stylesheet', self::TYPE_CSS), null, self::MODE_APPEND, $dup);
    return $this;
  }
  
  /**
   * Prepends a stylesheet.
   * @param string $href The url to the stylesheet.
   * @param string $media The medis type of the stylesheet.
   * @param string $dup What to do with duplicates.
   * @return HeadLink This instance for fluid design.
   */
  public function prependStylesheet($href, $media='', $dup=self::DUP_DISALLOWED) {
    $this->addTag($this->createFileAttrs($href, $media, 'stylesheet', self::TYPE_CSS), null, self::MODE_PREPEND, $dup);
    return $this;
  }
}