<?php

namespace ultimo\phptpl\helpers\widgets\facebook;

/**
 * http://developers.facebook.com/docs/reference/plugins/like-box/
 */
class Pageplugin extends \ultimo\phptpl\helpers\widgets\Widget {
  
  /**
   * Returns the default attributes of the widget.
   * @return array The default attrubutes of the widget.
   */
  public function getDefaultAttrs() {
    return array(
      // div attrs
      'data-href' => null,
      'data-width' => null, // default 340
      'data-height' => null, // default 500
      'data-hide-cover' => null, // default false
      'data-show-facepile' => null, // default true
      'data-show-posts' => null, // default false 
      'data-hide-cta' => null, // default false
      'data-small-header' => null, // default false
      'data-adapt-container-width' => null, // default true
      'class' => 'fb-page'
    );
  }
  
  public function setAttrs(array $attrs) {
    $booleanKeys = array ('data-hide-cover', 'data-show-facepile', 'data-data-show-posts', 'hide-cta', 'data-small-header', 'data-adapt-container-width');
    
    foreach ($booleanKeys as $key) {
      if (isset($attrs[$key]) && $attrs[$key] != 'false') {
        $attrs[$key] = $attrs[$key] ? 'true' : 'false';
      }
    }
    
    parent::setAttrs($attrs);
  }
  
  /**
   * Renders the widget.
   * @return string The rendered widget.
   */
  public function render() {
    $divAttrs = array();
    foreach ($this->attrs as $name => $value) {
      if ($value === null) {
        continue;
      }
      $divAttrs[$name] = $value;
    }
    
    $result = new \ultimo\util\net\html\Tag('div', $divAttrs, array(
                new \ultimo\util\net\html\Tag('div', array('class' => 'fb-xfbml-parse-ignore'), array(
                 new \ultimo\util\net\html\Tag('blockquote', array('cite' => $divAttrs['data-href']), array(
                   new \ultimo\util\net\html\Tag('a', array('href' => $divAttrs['data-href']), 'Facebook'),
              ))))));
    return $result->toHtml();
  }
}