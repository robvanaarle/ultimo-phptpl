<?php

namespace ultimo\phptpl\helpers;

class HttpHeader extends \ultimo\phptpl\Helper {

  /**
   * Helper initial function. Sends a http header
   * @param string $data The http header data: a header name and value,
   * separated by ':'
   * @param boolean $replace Whether to replace sent headers with the same
   * name.
   * @param integer $responseCode The http response code.
   * @return HttpHeader This instance for fluid design.
   */
  public function __invoke($data, $replace=true, $responseCode=null) {
    header($data, $replace, $responseCode);
    return $this;
  }
  
}