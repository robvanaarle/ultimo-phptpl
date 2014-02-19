<?php

namespace ultimo\phptpl\helpers\widgets\facebook;

/**
 * http://developers.facebook.com/docs/reference/plugins/like-box/
 */
class Likebox extends \ultimo\phptpl\helpers\widgets\Widget {
  
  static public $defaultHeights = array(
      'header' => 32,
      'base' => 140,
      'stream' => 300,
      'faces' => 118
  );
  
  /**
   * Returns the default attributes of the widget.
   * @return array The default attrubutes of the widget.
   */
  public function getDefaultAttrs() {
    return array(
      // iframe attrs
      'width' => '292',
      'height' => null,         // leave empty to get default height
      'href' => '',             // link to facebook page to like
      'colorscheme' => 'light', // light, dark
      'show_faces' => 'true',   // true, false
      'stream' => 'true',       // true, false
      'header' => 'true',       // true, false
      'border_color' => '',
      'force_wall' => 'false',  // true, false
      'locale' => '',           // en_US
      'show_border' => 'true'
    );
  }
  
  public function calculateDefaultHeight() {
    $height = self::$defaultHeights['base'];
    
    if ($this->attrs['header'] == 'true') {
      $height += self::$defaultHeights['header'];
    }
    
    if ($this->attrs['stream'] == 'true') {
      $height += self::$defaultHeights['stream'];
    }
    
    if ($this->attrs['show_faces'] == 'true') {
      $height += self::$defaultHeights['faces'];
    }
    
    return $height;
  }
  
  public function setAttrs(array $attrs) {
    $booleanKeys = array ('show_faces', 'stream', 'header', 'show_border');
    
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
    $height = $this->attrs['height'];
    if ($height === null) {
      $height = $this->calculateDefaultHeight();
    }
    
    $srcData = array(
        'href' => $this->attrs['href'],
        'width' => $this->attrs['width'],
        'height' => $height,
        'colorscheme' => $this->attrs['colorscheme'],
        'show_faces' => $this->attrs['show_faces'],
        'border_color' => $this->attrs['border_color'],
        'stream' => $this->attrs['stream'],
        'header' => $this->attrs['header'],
        'locale' => $this->attrs['locale'],
        'show_border' => $this->attrs['show_border']
    );
    
    $src = 'http://www.facebook.com/plugins/likebox.php?' . http_build_query($srcData);
    
    $iFrameAttrs = array(
        'src' => $src,
        'scrolling' => 'no',
        'frameborder' => '0',
        'style' => \ultimo\util\net\css\Definition::createInline(array(
            'border' => 'none',
            'overflow' => 'hidden',
            //'width' => $this->attrs['width'] . 'px',
            //'height' => $height . 'px'
        )),
        'allowTransparency' => 'true'
    );
    
    return \ultimo\util\net\html\Tag::createHtml('iframe', $iFrameAttrs, '');
  }
}