<?php

namespace ultimo\phptpl\helpers\widgets\twitter;

/**
 * http://twitter.com/about/resources/widgets/widget_profile
 */
class Profile extends Widget {
  /**
   * Returns the default attributes of the widget.
   * @return array The default attrubutes of the widget.
   */
  public function getDefaultAttrs() {
    return $this->mergeAttrs(parent::getDefaultAttrs(), array(
        'type' => 'profile',
        'user' => 'twitter',
        'rrp' => 4,
        'interval' => 30000,
        'features' => array(
            'scrollbar' => false,
            'loop' => false,
            'live' => true,
            'behavior' => 'all',      // all, default
            'avatars' => false
        )
    ));
  }
  
}
