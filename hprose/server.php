<?php
require_once dirname(__DIR__) . '/hprose/lib/Server.php';
/**
 * rpc服务端,提供方法,函数,类的公开方法，类的静态方法发布,对象等其他操作
 * Created by PhpStorm.
 * User: marin
 * Date: 2017/5/25
 * Time: 0:32
 * @property Server  $_server
 */

class rpcServer
{
    private $_server = null;

    public function __construct()
    {
        $process = new swoole_process([$this ,'hProseServerCall'],false,true);
        $process->start();
        echo 'FID:'.posix_getpid().PHP_EOL;//获取主进程id
        swoole_process::daemon(false);
        swoole_process::wait();
    }

    public function hProseServerCall(swoole_process $worker)
    {
        define('APPLICATION_PATH', dirname(__DIR__));
        define('MYPATH', dirname(APPLICATION_PATH));
        $application = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");
        $application->bootstrap();
        $config_obj = Yaf_Registry::get("config");
        $hProseConfig = $config_obj->hprose->toArray();
        $this->_server = new Server("tcp://" . $hProseConfig['ServerIp'] . ":" . $hProseConfig['port']);
        $this->_server->setErrorTypes(E_ALL);
        $this->_server->setDebugEnabled();//打开调试开关
        $this->release();
        $this->_server->start();
    }

    /**
     * 方法发布
     */
    private function release()
    {
        $this ->releaseStaticMethods();
        $this ->releaseObjMethods();
        $this ->releaseFunction();
    }

    /**
     * 发布类的静态方法
     */
    public function releaseStaticMethods()
    {

    }

    /**
     * 发布对象的方法
     */
    public function releaseObjMethods()
    {
        $this ->_server ->addMethod('add',new hProseServer_User());
        //$this ->_server ->addInstanceMethods($userInfo,'','');
    }

    /**
     * 发布函数
     */
    public function releaseFunction()
    {

        $this->_server->addFunction('testFc');
    }



    public static function run()
    {
//        define('APPLICATION_PATH', dirname(__DIR__));
//        $application = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");
//        $application->bootstrap();
//        $userInfo = new userInfo();
//        echo $userInfo ->add();
        new self();

    }
}

rpcServer::run();
