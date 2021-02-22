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

        if(is_null($this->redisServer)) {
            $this->connect();
        }

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

        if(is_null($this->redisServer)) {
            $this->connect();
        }

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

        if(is_null($this->redisServer)) {
            $this->connect();
        }

        if($this->redisServer instanceof \Redis) {
            $result = $this->redisServer->flushDb();
        }

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
	 * Used by integrate_modify_redis_settings added in the title method
	 *
	 * @param array $config_vars
	 */
	public function settings(&$config_vars)
	{
        $txt['redis_ip']       = 'Redis Server IP';
        $txt['redis_port']     = 'Redis Server Port';
        $txt['redis_user']     = 'Redis Server Username';
        $txt['redis_password'] = 'Redis Server Password';

		$config_vars[] = array ('redis_ip',         $txt['redis_ip'],       'string', 'text',   15,     'redis_ip' );
		$config_vars[] = array ('redis_port',       $txt['redis_port'],     'string', 'text',   15,     'redis_port' );
		$config_vars[] = array ('redis_user',       $txt['redis_user'],     'string', 'text',   15,     'redis_user' );
        $config_vars[] = array ('redis_password',   $txt['redis_password'], 'string', 'text',   15,     'redis_password' );
	}

    private function connect()
    {
        global $modSettings;
        
		if(!class_exists('Redis')) {
            return false;
        }

        foreach(array('redis_ip', 'redis_port', 'redis_user', 'redis_password') as $key) {
            if(isset($modSettings[$key])) {
                $$key   = $modSettings[$key];
            }
            else {
                $$key   = '';
            }
        }

        if(empty($redis_ip) || empty($redis_port)) {
            return false;
        }

        $this->redisServer = new \Redis();

        if(!($this->redisServer instanceof \Redis)) {
            return false;
        }

        $this->redisServer->connect($redis_ip, $redis_port);

        if(!empty($redis_user) && !empty($redis_password)) {
            $this->redisServer->auth($redis_user, $redis_password);
        }
        else if(!empty($redis_password)) {
            $this->redisServer->auth($redis_password);
        }

        $this->redisServer->select(0);

        return true;
    }

}
