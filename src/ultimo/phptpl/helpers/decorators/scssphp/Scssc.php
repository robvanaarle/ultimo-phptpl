<?php

namespace ultimo\phptpl\helpers\decorators\scssphp;

class Scssc extends \scssc {
  public function getPhpValue($value) {
    $phpValue = $this->compileValue($value);
    if ($value[0] == "string") {
      $delimLength = strlen($value[1]);
      
      if ($delimLength > 0) {
        $phpValue = substr($phpValue, $delimLength, -$delimLength);
      }
    }
    return $phpValue;
  }
}