<?php
/**
 * redis缓存驱动
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/28
 * Time: 23:53
 */
class Cache_RedisCache
{
    public $conn = false;

    public static $instance = null;
    protected $_redis;

    public function __construct()
    {
        $this->_redis = new Redis();
        $config =Yaf_Registry::get('config')->Cache->toArray()['redis'];
        try{
            if ($config['socket_type'] === 'unix')
            {
                $success = $this->_redis->connect($config['socket']);
            }
            else // tcp socket
            {
                $success = $this->_redis->connect($config['host'], $config['port'], $config['timeout']);
            }

            if ( ! $success)
            {
                log_message('error', 'Cache: Redis connection failed. Check your configuration.');

                $this->conn = false;
                return;
            }

        }catch (RedisException $exception){

        }
    }

    public static function getInstance($redisType) {
        if (!self::$instance instanceof Cache_RedisCache) {
            self::$instance = new self();
        }

        return self::$instance;
    }

}