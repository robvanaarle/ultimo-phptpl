<?php

namespace ultimo\phptpl\helpers;

class EscapeUrl extends \ultimo\phptpl\Helper {

  /**
   * Helper initial function. Escapes an url.
   * @param string $url The url to escape.
   * @return string The escaped url.
   */
  public function __invoke($url) {
   return str_replace('&', '&amp;', $url);
  }
}