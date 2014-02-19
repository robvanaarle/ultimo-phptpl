<?php

namespace ultimo\phptpl\helpers;

class MediaLibrary extends \ultimo\phptpl\Helper {
  protected $baseUrl = 'media/library';
  
  public function __invoke() {
    return $this;
  }
  
  public function setBaseUrl($baseUrl) {
    $this->baseUrl = rtrim($baseUrl, '/');
  }
  
  public function getBaseUrl() {
    return $this->baseUrl;
  }
  
  public function appendJavascriptFile($library, $src, $minVersion=null, $maxVersion=null) {
    $this->engine->headScript()->appendJavascriptFile($this->getBaseUrl() . '/' . $library . '/' . $src, \ultimo\phptpl\helpers\support\HeadTag::DUP_DISALLOWED);
  }
  
  public function prependJavascriptFile($library, $src, $minVersion=null, $maxVersion=null) {
    $this->engine->headScript()->prependJavascriptFile($this->getBaseUrl() . '/' . $library . '/' . $src, \ultimo\phptpl\helpers\support\HeadTag::DUP_DISALLOWED);
  }
  
  public function appendStylesheet($library, $href, $media='', $minVersion=null, $maxVersion=null) {
    $this->engine->headLink()->appendStylesheet($this->getBaseUrl() . '/' . $library . '/' . $href, $media, \ultimo\phptpl\helpers\support\HeadTag::DUP_DISALLOWED);
  }
  
  public function prependStylesheet($library, $href, $media='', $minVersion=null, $maxVersion=null) {
    $this->engine->headLink()->prependStylesheet($this->getBaseUrl() . '/' . $library . '/' . $href, $media, \ultimo\phptpl\helpers\support\HeadTag::DUP_DISALLOWED);
  }
  
}