<?php

namespace ultimo\phptpl;

abstract class Helper {
  /**
   * The engine the helper is for.
   * @var \ultimo\phptpl\Engine
   */
  protected $engine;
  
  /**
   * Constructor
   * @param Engine $engine The engine the helper is for.
   */
  public function __construct(Engine $engine) {
    $this->engine = $engine;
  }
}