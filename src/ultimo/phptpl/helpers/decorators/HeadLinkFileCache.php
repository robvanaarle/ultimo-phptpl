<?php

namespace ultimo\phptpl\helpers\decorators;

class HeadLinkFileCache extends HeadTagFileCache {
  /**
   * Prepends a stylesheet.
   * @param string $href The url to the stylesheet.
   * @param string $media The medis type of the stylesheet.
   * @param string $dup What to do with duplicates.
   * @return HeadLink This instance for fluid design.
   */
  public function prependStylesheet($href, $media='', $dup=\ultimo\phptpl\helpers\support\HeadTag::DUP_DISALLOWED) {
    parent::prependStylesheet($this->addVersion($href), $media, $dup);
    return $this;
  }
  
  /**
   * Appends a stylesheet.
   * @param string $href The url to the stylesheet.
   * @param string $media The medis type of the stylesheet.
   * @param string $dup What to do with duplicates.
   * @return HeadLink This instance for fluid design.
   */
  public function appendStylesheet($href, $media='', $dup=\ultimo\phptpl\helpers\support\HeadTag::DUP_DISALLOWED) {
    parent::appendStylesheet($this->addVersion($href), $media, $dup);
    return $this;
  }
}
