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
	protected $title = 'Redis-based caching';

	/**
	 * {@inheritdoc}
	 */
	public function __construct($options)
	{
		parent::__construct($options);


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

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($key, $ttl = 120)
	{
        $value = '';
       
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

	}
}
