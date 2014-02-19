<?php

namespace ultimo\phptpl;

class Engine {
  
  /**
   * The output encoding character set.
   * @var string
   */
  protected $_encoding = 'UTF-8';
  
  /**
   * The base paths, a hashtable with the following keys:
   * path: The path to the directory.
   * namespace: The namespace of the files in the basepath.
   * @var array
   */
  protected $_basePaths = array();
  
  /**
   * The script paths. At rendering, scripts are looked for in these paths.
   * @var array
   */
  protected $_scriptPaths = array();
  
  /**
   * Helper classes, a hashtable with the name of the helper as name, and the
   * classname of the helper as value.
   * @var array
   */
  protected $_helperClasses = array();
  
  /**
   * The helper paths, a hashtable with the following keys:
   * path: The path to the directory with helpers.
   * namespace: The namespace of the helpers in the basepath.
   * @var array
   */
  protected $_helperPaths = array();
  
  /**
   * Helper decorators, a hashtable with helpernames as name and an hashtable
   * as value. That hashtable has the follow keys:
   * class: Fully qualified classname of the decorator
   * config: An array with config parameters
   * @var array
   */
  protected $_helperDecorators = array();
  
  /**
   * The scriptpath being rendered.
   * @var string
   */
  protected $_script = null;
  
  /**
   * The helper cache, a hashtable with helper name as key and the helper
   * object as value.
   * @var array
   */
  protected $_helpers = array();
  
  /**
   * Constructor.
   */
  public function __construct() {
    $this->addHelperPath(__DIR__ . DIRECTORY_SEPARATOR . 'helpers');
  }
  
  /**
   * Adds a base path. A script and helper path are also added from this base
   * path.
   * @param string $path The path to the base directory.
   * @param string $namespace The namespace of the files in the base path.
   */
  public function addBasePath($path, $namespace='ultimo\phptpl') {
    $path = rtrim($path, '\\/');
    $this->addPath(array('path' => $path, 'namespace' => $namespace), $this->_basePaths);
    
    $path .= DIRECTORY_SEPARATOR;
    $this->addScriptPath($path . 'scripts');
    $this->addHelperPath($path . 'helpers', $namespace . '\helpers');
  }
  
  /**
   * Clears all paths.
   */
  public function resetBasePaths() {
    $this->_basePaths = array();
    $this->_helperPaths = array();
    $this->_scriptPaths = array();
  }
  
  /**
   * Adds a script path.
   * @param string $path The script path.
   */
  public function addScriptPath($path) {
    $this->addPath(rtrim($path, '\\/'), $this->_scriptPaths);
  }
  
  /**
   * Adds a helper path.
   * @param string $path The helper path.
   * @param stirng $namespace The namespace of the helpers in the helper path.
   */
  public function addHelperPath($path, $namespace='ultimo\phptpl\helpers') {
    $this->addPath(array('path' => rtrim($path, '\\/'), 'namespace' => $namespace), $this->_helperPaths);
  }
  
  /**
   * Prepends a path to an array of paths, if it is not already in that array.
   * @param string $path The path to prepend.
   * @param array $paths The array of paths to prepend the path to.
   */
  protected function addPath($path, &$paths) {
    // remove the path, if it is already in the paths array
    if (($index = array_search($path, $paths)) !== false) {
      unset($paths[$index]);
      $paths = array_values($paths);
    }
    
    // prepend the path
    array_unshift($paths, $path);
  }
  
  /**
   * Returns the base paths.
   * @return array The base paths.
   */
  public function getBasePaths() {
    return $this->_basePaths;
  }
  
  /**
   * Returns the script paths.
   * @return array The script paths.
   */
  public function getScriptPaths() {
    return $this->_scriptPaths;
  }
  
  /**
   * Returns the helper paths.
   * @return array The helper paths.
   */
  public function getHelperPaths() {
    return $this->_helperPaths;
  }
  
  /**
   * Returns the helper class by helper name in the helper paths.
   * @param string $helperName Name of the helper.
   * @return string Fully qualified classname of the helper or null if the
   * helper name could not be found.
   */
  protected function findHelperClassInHelperPaths($helperName) {
    $fileName = $helperName . '.php';
    
    foreach ($this->_helperPaths as $helperPath) {
      $helperFile = $helperPath['path'] . DIRECTORY_SEPARATOR . $fileName;
      if (is_readable($helperFile)) {
        include_once $helperFile;
        $class = $helperPath['namespace'] . '\\' . $helperName;
        return $class;
      }
    }
    return null;
  }
  
