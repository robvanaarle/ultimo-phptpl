<?php

namespace ultimo\phptpl\helpers;

class HeadMeta extends \ultimo\phptpl\helpers\support\HeadTag {
  
  /**
   * Helper initial function.
   * @return HeadMeta This class.
   */
  public function __invoke() {
    return $this;
  }
  
  /**
   * Returns the name of the tags the helper generates.
   * @return string The name of the tags the helper generates.
   */
  public function getTagName() {
    return 'meta';
  }
  
}