<?php

namespace ultimo\phptpl\helpers\support;

class ContainerStack {
  /**
   * Stacked containers.
   * @var array
   */
  protected $containers = array();
  
  /**
   * Starts the capturing of a new container.
   */
  public function captureStart($metadata=null) {
    $container = new Container($metadata);
    $this->containers[] = $container;
    $container->captureStart();
  }
  
  /**
   * Ends the capturing.
   * @return Container The captured container.
   */
  public function captureEnd() {
    if (count($this->containers) == 0) {
      throw new ContainerException('Cannot end capture of empty container stack');
    }
    
    $container = array_pop($this->containers);
    $container->captureEnd();
    return $container;
  }
}