<?php

namespace ultimo\phptpl\helpers\support;

abstract class HeadTag extends \ultimo\phptpl\Helper {
  const MODE_APPEND = 'append';
  const MODE_PREPEND = 'prepend';
  
  const DUP_ALLOWED = 'dup_allowed';
  const DUP_DISALLOWED = 'dup_disallowed';
  const DUP_DISALLOWED_STRICT = 'dup_disallowed_strict';
  
  /**
   * The added tags.
   * @var array
   */
  private $tags = array();
  
  /**
   * The attributes of the capturing in progress.
   * @var array
   */
  private $captureAttrs = null;
  
  /**
   * The mode of the capture in progress.
   * @var string
   */
  private $captureMode = null;
  
  /**
   * The container used for the capture in progress.
   * @var Container
   */
  private $container = null;
  
  /**
   * Returns the name of the tags the helper generates.
   * @return string The name of the tags the helper generates.
   */
  abstract public function getTagName();
  
  /**
   * Appends a tag.
   * @param array $attrs The tag attributes.
   * @param string $value The value of the tag.
   * @param string $dup What to do with duplicates.
   * @return HeadScript This instance for fluid design.
   */
  public function append($attrs, $value=null, $dup=self::DUP_ALLOWED) {
    $this->addTag($attrs, $value, self::MODE_APPEND, $dup);
    return $this;
  }
  
  /**
   * Prepends a tag.
   * @param array $attrs The tag attributes.
   * @param string $value The value of the tag.
   * @param string $dup What to do with duplicates.
   * @return HeadScript This instance for fluid design.
   */
  public function prepend($attrs, $value=null, $dup=self::DUP_ALLOWED) {
    $this->addTag($attrs, $value, self::MODE_PREPEND, $dup);
    return $this;
  }
  
  /**
   * Returns the index of the tag with the specified attributes and value
   * Enter description here ...
   * @param array $attrs The tag attributes.
   * @param string $value The value of the tag.
   * @param boolean $strict Whether all the existing tag attributess are
   * required to have the same value in the tag to search.
   * @return integer The index of the found tag, or -1 if the tag could not be
   * found.
   */
  public function findTag($attrs, $value, $strict=false) {
    foreach ($this->tags as $tagIndex => $tag) {
      foreach ($attrs as $attrName => $attrValue) {
        if (!isset($tag['attrs'][$attrName]) || $tag['attrs'][$attrName] != $attrValue) {
          continue 2;
        }
      }
      
      if ($value !== null && $tag['value'] != $value) {
        continue;
      }
      
      if ($strict) {
        foreach ($tag['attrs'] as $attrName => $attrValue) {
          if (!isset($attrs[$attrName]) || $attrs[$attrName] != $attrValue) {
            continue 2;
          }
        }
        
        if ($tag['value'] != $value) {
          continue;
        }
      }
      
      return $tagIndex;
    }
    return -1;
  }
  
  /**
   * Removes a tag specified by an index.
   * @param integer $tagIndex Index of the tag to remove
   * @return HeadTag This instance for fluid design.
   */
  public function removeTag($tagIndex) {
    unset($this->tags[$tagIndex]);
    $this->tags = array_values($this->tags);
    return $this;
  }
  
  /**
   * Starts the capturing of a tag value
   * @param array $attrs The attributes of the tag.
   * @param string $mode How the captured tag should be added.
   */
  public function captureStart($attrs = array(), $mode=self::MODE_APPEND) {
    if ($this->container === null) {
      $this->container = new Container();
    }
    
    $this->container->captureStart();
    $this->captureAttrs = $attrs;
    $this->captureMode = $mode;
  }
  
  /**
   * Ends the capturing of a tag value.
   */
  public function captureEnd() {
    $this->container->captureEnd();
    $this->addTag($this->captureAttrs, $this->container->getValue(), $this->captureMode);
    $this->container->setValue('');
  }
  
  /**
   * Magic functoin to convert this instance to a string. The string
   * representation of this class is a newline separated list of all tags.
   * @return string A newline separated list of all tags.
   */
  public function __toString() {
    $html = array();
    foreach ($this->tags as $tag) {
      $html[] = $this->tagToString($tag['attrs'], $tag['value']);
    }
    
    return implode('', $html);
  }
  
  /**
   * Adds a tag.
   * @param array $attrs The tag attributes.
   * @param string $value The value of the tag.
   * @param string $mode How the tag should be added.
   * @param string $dup What to do with duplicates.
   */
  protected function addTag($attrs, $value, $mode, $dup=self::DUP_ALLOWED) {
    if ($dup !== self::DUP_ALLOWED) {
      $dupIndex = $this->findTag($attrs, $value, $dup==self::DUP_DISALLOWED_STRICT);
      if ($dupIndex != -1) {
        if ($mode == self::MODE_APPEND) {
          return;
        } else {
          // remove the dup, so the new entry is prepended
          $this->removeTag($dupIndex);
        }
      }
    }
    
    $tag = array('attrs' => $attrs, 'value' => $value);
    if ($mode == self::MODE_APPEND) {
      $this->tags[] = $tag;
    } elseif ($mode == self::MODE_PREPEND) {
      array_unshift($this->tags, $tag);
    }
  }
  
  /**
   * Converts tag attributes and value to a string.
   * @param array $attrs The tag attributes.
   * @param string $value The value of the tag.
   * @return string The tag as string.
   */
  protected function tagToString($attrs, $value) {
    $attrsHtml = array();
    foreach ($attrs as $attrName => $attrValue) {
      $attrsHtml[] = $attrName . '="' . htmlentities($attrValue) . '"';
    }
    
    $tagName = $this->getTagName();
    if ($value === null) {
      return '<' . $tagName . ' ' . implode(' ', $attrsHtml) . ' />' . "\n";
    } else {
      return '<' . $tagName . ' ' . implode(' ', $attrsHtml) . '>' . $value . '</' . $tagName . '>' . "\n";
    }
  }
}