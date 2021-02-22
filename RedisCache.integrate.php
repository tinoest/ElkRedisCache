<?php

class RedisCache
{

    static public function integrate_save_cache_settings() {{{

        $updateArray    = array();
		$post           = (array)HttpReq::instance()->post;
        foreach(array('redis_ip', 'redis_port', 'redis_user', 'redis_password') as $key) {
            if(array_key_exists($key, $post)) {
                $updateArray[$key] = $post[$key];
            }
            else {
                $updateArray[$key] = '';
            }
        }

        updateSettings($updateArray);
   
    }}}

}
