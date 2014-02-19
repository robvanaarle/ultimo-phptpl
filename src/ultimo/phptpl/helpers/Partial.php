<?php

namespace ultimo\phptpl\helpers;

class Partial extends \ultimo\phptpl\Helper {
  
  /**
   * Helper initial function. Renders a script file.
   * @param string $relScriptPath The relative script path to render.
   * @param array $vars Extra variables for that script file.
   * @return string The rendered data.
   */
  public function __invoke($relScriptPath, array $vars = array()) {
    $prevVars = array();
    foreach ($vars as $name => $value) {
      if (isset($this->$name)) {
        $prevVars[$name] = $this->$name;
      } else {
        $prevVars[$name] = null;
      }
      $this->$name = $value;
    }

    $rendered = $this->engine->render($relScriptPath);
    
    foreach ($prevVars as $name => $value) {
      $this->$name = $value;
    }
    
    return $rendered;
  }
}