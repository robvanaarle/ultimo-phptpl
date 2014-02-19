<?php

namespace ultimo\phptpl\helpers;

class Escape extends \ultimo\phptpl\Helper {
  /**
   * Helper initial function. Escapes a string for HTML use.
   * @param string $str The string to escape.
   * @return string The escaped string.
   */
  public function __invoke($str) {
    return htmlentities($str, ENT_COMPAT, $this->engine->getEncoding());
  }
}