<?php

namespace ultimo\phptpl;

abstract class HelperDecorator extends Helper {
  /**
   * Decorated helper.
   * @var Helper
   */
  protected $decorated;
  
  /**
   * Configuration
   * @var array
   */
  protected $config = array();
  
  /**
   * 
   * @param \ultimo\phptpl\Helper $helper
   */
  public function __construct(Helper $helper, array $config = array()) {
    $this->decorated = $helper;
    $this->config = $config;
    $this->engine = $helper->engine;
  }
  
  /**
   * Passes all method calls to decorated.
   * @param string $name Name of the method
   * @param array $arguments Arguments of the method.
   * @return mixed Result of the method called on decorated.
   */
  public function __call($name, array $arguments) {
    return call_user_func_array(array($this->decorated, $name), $arguments);
  }
  
  /**
   * Passes method call to decorated.
   * @return string String representation of this class.
   */
  public function __toString() {
    return $this->decorated->__toString();
  }
  
  /**
   * Passes method call to decorated.
   * @return mixed Result of the method called on decorated.
   */
  public function __invoke() {
    return $this->decorated->__invoke();
  }
  
  
}