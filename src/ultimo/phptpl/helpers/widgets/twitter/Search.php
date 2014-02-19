<?php

namespace ultimo\phptpl\helpers\widgets\twitter;

/**
 * http://twitter.com/about/resources/widgets/widget_search
 */
class Search extends Widget {
  /**
   * Returns the default attributes of the widget.
   * @return array The default attrubutes of the widget.
   */
  public function getDefaultAttrs() {
    return $this->mergeAttrs(parent::getDefaultAttrs(), array(
        'type' => 'search',
        'title' => '',
        'subject' => '',
        'search' => 'rainbow',
        'rrp' => 30,
        'interval' => 30000,
        'features' => array(
            'scrollbar' => false,
            'loop' => true,
            'live' => true,
            'behavior' => 'default',      // all, default
            'avatars' => true,
            'hashtags' => false
        )
    ));
  }
  
}
