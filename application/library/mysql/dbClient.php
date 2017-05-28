<?php

/**
 * 使用mysql线程池，
 * 简单封装 后边根据需要自行修改
 * Class mysql_dbclient
 * @property swoole_client $client
 */
class dbClient
{
    private $client;
 
    public function __construct() {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP);
        $config_obj=Yaf_Registry::get("config");
        $Serconfig=$config_obj->DbServer->toArray();
        if (!$this->client->connect($Serconfig['localip'],$Serconfig['port'], -1))
        {
            exit("connect failed. Error: {$this->client->errCode}\n");
        }
    }
 
    public function query($sql) {
        $this->client->send($sql);
        return $this->client->recv();
    }

    public function insert($table,$data){
        $fields = array_keys($data);
        $fields = implode('`,`', $fields);
        $data = $this ->escape($data);
        $value = implode(',', $data);
        $sql = "INSERT INTO `{$table}`(`{$fields}`) VALUE ({$value})";
        return $this ->query($sql);
    }

    /**
     * 批量插入
     */
    public function insertBatch()
    {

    }

    /**
     * 手动转义
     * @param $data
     * @return mixed
     */
    protected function escape($data){

        foreach ($data as $k => $v){
            $data[$k] = "'{$v}'";
        }
        return $data;
    }

    public function close() {
        $this->client->close();
    }
}
