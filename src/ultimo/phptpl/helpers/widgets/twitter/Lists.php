<?php

namespace ultimo\phptpl\helpers\widgets\twitter;

/**
 * http://twitter.com/about/resources/widgets/widget_list
 */
class Lists extends Widget {
  /**
   * Returns the default attributes of the widget.
   * @return array The default attrubutes of the widget.
   */
  public function getDefaultAttrs() {
    return $this->mergeAttrs(parent::getDefaultAttrs(), array(
        'type' => 'lists',
        'title' => '',
        'subject' => '',
        'user' => 'twitter',
        'list' => 'team',
        'rrp' => 30,
        'interval' => 30000,
        'features' => array(
            'scrollbar' => true,
            'loop' => false,
            'live' => true,
            'behavior' => 'all',      // all, default
            'avatars' => true
        )
    ));
  }
  
}