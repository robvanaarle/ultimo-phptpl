<?php

namespace ultimo\phptpl\helpers\widgets\youtube;

class Video extends \ultimo\phptpl\helpers\widgets\Widget {
  /**
   * Returns the default attributes of the widget.
   * @return array The default attrubutes of the widget.
   */
  public function getDefaultAttrs() {
    return array(
        'videoId' => '',
        'width' => 480,
        'height' => 360,
        'https' => false,
        'privacy_mode' => false,
        'show_suggestions' => false
    );
  }
  
  /**
   * Renders the widget.
   * @return string The rendered widget.
   */
  public function render() {
    $width = $this->attrs['width'];
    $height = $this->attrs['height'];
    
    if (is_int($width)) {
      $width .= 'px';
    }
    
    if (is_int($height)) {
      $height .= 'px';
    }
    
    $iFrameAttrs = array(
        'style' => \ultimo\util\net\css\Definition::createInline(array(
                'height' => $width,
                'width' => $height,
                'border' => '0px'
                )),
        //'allowfullscreen' => 'allowfullscreen'
    );
    
    $scheme = 'http';
    if ($this->attrs['https']) {
      $scheme = 'https';
    }
    
    $host = 'www.youtube.com';
    if ($this->attrs['privacy_mode']) {
      $host = 'www.youtube-nocookie.com';
    }
    
    $src = $scheme . '://' . $host . '/embed/' . $this->attrs['videoId'];
    
    if ($this->attrs['show_suggestions']) {
      $src = $src . '?rel=0';
    }
    
    $iFrameAttrs['src'] = $src;
    
    return \ultimo\util\net\html\Tag::createHtml('iframe', $iFrameAttrs, '');
  }
}