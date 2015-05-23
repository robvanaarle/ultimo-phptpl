<?php

namespace ultimo\phptpl\helpers;

/**
 * Usage:
 * if (!$this->cache()->printOrUpdate('somekey', 10, 1)):
 *  <div>Data: <?php echo date('H:i:s')></div>
 * $this->cache()->updateEnd(); endif;
 */
class Cache extends \ultimo\phptpl\Helper {
  
  /**
   * ContainerStack for capturing nested fragments.
   * @var ContainerStack 
   */
  protected $containerStack;
  
  /**
   * Cache to store data in.
   * @var \ultimo\util\cache\Cache
   */
  protected $cache = null;
  
  /**
   * Constructor
   * @param Engine $engine The engine the helper is for.
   */
  public function __construct(\ultimo\phptpl\Engine $engine) {
    $this->engine = $engine;
    $this->containerStack =  new \ultimo\phptpl\helpers\support\ContainerStack();
  }
  
  /**
   * Sets the cache to store data in.
   * @param \ultimo\util\cache\Cache $cache The cache to store data in.
   */
  public function setCache(\ultimo\util\cache\Cache $cache) {
    $this->cache = $cache;
  }
  
  /**
   * Helper initial function.
   * @return Authorizer This instance.
   */
  public function __invoke() {
    return $this;
  }
  
  /**
   * Prints the content of the cache or starts capturing a fragment.
   * @param string $key The cache key.
   * @param integer $ttl The ttl of the data in seconds.
   * @param integer $ttlExtend The number of seconds to extend the ttl if data
   * is expired.
   * @return boolean Whether the cache was not expired.
   */
  public function printOrUpdate($key, $ttl=null, $ttlExtend=0) {
    $data = null;
    if (!$this->cache->testLoad($key, $data, $ttlExtend)) {
      $metadata = array(
        'key' => $key,
        'ttl' => $ttl,
        'ttlExtend' => $ttlExtend
      );

      $this->containerStack->captureStart($metadata);
      return false;
    }
    
    echo $data;
    return true;
  }
  
  /**
   * Ends the capturing of a fragment, stores in the the associated cache and
   * prints it.
   */
  public function updateEnd() {
    $container = $this->containerStack->captureEnd();
    $data = $container->getValue();
    
    $metadata = $container->getMetadata();

    $this->cache->save($metadata['key'], $data, $metadata['ttl']);
    
    echo $data;
  }
}