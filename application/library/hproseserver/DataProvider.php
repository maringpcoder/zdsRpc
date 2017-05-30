<?php
/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/28
 * Time: 23:50
 */
class DataProvider  implements Cache
{

    public $redis;

    public function __construct()
    {
    }

    public function redisConnect()
    {
        // TODO: Implement redisConnect() method.
    }

    public function hSet($key, $filed, $data)
    {
        // TODO: Implement hSet() method.
    }

    public function hGet($key, $filed)
    {
        // TODO: Implement hGet() method.
    }

}
