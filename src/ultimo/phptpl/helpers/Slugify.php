<?php

namespace ultimo\phptpl\helpers;

class Slugify extends \ultimo\phptpl\Helper {
  
  /**
   * Helper initial function. Converts a string to slug.
   * @param string $plain The string to convert to slug.
   * @return string The slug created from the plain input string.
   */
  public function __invoke($plain) {
    return \ultimo\util\string\Slug::slugify($plain);
  }
}