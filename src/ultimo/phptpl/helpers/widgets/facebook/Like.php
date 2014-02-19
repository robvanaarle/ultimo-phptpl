<?php

namespace ultimo\phptpl\helpers\widgets\facebook;

/**
 * http://developers.facebook.com/docs/reference/plugins/like/
 */
class Like extends \ultimo\phptpl\helpers\widgets\Widget {
  
  static public $defaultHeights = array(
      'layout=standard&show_faces=true' => 80,
      'layout=standard&show_faces=false' => 35,
      'layout=button_count&show_faces=true' => 21,
      'layout=button_count&show_faces=false' => 21,
      'layout=box_count&show_faces=true' => 90,
      'layout=box_count&show_faces=false' => 90,
  );
  
  /**
   * Returns the default attributes of the widget.
   * @return array The default attrubutes of the widget.
   */
  public function getDefaultAttrs() {
    return array(
      // iframe attrs
      'width' => '450',
      'height' => null,         // leave null to get default height
      'href' => '',             // link to page to like
      'layout' => 'standard',   // standard, button_count, box_count
      'show_faces' => 'true',   // true, false
      'action' => 'like',       // like, recommend
      'colorscheme' => 'light', // light, dark
      'font' => '',             // , arial, lucida grande, segoe ui, tahoma, trebuchet ms, verdana,
      'ref' => '',
      'locale' => ''            // en_US
    );
  }
  
  public function getDefaultHeight() {
    $key = 'layout=' . $this->attrs['layout'] . '&show_faces=' . $this->attrs['show_faces'];
    if (isset(self::$defaultHeights[$key])) {
      return self::$defaultHeights[$key];
    } else {
      return 90;
    }
  }
  
  public function setAttrs(array $attrs) {
    if (isset($attrs['show_faces']) && $attrs['show_faces'] != 'false') {
      $attrs['show_faces'] = $attrs['show_faces'] ? 'true' : 'false';
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
      $height = $this->getDefaultHeight();
    }
    
    $srcData = array(
        'href' => $this->attrs['href'],
        'send' => 'false',
        'layout' => $this->attrs['layout'],
        'width' => $this->attrs['width'],
        'show_faces' => $this->attrs['show_faces'],
        'action' => $this->attrs['action'],
        'colorscheme' => $this->attrs['colorscheme'],
        'height' => $height,
        'ref' => $this->attrs['ref'],
        'locale' => $this->attrs['locale']
    );
    
    $src = 'http://www.facebook.com/plugins/like.php?' . http_build_query($srcData);
    
    $iFrameAttrs = array(
        'src' => $src,
        'scrolling' => 'no',
        'frameborder' => '0',
        'style' => \ultimo\util\net\css\Definition::createInline(array(
            'border' => 'none',
            'overflow' => 'hidden',
            'width' => $this->attrs['width'] . 'px',
            'height' => $height . 'px'
        )),
        'allowTransparency' => 'true'
    );
    
    return \ultimo\util\net\html\Tag::createHtml('iframe', $iFrameAttrs, '');
  }
}