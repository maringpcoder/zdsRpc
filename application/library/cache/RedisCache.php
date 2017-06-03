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
                Log::getInstance()->println('Cache: Redis connection failed. Check your configuration.');
                $this->conn = false;
                return;
            }

            if (isset($config['pwd']) && ! $this->_redis->auth($config['pwd']))
            {
                Log::getInstance()->println('Cache: Redis authentication failed.');
                $this->conn = false;
                return;
            }
            $this->conn = true;
        }catch (RedisException $exception){
            Log::getInstance()->println('Cache: Redis connection refused ('.$exception->getMessage().')');
            $this ->conn = false;
            return;
        }
    }

    public static function getInstance() {
        if (!self::$instance instanceof Cache_RedisCache) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function cache_info()
    {
        return $this->_redis->info();
    }


    public function __destruct()
    {
        if ($this->_redis && $this->conn)
        {
            $this->_redis->close();
        }
    }

    public function hSet($key, $field, $value) {
        if ($this->conn) {
            $this ->_redis->hSet($key,$field,$value);
        } else {
            return "";
        }
    }

    /**
     * 获取所有的field-value
     * @param $key
     * @return array|bool
     */
    public function hGetAll($key) {
        if ($this->conn) {
            $result = $this->_redis->hGetAll($key);
            if (!$result) {
                return array();
            }
            return $result;
        }
        return false;
    }

    /**
     * 获取hash field
     * @param $key
     * @param $field
     * @return bool|string
     */
    public function hGet($key, $field) {
        if ($this->conn) {
            $result = $this->_redis->hGet($key, $field);
            if (false === $result) {
                return false;
            }
            return $result;
        }
        return false;
    }

    /**
     *
     * @param $key
     * @param $field
     * @return bool|string
     */
    public function hGet2($key, $field)
    {
        if ($this->conn) {
            $result = $this->_redis->hGet($key, $field);
            if (false === $result) {
                return false;
            }
            return $result;
        }
        return false;
    }

    /**
     * 批量获取hash的存储的值
     * @param $key
     * @param $fieldArray
     * @return array|bool
     */
    public function hMget($key, $fieldArray) {
        if (!is_array($fieldArray)) {
            return false;
        }

        $result = array();
        if ($this->conn) {
            $result = $this->_redis->hMget($key, $fieldArray);
        }

        if ($result) {
            //记录缓存不存在的记录数量
            $countFalse = 0;
            foreach ($fieldArray as $index) {
                if (isset($result[$index]) && $result[$index]) {
                    $result[$index] = unserialize($result[$index]);
                    if(is_array($result[$index]) && isset($result[$index]['cache_time'])){
                        unset($result[$index]['cache_time']);
                    }
                } else {
                    $result[$index] = array();
                    $countFalse++;
                }
            }
            //如果缓存中一条记录都查不到,返回false
            if ($countFalse == count($fieldArray)) {
                return false;
            }
        }

        return $result;
    }

    /**
     * 批量设置hash
     * @param $key
     * @param $fieldValueArray
     * @return bool
     */
    public function hMset($key, $fieldValueArray) {
        if (!is_array($fieldValueArray)) {
            return false;
        }

        $data = array();
        foreach ($fieldValueArray as $field => $value) {
            $data[$field] = serialize($value);
        }
        if ($this->conn) {
            return $this->_redis->hMset($key, $data);
        }
    }

    /**
     * 删除hash field
     * @param $key
     * @param $field
     * @return bool|int
     */
    public function hDel($key, $field) {
        if ($this->conn) {
            return $this->_redis->hDel($key, $field);
        }
        Log::getInstance()->println('Cache Redis is disconnected.....');
        return false;
    }

    /**
     * 入左链表头
     * @param $key
     * @param $data
     * @return bool|int
     */
    public function lPush($key, $data) {
        if ($this->conn) {
            return $this->_redis->lPush($key, json_encode($data));
        }
        Log::getInstance()->println('Cache Redis is disconnected.....');
        return false;
    }


    /**
     * 检查hash key or field 是否存在
     * @param $key
     * @param $field
     * @return bool
     */
    public function hExists($key, $field)
    {
        return $this->_redis->hExists($key, $field);
    }

    /**
     * 原子性的+操作
     * @param $key
     * @param $field
     * @param $increment
     * @return int
     */
    public function hIncrBy($key, $field, $increment)
    {
        return $this->_redis->hIncrBy($key, $field, $increment);
    }

    public function lLen($key) {
        if ($this->conn) {
            return $this->_redis->lLen($key);
        }
    }

}