<?php

/**
 * Slim Framework Doctrine middleware (https://github.com/juliangut/slim-doctrine-middleware)
 *
 * @link https://github.com/juliangut/slim-doctrine-middleware for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/slim-doctrine-middleware/master/LICENSE
 */

namespace Ecomm\Services;

use BadMethodCallException;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\MemcacheCache;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\Common\Cache\XcacheCache;
use Memcache;
use Redis;

class DoctrineCacheFactory {

    /**
     * @param array $cacheDriver
     *
     * @return Cache
     */
    public static function configureCache($cacheDriver) {
        $cache = null;

        switch (strtolower($cacheDriver['type'])) {
            case 'apc':
                $cache = new ApcCache();
                break;

            case 'xcache':
                $cache = new XcacheCache();
                break;

            case 'memcache':
                $cache = self::configureMemcacheCache($cacheDriver['host'], $cacheDriver['port']);
                break;

            case 'redis':
                $cache = self::configureRedisCache($cacheDriver['host'], $cacheDriver['port']);
                break;

            case 'array':
                $cache = new ArrayCache();
                break;
        }

        return $cache;
    }

    /**
     * @param string $host
     * @param int $port
     * @throws BadMethodCallException
     *
     * @return MemcacheCache
     */
    private static function configureMemcacheCache($host = '127.0.0.1', $port = 11211) {
        if (!extension_loaded('memcache')) {
            throw new BadMethodCallException('MemcacheCache configured but module \'memcache\' not loaded.');
        }

        $memcache = new Memcache();
        $memcache->addserver($host, $port);

        $cache = new MemcacheCache();
        $cache->setMemcache($memcache);

        return $cache;
    }

    /**
     * @param string $host
     * @param int $port
     * @throws BadMethodCallException
     *
     * @return RedisCache
     */
    private static function configureRedisCache($host = '127.0.0.1', $port = 6379) {
        if (!extension_loaded('redis')) {
            throw new BadMethodCallException('RedisCache configured but module \'redis\' not loaded.');
        }

        $redis = new Redis();
        $redis->connect($host, $port);

        $cache = new RedisCache();
        $cache->setRedis($redis);

        return $cache;
    }

}
