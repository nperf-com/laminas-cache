<?php

/**
 * @see       https://github.com/laminas/laminas-cache for the canonical source repository
 * @copyright https://github.com/laminas/laminas-cache/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-cache/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Cache\Storage\Adapter;

use Laminas\Cache\Exception;
use Laminas\Cache\Storage\ClearByNamespaceInterface;
use Laminas\Cache\Storage\FlushableInterface;
use Laminas\Cache\Storage\TotalSpaceCapableInterface;

/**
 * @category   Laminas
 * @package    Laminas_Cache
 * @subpackage Storage
 */
class ZendServerShm extends AbstractZendServer implements
    ClearByNamespaceInterface,
    FlushableInterface,
    TotalSpaceCapableInterface
{

    /**
     * Constructor
     *
     * @param  null|array|\Traversable|AdapterOptions $options
     * @throws Exception\ExtensionNotLoadedException
     */
    public function __construct($options = array())
    {
        if (!function_exists('zend_shm_cache_store')) {
            throw new Exception\ExtensionNotLoadedException("Missing 'zend_shm_cache_*' functions");
        } elseif (PHP_SAPI == 'cli') {
            throw new Exception\ExtensionNotLoadedException("Zend Server data cache isn't available on cli");
        }

        parent::__construct($options);
    }

    /* FlushableInterface */

    /**
     * Flush the whole storage
     *
     * @return bool
     */
    public function flush()
    {
        return zend_shm_cache_clear();
    }

    /* ClearByNamespaceInterface */

    /**
     * Remove items of given namespace
     *
     * @param string $namespace
     * @return bool
     */
    public function clearByNamespace($namespace)
    {
        return zend_shm_cache_clear($namespace);
    }

    /* TotalSpaceCapableInterface */

    /**
     * Get total space in bytes
     *
     * @return int|float
     */
    public function getTotalSpace()
    {
        return (int) ini_get('zend_datacache.shm.memory_cache_size') * 1048576;
    }

    /* internal */

    /**
     * Store data into Zend Data SHM Cache
     *
     * @param  string $internalKey
     * @param  mixed  $value
     * @param  int    $ttl
     * @return void
     * @throws Exception\RuntimeException
     */
    protected function zdcStore($internalKey, $value, $ttl)
    {
        if (!zend_shm_cache_store($internalKey, $value, $ttl)) {
            $valueType = gettype($value);
            throw new Exception\RuntimeException(
                "zend_shm_cache_store($internalKey, <{$valueType}>, {$ttl}) failed"
            );
        }
    }

    /**
     * Fetch a single item from Zend Data SHM Cache
     *
     * @param  string $internalKey
     * @return mixed The stored value or FALSE if item wasn't found
     * @throws Exception\RuntimeException
     */
    protected function zdcFetch($internalKey)
    {
        return zend_shm_cache_fetch((string) $internalKey);
    }

    /**
     * Fetch multiple items from Zend Data SHM Cache
     *
     * @param  array $internalKeys
     * @return array All found items
     * @throws Exception\RuntimeException
     */
    protected function zdcFetchMulti(array $internalKeys)
    {
        $items = zend_shm_cache_fetch($internalKeys);
        if ($items === false) {
            throw new Exception\RuntimeException("zend_shm_cache_fetch(<array>) failed");
        }
        return $items;
    }

    /**
     * Delete data from Zend Data SHM Cache
     *
     * @param  string $internalKey
     * @return bool
     * @throws Exception\RuntimeException
     */
    protected function zdcDelete($internalKey)
    {
        return zend_shm_cache_delete($internalKey);
    }
}
