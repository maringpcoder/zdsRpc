<?php
/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/28
 * Time: 23:46
 */
interface Cache
{
    public function redisConnect();
    public function hSet($key,$filed,$data);
    public function hGet($key,$filed);
}