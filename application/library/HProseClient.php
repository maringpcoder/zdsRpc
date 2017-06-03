<?php
require_once dirname(dirname(__DIR__)) . '/hprose/lib/Client.php';

/**
 * 自定义hprsose客户端
 * Class hprose
 * @property Client $client
 */
class HProseClient
{
    public static $instance;
    public  $client=null;

    public function __construct()
    {
        $hProseConf = Yaf_Registry::get('config')->hprose->toArray();
        $this ->client = new  Client("tcp://" . $hProseConf['ServerIp'] . ":" . $hProseConf['port'],false);
    }

    public  function getAllUser($page,$pageSize)
    {
        return $this ->client ->getAllUser($page,$pageSize);
    }

    public function editUser()
    {
        return $this ->client ->editUser();
    }


    public static function getInstance()
    {
        if (!(self::$instance instanceof hproseClient)) {
            self::$instance = new hproseClient();
        }
        return self::$instance;
    }

    public function getAgentId()
    {
      return $this ->client ->getAgentId();
    }

    public function updateUser($data,$where)
    {
        return $this ->client ->updateUser($data,$where);
    }

    public function getUserByPage($sql,$page='',$pageSize='',$order='')
    {
        return $this ->client ->userList($sql,$page,$pageSize,$order);
    }

}
