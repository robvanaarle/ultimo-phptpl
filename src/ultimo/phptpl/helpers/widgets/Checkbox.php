<?php

namespace ultimo\phptpl\helpers\widgets;

class Checkbox extends \ultimo\phptpl\helpers\widgets\Widget {
  /**
   * Returns the default attributes of the widget.
   * @return array The default attrubutes of the widget.
   */
  public function getDefaultAttrs() {
    return array(
    );
  }
  
  /**
   * Renders the widget.
   * @return string The rendered widget.
   */
  public function render() {
    $attrs = $this->attrs;
    $attrs['type'] = 'checkbox';
    
    if (isset($attrs['value'])) {
      if ($attrs['value']) {
        $attrs['checked'] = 'checked';
      }
      unset($attrs['value']);
    }
    
    return \ultimo\util\net\html\Tag::createHtml('input', $attrs);
  }
}