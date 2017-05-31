<?php

/**
 * 使用mysql线程池，
 * 简单封装 后边根据需要自行修改
 * Class mysql_dbclient
 * @property swoole_client $client
 */
class Mysql_DbClient
{
    public $client;
    public function __construct() {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP);
        $configObj=Yaf_Registry::get("config");
        $Serconfig=$configObj->DbServer->toArray();
        if (!$this->client->connect($Serconfig['localip'],$Serconfig['port'], -1))
        {
            exit("connect failed. Error: {$this->client->errCode}\n");
        }
    }
}
