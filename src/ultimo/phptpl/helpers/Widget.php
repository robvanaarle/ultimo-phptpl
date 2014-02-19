<?php

namespace ultimo\phptpl\helpers;

class Widget extends \ultimo\phptpl\Helper {
  
  /**
   * The systematic id of the next widget.
   * @var integer
   */
  protected $nextWidgetId = 0;
  
  /**
   * The cached widgets, a hashtable with the widget id as keys and the widgets
   * are values.
   * @var array
   */
  protected $widgets = array();
  
  /**
   * Initial helper function. Constructs and returns a widget with the specified
   * name. Widgets are looked for in the 'helpers/widgets' directory set in the
   * engine.
   * @param string $widgetName The name of the widget to get.
   * @param array $attrs The attributes for the widget.
   * @return widgets\Widget The constructed widget.
   */
  public function __invoke($widgetName, array $attrs = array()) {
    $widgetId = '__widgetid_' . $this->nextWidgetId;
    $this->nextWidgetId++;
    return $this->constructWidget($widgetName, $widgetId, $attrs);
  }
  
  /**
   * Constructs and returns a widget with the specified name.
   * @param string $widgetName The name of the widget to get.
   * @param integer The id of the widget.
   * @param array $attrs The attributes for the widget.
   * @return widgets\Widget The constructed widget.
   */
  protected function constructWidget($widgetName, $widgetId, array $attrs = array()) {
    
    $nameElems = explode(':', $widgetName);
    $nameElems[count($nameElems)-1] = ucfirst($nameElems[count($nameElems)-1]);

    $qName = implode('\\', $nameElems);
    
    $filePath = implode(DIRECTORY_SEPARATOR, $nameElems) . '.php';
    foreach ($this->engine->getHelperPaths() as $helperPath) {
      $widgetFile = $helperPath['path'] . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . $filePath;
      if (is_readable($widgetFile)) {
        // The autoloader executes the next line
        //require_once $widgetFile;
        
        $class = $helperPath['namespace'] . '\widgets\\' . $qName;
        $widget = new $class($this->engine, $widgetId, $attrs);
        return $widget;
      }
    }
    
    throw new \ultimo\phptpl\EngineException("Could not find widget class {$widgetName}.");
  }
}