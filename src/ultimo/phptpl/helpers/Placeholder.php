<?php

namespace ultimo\phptpl\helpers;

class Placeholder extends \ultimo\phptpl\Helper {
  /**
   * Cached containers, a hashtable with the container name as keys and its 
   * value as values
   * @var array
   */
  protected $containers = array();
  
  /**
   * Helper initial function. Returns the container with the specified name.
   * @param string $name The name of the container to get.
   * @return support\Container The container with the specified name.
   */
  public function __invoke($name) {
    if (!isset($this->containers[$name])) {
      $this->containers[$name] = new support\Container();
    }
    return $this->containers[$name];
  }
}