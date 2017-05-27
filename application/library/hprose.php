<?php
require_once dirname(dirname(__DIR__)) . '/hprose/lib/Client.php';

/**
 * 自定义hprsose客户端
 * Class hprose
 */
class hprose
{
    public static $instance;
    public static $client=null;

    public function __construct() {
        $hProseConf = Yaf_Registry::get('config')->hprose->toArray();
        self::$client = new  Client("tcp://" . $hProseConf['ServerIp'] . ":" . $hProseConf['port'],false);
    }

    public static function getdata()
    {
        return self::$client ->add();
    }
    public static function getInstance() {
        if (!(self::$instance instanceof hprose)) {
            self::$instance = new hprose;
        }
        return self::$instance;
    }

    public static function getUserInfo()
    {
        $hprose_config = Yaf_Registry::get("config")->hprose->toArray();
        $client = new Client("tcp://" . $hprose_config['ServerIp'] . ":" . $hprose_config['port'],false);
        return $client ->testFc();
    }
}

