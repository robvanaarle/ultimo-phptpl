<?php

namespace ultimo\phptpl;

class EngineException extends \Exception {
  const TEMPLATE_NOT_FOUND = 1;
  const HELPER_NOT_FOUND = 2;
}