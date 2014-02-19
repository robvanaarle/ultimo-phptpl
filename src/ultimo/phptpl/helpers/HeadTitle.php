<?php

namespace ultimo\phptpl\helpers;

class HeadTitle extends \ultimo\phptpl\Helper {
  
  /**
   * The title.
   * @var string
   */
  protected $title = '';
  
  /**
   * Helper initial function. Sets the title.
   * @param string $title The title.
   * @return HeadTitle This instance for fluid design.
   */
  public function __invoke($title=null) {
    if ($title !== null) {
      $this->title = $title;
    }
    return $this;
  }
  
  /**
   * Magic functoin to convert this instance to a string. The string
   * representation of this class is a title tag.
   * @return string A title tag.
   */
  public function __toString() {
    return '<title>' . $this->engine->escape($this->title) . '</title>';
  }
}