  /**
   * Returns the full path to a script.
   * @param string $relScriptPath The relative path to a script.
   * @return string The full path to the script.
   */
  protected function _getScript($relScriptPath) {
    foreach ($this->_scriptPaths as $scriptPath) {
      $scriptFile = $scriptPath . DIRECTORY_SEPARATOR . $relScriptPath;
      if (is_readable($scriptFile)) {
        return $scriptFile;
      }
    }
    
    throw new EngineException("Could not find template file '{$relScriptPath}'.", EngineException::TEMPLATE_NOT_FOUND);
  }
  
  /**
   * Adds a helper class.
   * @param string $helperClass Fully qualified classname of the helper.
   */
  public function addHelperClass($helperClass) {
    $nameElems = explode('\\', $helperClass);
    $helperName = array_pop($nameElems);
    
    $this->_helperClasses[$helperName] = $helperClass;
  }
  
  /**
   * Returns the helper class by helper name. It only returns helper classes
   * added through addHelperClass().
   * @param string $helperName Name of the helper.
   * @return string Fully qualified classname of the helper or null if the
   * helper name could not be found.
   */
  protected function getHelperClass($helperName) {
    if (isset($this->_helperClasses[$helperName])) {
      return $this->_helperClasses[$helperName];
    }
    return null;
  }
  
  /**
   * Adds a decorator class for a helper.
   * @param string $helperName Name of the helper.
   * @param string $decoratorClass Fully qualified classname of the decorator.
   */
  public function addDecoratorClass($helperName, $decoratorClass, array $config = array()) {
    if (!isset($this->_helperDecorators[$helperName])) {
      $this->_helperDecorators[$helperName] = array();
    }
    
    $this->_helperDecorators[$helperName][] = array('class' => $decoratorClass, 'config' => $config);
  }
  
  /**
   * Returns the helper with the specified name.
   * @param string $helperName The name of the helper.
   * @return Helper The helper with the specified name, or null if the Helper
   * does not exist.
   */
  public function getHelper($helperName) {
    $helperName = ucfirst($helperName);
    
    if (isset($this->_helpers[$helperName])) {
      return $this->_helpers[$helperName];
    }
    
    // find manually added helper class
    $class = $this->getHelperClass($helperName);
    
    // find helper in paths
    if ($class === null) {
      $class = $this->findHelperClassInHelperPaths($helperName);
    }
    
    // check if found
    if ($class === null) {
      return null;
    }
    
    // create helper
    $helper = new $class($this);
        
    if (!$helper instanceof Helper) {
      throw new EngineException("Helper class '{$class}' does not extend \ultimo\phptpl\Helper");
    }

    $helper = $this->decorate($helper, $helperName);
    
    // cache helper
    $this->_helpers[$helperName] = $helper;
    
    return $helper;
  }
  
  /**
   * Decorates a helper.
   * @param \ultimo\phptpl\Helper $helper Helper to decorate.
   * @param string $helperName Name of the helper.
   * @return \ultimo\phptpl\Helper Decorated helper.
   */
  protected function decorate(Helper $helper, $helperName) {
    if (!isset($this->_helperDecorators[$helperName])) {
      return $helper;
    }
    
    foreach ($this->_helperDecorators[$helperName] as $decorator) {
      $decoratorClass = $decorator['class'];
      $helper = new $decoratorClass($helper, $decorator['config']);
    }
    
    
    
    return $helper;
  }
  
  /**
   * Renders a script.
   * @param string $relScriptPath The relative path to a script.
   * @return The rendered data.
   */
  public function render($relScriptPath) {
    $this->_script = $this->_getScript($relScriptPath);
    unset($relScriptPath);
    ob_start();
    include($this->_script);
    return ob_get_clean();
  }
  
  /**
   * Magic function to catch all calls to unexisting methods. The call is
   * threated as a call to a helper.
   * @param string $name The name of the method.
   * @param array $args The arguments of the method.
   * @return mixed The result of the call to the helper.
   */
  public function __call($name, $args) {
    $helper = $this->getHelper($name);
    if ($helper === null) {
      throw new EngineException("Helper '{$name}' not found.", EngineException::HELPER_NOT_FOUND);
    }
    return call_user_func_array($helper, $args);
  }
  
  /**
   * Sets the output encoding character set.
   * @param string $encoding The output encoding character set.
   */
  public function setEncoding($encoding) {
    $this->_encoding = $encoding;
  }
  
  /**
   * Returns the output encoding character set.
   * @return string The output encoding character set.
   */
  public function getEncoding() {
    return $this->_encoding;
  }
}