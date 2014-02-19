<?php

namespace ultimo\phptpl\helpers\widgets\keithwood;

class Datetimeentry extends \ultimo\phptpl\helpers\widgets\Widget {
  
  /**
   * Returns the default attributes of the widget.
   * @return array The default attrubutes of the widget.
   */
  public function getDefaultAttrs() {
    return array(
      'type' => 'text',
      'name' => '',
      'value' => '',
      'class' => '',
      'id' => '',
      'autocomplete' => 'off',
      'config' => array(
        'phpDatetimeFormat' => null, // The PHP format of the datetimeFormat, if set replaces the datetimeFormat
        'datetimeFormat' => 'O/D/Y H:Ma', // The format of the date text: 
            // 'y' for short year, 'Y' for full year, 'o' for month, 'O' for two-digit month, 
            // 'n' for abbreviated month name, 'N' for full month name, 
            // 'd' for day, 'D' for two-digit day, 'w' for abbreviated day name and number, 
            // 'W' for full day name and number), 'h' for hour, 'H' for two-digit hour, 
            // 'm' for minute, 'M' for two-digit minutes, 's' for seconds, 
            // 'S' for two-digit seconds, 'a' for AM/PM indicator (omit for 24-hour) 
        'datetimeSeparators' => '.', // Additional separators between datetime portions 
        'monthNames' => array('January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'), // Names of the months 
        'monthNamesShort' => array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'), // Abbreviated names of the months 
        // Names of the days 
        'dayNames' => array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), 
        'dayNamesShort' => array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'), // Abbreviated names of the days 
        'ampmNames' => array('AM', 'PM'), // Names of morning/evening markers 
        // The popup texts for the spinner image areas 
        'spinnerTexts' => array('Today', 'Previous field', 'Next field', 'Increment', 'Decrement'), 
     
        'appendText' => '', // Display text following the input box, e.g. showing the format 
        'initialField' => 0, // The field to highlight initially, 0 = hours, 1 = minutes, ... 
        'useMouseWheel' => true, // True to use mouse wheel for increment/decrement if possible, 
            // false to never use it 
        'shortYearCutoff' => '+10', // The century cutoff for two-digit years, 
            // absolute (numeric) or relative (string) 
        'defaultDatetime' => null, // The date to use if none has been set, leave at null for now 
        'minDatetime' => null, // The earliest selectable datetime, or null for no limit 
        'maxDatetime' => null, // The latest selectable datetime, or null for no limit 
        'minTime' => null, // The earliest selectable time regardless of date, or null for no limit 
        'maxTime' => null, // The latest selectable time regardless of date, or null for no limit 
        'timeSteps' => array(1, 1, 1), // Steps for each of hours/minutes/seconds when incrementing/decrementing 
        'spinnerImage' => 'spinnerDefault.png', // The URL of the images to use for the date spinner 
            // Seven images packed horizontally for normal, each button pressed, and disabled 
        'spinnerSize' => array(20, 20, 8), // The width and height of the spinner image, 
            // and size of centre button for current date 
        'spinnerBigImage' => '', // The URL of the images to use for the expanded date spinner 
            // Seven images packed horizontally for normal, each button pressed, and disabled 
        'spinnerBigSize' => array(40, 40, 16), // The width and height of the expanded spinner image, 
            // and size of centre button for current date 
        'spinnerIncDecOnly' => false, // True for increment/decrement buttons only, false for all 
        'spinnerRepeat' => array(500, 250), // Initial and subsequent waits in milliseconds 
            // for repeats on the spinner buttons 
        'beforeShow' => null, // Function that takes an input field and 
            // returns a set of custom settings for the date entry 
        'altField' => null, // Selector, element or jQuery object for an alternate field to keep synchronised 
        'altFormat' => 'Y-O-D H:M:S' // A separate format for the alternate field 
      )
    );
  }
  
  /**
   * Converts a php date format to the date format used in the datetiimeentry
   * library.
   * @param string $phpFormat The php date format.
   * @return string The datetimeentry date format.
   */
  public function fromPhpFormat($phpFormat) {
    /*
     * 'datetimeFormat' => 'O/D/Y H:Ma', // The format of the date text: 
            // 'y' for short year, 'Y' for full year, 'o' for month, 'O' for two-digit month, 
            // 'n' for abbreviated month name, 'N' for full month name, 
            // 'd' for day, 'D' for two-digit day, 'w' for abbreviated day name and number, 
            // 'W' for full day name and number), 'h' for hour, 'H' for two-digit hour, 
            // 'm' for minute, 'M' for two-digit minutes, 's' for seconds, 
            // 'S' for two-digit seconds, 'a' for AM/PM indicator (omit for 24-hour)
     */
    
    $table = array(
      'n' => 'o', 'M' => 'n', 'F' => 'N', 'm' => 'O',
      'j' => 'd', 'd' => 'D', // '?' => 'w', '?' => 'W',
      'i' => 'M', 's' => 'S',
      'A' => 'a',
      'g' => 'h', 'G' => 'h', 'h' => 'H'
    );
    
    return str_replace(array_keys($table), array_values($table), $phpFormat);
  }
  
  /**
   * Renders the widget.
   * @return string The rendered widget.
   */
  public function render() {
    $attrs = $this->attrs;

    $config = $attrs['config'];
    // remove the config from the specified attributes
    unset($attrs['config']);
    
    if (isset($config['phpDatetimeFormat'])) {
      $config['datetimeFormat'] = $this->fromPhpFormat($config['phpDatetimeFormat']);
      if ($config['minDatetime'] instanceof \DateTime) {
        $config['minDatetime'] = $config['minDatetime']->format($config['phpDatetimeFormat']);
      }
      if ($config['maxDatetime'] instanceof \DateTime) {
        $config['maxDatetime'] = $config['maxDatetime']->format($config['phpDatetimeFormat']);
      }
      if ($attrs['value'] instanceof \DateTime) {
        $attrs['value'] = $attrs['value']->format($config['phpDatetimeFormat']);
      }
    }
    unset($config['phpDatetimeFormat']);
    
    $attrs['class'] .= " {$this->widgetId}_hidden";
    $config['altField'] = ".{$this->widgetId}_hidden";
    
    $widgetFieldAttrs = $attrs;
    unset($widgetFieldAttrs['name']);
    $widgetFieldAttrs['id'] = $this->widgetId.'_widget';
    
    $widgetField = \ultimo\util\net\html\Tag::createHtml('input', $widgetFieldAttrs);
    
    $config = json_encode($config);
    
    $js = "$(document).ready(function() {
      jQuery('.{$this->widgetId}_hidden').css('display', 'none');
      jQuery('.{$this->widgetId}_hidden').after('".addslashes($widgetField)."');
      jQuery('#{$this->widgetId}_widget').datetimeEntry({$config});
    });";

    $this->engine->headScript()->appendJavascript($js);
      
    $this->engine->mediaLibrary()->appendJavascriptFile('jquery', 'jquery.min.js', '1.7.1');
    $this->engine->mediaLibrary()->appendJavascriptFile('jquery.datetimeentry', 'jquery.datetimeentry.min.js', '1.0.1');
    return \ultimo\util\net\html\Tag::createHtml('input', $attrs);
  }
}