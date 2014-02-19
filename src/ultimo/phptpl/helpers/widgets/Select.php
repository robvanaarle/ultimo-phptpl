<?php

namespace ultimo\phptpl\helpers\widgets;

class Select extends \ultimo\phptpl\helpers\widgets\Widget {
  
  /**
   * Returns the default attributes of the widget.
   * @return array The default attrubutes of the widget.
   */
  public function getDefaultAttrs() {
    return array(
      'id' => '',
      'name' => '',
      'class' => '',
      'options' => array(),
      'values' => array(),
      'texts' => array(),
      'value' => '',
      'translateTexts' => false,
      'translateFormat' => '%s'
    );
  }
  
  /**
   * Renders the widget.
   * @return string The rendered widget.
   */
  public function render() {
    $attrs = $this->attrs;
    
    if ($attrs['id'] == '') {
      $attrs['id'] = $this->getWidgetId();
    }
    
    $values = $attrs['values'];
    unset($attrs['values']);
    $texts = $attrs['texts'];
    unset($attrs['texts']);
    $options = $attrs['options'];
    unset($attrs['options']);

    reset($values);
    reset($texts);
    
    // create options from the values and texts attributes
    while(true) {
      $value = current($values);
      $text = current($texts);
      
      next($values);
      next($texts);
      
      // break if there are no more values or texts
      if ($value === false || $text == false) {
        break;
      }
      $options[$value] = $text;
    }
    
    $value = $attrs['value'];
    unset($attrs['value']);
    
    $optionTags = array();
    foreach ($options as $optVal => $optText) {
      $optionTag = new \ultimo\util\net\html\Tag('option');
      $optionTag->attrs['value'] = $optVal;

      if ($optVal == $value) {
         $optionTag->attrs['selected'] = 'selected';
      }
      
      if ($attrs['translateTexts']) {
        $optTextKey = sprintf($attrs['translateFormat'], $optText);
        $optText = $this->engine->translate($optTextKey);
      }
      
      $optionTag->appendChild($optText);
      
      $optionTags[] = $optionTag;
    }

    unset($attrs['translateTexts']);
    unset($attrs['translateFormat']);
    
    return \ultimo\util\net\html\Tag::createHtml('select', $attrs, $optionTags);
  }
}