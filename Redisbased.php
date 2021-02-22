<?php

/**
 * This file contains functions that deal with getting and setting cache values using RedisCache.
 *
 * @author    tinoest http://tinoest.co.uk
 * @copyright tinoest
 * @license   BSD http://opensource.org/licenses/BSD-3-Clause
 * @mod       RedisCache Cache - Redis based caching mechanism
 *
 * @version 1.0.0
 *
 */

namespace ElkArte\sources\subs\CacheMethod;

/**
 * Redisbased caching stores the cache data in a Redis database
 * The performance gain may or may not exist depending on many factors.
 *
 * It requires the a Redis database greater than 5.0.0 to work
 */
class Redisbased extends Cache_Method_Abstract
{
	/**
	 * {@inheritdoc}
	 */
	protected $title        = 'Redis-based caching';

	/**
	 * {@inheritdoc}
	 */
    protected $redisServer  = null;

	/**
	 * {@inheritdoc}
	 */
	public function __construct($options)
	{
		parent::__construct($options);

		if(!class_exists('Redis')) {
            return false;
        }

        $host   = '127.0.0.1';
        $port   = '6379';
        $passwd = '';
        $db     = 0;

        $this->redisServer = new \Redis();

        if(!($this->redisServer instanceof \Redis)) {
            return false;
        }

        $this->redisServer->connect($host, $port);

        if(!empty($password)) {
            $this->redisServer->auth($passwd);
        }

        $this->redisServer->select($db);

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function exists($key) {

	}

	/**
	 * {@inheritdoc}
	 */
	public function put($key, $value, $ttl = 120)
	{
        $result = false;

        if($this->redisServer instanceof \Redis) {
            $this->redisServer->setEx($key, $ttl, $value);
        }

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($key, $ttl = 120)
	{
        $value = '';

        if($this->redisServer instanceof \Redis) {
            $value = $this->redisServer->get($key);
        }
     
        return $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function clean($type = '')
	{
        $result = false;

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isAvailable()
	{
		if( class_exists('Redis') ) {
			return true;
        }

		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function details()
	{
		return array('title' => $this->title, 'version' => '1.0.0');
	}

	/**
	 * Adds the settings to the settings page.
	 *
	 * Used by integrate_modify_cache_settings added in the title method
	 *
	 * @param array $config_vars
	 */
	public function settings(&$config_vars)
	{
        $txt['redis_ip']       = 'Redis Server IP';
        $txt['redis_port']     = 'Redis Server Port';
        $txt['redis_password'] = 'Redis Server Password';

		$config_vars[] = array ('cache_ip',        $txt['redis_ip'],          'file',   'text',     15,     'cache_ip' );
		$config_vars[] = array ('cache_port',      $txt['redis_port'],        'file',   'text',     15,     'cache_port' );
		$config_vars[] = array ('cache_password',  $txt['redis_password'],    'file',   'text',     30,     'cache_password' );
	}
}
