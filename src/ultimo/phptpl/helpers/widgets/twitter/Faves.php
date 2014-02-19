<?php

namespace ultimo\phptpl\helpers\widgets\twitter;

/**
 * http://twitter.com/about/resources/widgets/widget_search
 */
class Faves extends Widget {
  /**
   * Returns the default attributes of the widget.
   * @return array The default attrubutes of the widget.
   */
  public function getDefaultAttrs() {
    return $this->mergeAttrs(parent::getDefaultAttrs(), array(
        'type' => 'faves',
        'title' => '',
        'subject' => '',
        'user' => 'toptweets',
        'rrp' => 10,
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
