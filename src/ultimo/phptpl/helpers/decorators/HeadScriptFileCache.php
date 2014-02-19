<?php

namespace ultimo\phptpl\helpers\decorators;

class HeadScriptFileCache extends HeadTagFileCache {
  /**
   * Appends a javascript file.
   * @param string $src The url to the javascript file.
   * @param string $dup What to do with duplicates.
   * @return HeadScript This instance for fluid design.
   */
  public function appendJavascriptFile($src, $dup=\ultimo\phptpl\helpers\support\HeadTag::DUP_DISALLOWED) {
    parent::appendJavascriptFile($this->addVersion($src), $dup);
    return $this;
  }
  
  /**
   * Appends a javascript file.
   * @param string $src The url to the javascript file.
   * @param string $dup What to do with duplicates.
   * @return HeadScript This instance for fluid design.
   */
  public function prependJavascriptFile($src, $dup=\ultimo\phptpl\helpers\support\HeadTag::DUP_DISALLOWED) {
    parent::prependJavascriptFile($this->addVersion($src), $dup);
    return $this;
  }
}