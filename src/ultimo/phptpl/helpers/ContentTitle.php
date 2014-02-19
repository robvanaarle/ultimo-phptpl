<?php

namespace ultimo\phptpl\helpers;

class ContentTitle extends \ultimo\phptpl\Helper {
  
  protected $title = '';
  
  /**
   * Helper initial function. Escapes a string for HTML use.
   * @param string $str The string to escape.
   * @return string The escaped string.
   */
  public function __invoke($title=null) {
    if ($title !== null) {
      $this->title = $title;
    }
    return $this;
  }
  
  public function __toString() {
    return $this->title;
  }
}