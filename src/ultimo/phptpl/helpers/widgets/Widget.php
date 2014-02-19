<?php

namespace ultimo\phptpl\helpers\widgets;

abstract class Widget {  
  /**
   * The id of this widget.
   * @var string
   */
  protected $widgetId;
  
  /**
   * The attributes of the widget, a hashtable with the attribute name as keys
   * and its value as values.
   * @var array
   */
  protected $attrs;
  
  /**
   * The engine the widget is for.
   * @var \ultimo\phptpl\Engine
   */
  protected $engine;

  /**
   * Constructor.
   * @param \ultimo\phptpl\Engine $engine The engine the widget is for.
   * @param string $widgetId The id of the widget.
   * @param array $attrs The attributes of the widget.
   */
  public function __construct(\ultimo\phptpl\Engine $engine, $widgetId, array $attrs = array()) {
    $this->engine = $engine;
    $this->widgetId = $widgetId;
    $this->setAttrs($this->mergeAttrs($this->getDefaultAttrs(), $attrs));
  }
  
  public function setAttrs(array $attrs) {
    $this->attrs = $attrs;
  }
  
  /**
   * Merges two multi dimensional attribute arrays
   * @param array $attrs1 The attributes to merge into.
   * @param array $attrs2 The attributes to merge with.
   * @return array The merged attributes.
   */
  protected function mergeAttrs($attrs1, $attrs2) {
    foreach ($attrs2 as $key => &$value) {
      if (is_array($value) && array_key_exists($key, $attrs1) && is_array($attrs1[$key])) {
        $attrs1[$key] = $this->mergeAttrs($attrs1[$key], $value);
      } else {
        $attrs1[$key] = $value;
      }
    }
    
    return $attrs1;
  }
  
  /**
   * Returns the default attributes of the widget.
   * @return array The default attrubutes of the widget.
   */
  public function getDefaultAttrs() {
    return array();
  }
  
  /**
   * Renders the widget.
   * @return string The rendered widget.
   */
  abstract public function render();
  
  /**
   * Magic functoin to convert this instance to a string. The string
   * representation of this class is the rendered widget.
   * @return string The rendered widget.
   */
  public function __toString() {
    return $this->render();
  }
  
  /**
   * Returns the id of the widget.
   * @return string The id of the widget.
   */
  public function getWidgetId() {
    return $this->widgetId;
  }
}