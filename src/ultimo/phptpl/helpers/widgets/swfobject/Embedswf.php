<?php

namespace ultimo\phptpl\helpers\widgets\swfobject;

class Embedswf extends \ultimo\phptpl\helpers\widgets\Widget {
  /**
   * Returns the default attributes of the widget.
   * @return array The default attrubutes of the widget.
   */
  public function getDefaultAttrs() {
    return array(
        'swfUrl' => '',
        'id' => null,
        'width' => 300,
        'height' => 200,
        'version' => '9.0.0',
        'expressInstallSwfurl' => null,
        'flashvars' => array(),
        'params' => array(),
        'attributes' => array(),
        'callbackFn' => null
    );
  }
  
  /**
   * Renders the widget.
   * @return string The rendered widget.
   */
  public function render() {
    $this->engine->mediaLibrary()->appendJavascriptFile('swfobject', 'swfobject.js', '2.2');
    
    $html = '';
    if ($this->attrs['id'] === null) {
      $this->attrs['id'] = 'replace_' . $this->widgetId;
      $html = '<div id="'.$this->attrs['id'].'">Flash is required.</div>';
    }
    
    $attrNames = array('swfUrl', 'id', 'width', 'height', 'version', 'expressInstallSwfurl', 'flashvars', 'params', 'attributes', 'callbackFn');
    
    $js = array('swfobject.embedSWF(');
    foreach ($attrNames as $index => $attrName) {
      if ($index > 0) {
        $js[] .= ', ';
      }
      $js[] = json_encode($this->attrs[$attrName]);
    }
    $js[] = ');';
    
    $html .= '<script type="text/javascript">' . "\n";
    $html .= implode('', $js);
    $html .= "\n</script>";
    return $html;
  }
}

/*
<script type="text/javascript">

var flashvars = {
  name1: "hello",
  name2: "world",
  name3: "foobar"
};
var params = {
  menu: "false"
};
var attributes = {
  id: "myDynamicContent",
  name: "myDynamicContent"
};

swfobject.embedSWF("myContent.swf", "myContent", "300", "120", "9.0.0","expressInstall.swf", flashvars, params, attributes);

 */