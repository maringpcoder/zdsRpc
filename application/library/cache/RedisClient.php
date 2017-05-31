<?php
/**
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/31
 * Time: 16:05
 */
class Cache_RedisClient
{
    public $client;
    public function __construct() {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP);
        $configObj=Yaf_Registry::get("config");
        $Serconfig=$configObj->RedisServer->toArray();
        if (!$this->client->connect($Serconfig['localip'],$Serconfig['port'], -1))
        {
            exit("connect failed. Error: {$this->client->errCode}\n");
        }
    }

}