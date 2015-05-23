<?php

namespace ultimo\phptpl\helpers\support;

class Container {
  
  /**
   * The captured value.
   * @var string
   */
  protected $value = null;
  
  /**
   * Whether another capture may start.
   * @var boolean
   */
  protected $captureLock = false;
  
  /**
   * Metadata about the container
   * @var mixed
   */
  protected $metadata = null;
  
  /**
   * Constructor
   * @param mixed Metadata about the container.
   */
  public function __construct($metadata = null) {
    $this->metadata = $metadata;
  }
  
  /**
   * Starts the capturing.
   */
  public function captureStart() {
    if ($this->captureLock) {
      throw new ContainerException('Already capturing');
    }
    $this->captureLock = true;
    ob_start();
  }
  
  /**
   * Ends the capturing.
   */
  public function captureEnd() {
    if (!$this->captureLock) {
      throw new ContainerException('Not capturing');
    }
    $this->captureLock = false;
    $this->value = ob_get_clean();
  }
  
  /**
   * Returns the captured value.
   * @return string The captured value.
   */
  public function getValue() {
    return $this->value;
  }
  
  /**
   * Sets the captured value.
   * @param string $value The value to set.
   */
  public function setValue($value) {
    $this->value = $value;
  }
  
  /**
   * Magic functoin to convert this instance to a string. The string
   * representation of this class is the captured value.
   * @return string The captured value.
   */
  public function __toString() {
    return $this->value;
  }
  
  /**
   * Returns metadata about the container.
   * @return mixed Metadata about the container.
   */
  public function getMetadata() {
    return $this->metadata;
  }
}