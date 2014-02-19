<?php

namespace ultimo\phptpl\helpers\widgets\markitup;

class Bbcode extends \ultimo\phptpl\helpers\widgets\Widget {
  /**
   * Returns the default attributes of the widget.
   * @return array The default attrubutes of the widget.
   */
  public function getDefaultAttrs() {
    return array(
        'id' => $this->widgetId,
        'value' => '',
        'skin' => 'simple',
        'height' => null,
        'width' => null,
        'config' => array(
          'markupSet' => array(),
          'buttons' => array(
              'bold', 'italic', 'underline', 'link'
          ),
          'resizeHandle' => true
        ),
        'style' => array()
    );
  }
  
  static public $settings = array(
      'bold' => array('name' => 'Bold', 'className' => 'bold', 'key' => 'B', 'openWith' => '[b]', 'closeWith' => '[/b]'),
      'italic' => array('name' => 'Italic', 'className' => 'italic', 'key' => 'I', 'openWith' => '[i]', 'closeWith' => '[/i]'),
      'underline' => array('name' => 'Underline', 'className' => 'underline', 'key' => 'U', 'openWith' => '[u]', 'closeWith' => '[/u]'),
      'link' => array('name' => 'Link', 'className' => 'link', 'Key' => 'L', 'openWith' => '[url="[![Url]!]"]', 'closeWith' => '[/url]', 'placeHolder' => 'Link:')
  );
  
  /**
   * Renders the widget.
   * @return string The rendered widget.
   */
  public function render() {
    $this->engine->mediaLibrary()->appendJavascriptFile('jquery', 'jquery.min.js', '1.7.1');
    $this->engine->mediaLibrary()->appendJavascriptFile('jquery.markitup', 'jquery.markitup.js', '1.1.12');
    
    $attrs = $this->attrs;
    
    $skin = $attrs['skin'];
    unset($attrs['skin']);
    
    $this->engine->mediaLibrary()->appendStylesheet('jquery.markitup', "skins/{$skin}/style.css", '', '1.1.12');
    $this->engine->mediaLibrary()->appendStylesheet('jquery.markitup', "sets/bbcode/style.css", '', '1.1.12');
    
    $value = $attrs['value'];
    unset($attrs['value']);
    
    $config = $attrs['config'];
    unset($attrs['config']);
    $config = $this->mergeAttrs($config, array('markupSet' => $this->getMarkupSet($config['buttons'])));
    unset($config['buttons']);
    
    $config = json_encode($config);
    
    $textareaId = $attrs['id'];
    $holderId = 'markItUp'.ucfirst($attrs['id']);
    
    $js = "jQuery(document).ready(function() { 
      jQuery('#{$attrs['id']}').markItUp({$config});";
    
    if ($attrs['height'] !== null) {
      $height = $attrs['height'];
      unset($attrs['height']);
      $attrs['style']['height'] = $height;
      
      $js .= "\nvar heightDiff = jQuery('#{$holderId}').height() - jQuery('#{$textareaId}').height();
      jQuery('#{$textareaId}').css('height', {$height}-heightDiff + 'px');";
    }
    
    if ($attrs['width'] !== null) {
      $width = $attrs['width'];
      unset($attrs['width']);
      $attrs['style']['width'] = $width;
      
      $js .= "\nvar widthDiff = jQuery('#{$holderId}').width() - jQuery('#{$textareaId}').width();
      jQuery('#{$holderId}').css('width', '{$width}px');
      jQuery('#{$textareaId}').css('width', {$width}-widthDiff + 'px');";
    }
    
    $js .= "\n});";
    
    $this->engine->headScript()->appendJavascript($js);
    
    $attrs['style'] = \ultimo\util\net\css\Definition::createInline($attrs['style']);
    return \ultimo\util\net\html\Tag::createHtml('textarea', $attrs, $value);
  }
  
  protected function getMarkupSet($buttons) {
    $markupSetConfig = array();
    
    foreach ($buttons as $name) {
      $name = strtolower($name);
      if (isset(static::$settings[$name])) {
        $markupSetConfig[] = static::$settings[$name];
      }
    }
    
    return $markupSetConfig;
  }
}