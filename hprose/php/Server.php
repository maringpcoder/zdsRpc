<?php
require_once dirname(dirname(__DIR__)) . '/hprose/php/lib/Server.php';

/**
 * rpc服务端,提供方法发布,对象等其他操作
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/25
 * Time: 0:32
 */
class rpcServer
{
    private $_server = null;

    public function __construct()
    {
        $process = new swoole_process('hProseServerCall', true);
        $process->start();
    }

    public function hProseServerCall()
    {
        $rootDir = dirname(dirname(__DIR__));
        define('APPLICATION_PATH', $rootDir . '/application');
        $application = new Yaf_Application($rootDir . '/conf/application.ini');
        $application->bootstrap();
        $config_obj = Yaf_Registry::get("config");
        $hProseConfig = $config_obj->hprose->toArray();
        $this->_server = new Server("tcp://" . $hProseConfig['ServerIp'] . ":" . $hProseConfig['port']);
        $this->_server->setErrorTypes(E_ALL);
        $this->_server->setDebugEnabled();
        $this->release();
    }


    private function release()
    {
        $this ->releaseStaticMethods();
    }


    public function releaseStaticMethods()
    {

    }

    public function releaseObjMethods()
    {

    }




    public static function run()
    {
        new self();
    }
}

rpcServer::run();